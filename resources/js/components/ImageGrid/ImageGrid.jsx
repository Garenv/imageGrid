import React, {useContext, useEffect, useState} from 'react';
import UserContext from "../UserContext.jsx";
import AxiosClient from "../utlities/AxiosClient.jsx";
import 'react-toastify/dist/ReactToastify.css';
import { ToastContainer, toast } from "react-toastify";
import { Modal, Button, Form } from "react-bootstrap";
import '../../../sass/imageGrid.scss';
import { useQuery, useMutation, useQueryClient } from 'react-query';
import LoadingSpinner from "../utlities/LoadingSpinner/LoadingSpinner.jsx";
import { useSharedStyles } from "../utlities/SharedStyles.jsx";

const ImageGrid = () => {
    const userId = useContext(UserContext).userId;
    const queryClient = useQueryClient();
    const [userLikedPhotos, setUserLikedPhotos] = useState({});
    const [file, setFile] = useState(null);
    const [imagePreview, setImagePreview] = useState(null);
    const [show, setShow] = useState(false);
    const [showVerifyDelete, setShowVerifyDelete] = useState(false);
    const sharedClasses = useSharedStyles();
    const [isScrollToTheTopArrowVisible, setIsScrollToTheTopArrowVisible] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const handleVerifyDeleteClose = () => setShowVerifyDelete(false);
    const handleVerifyDeleteShow = () => setShowVerifyDelete(true);

    const toggleVisibility = () => {
        if (window.pageYOffset > 100) {
            setIsScrollToTheTopArrowVisible(true);
        } else {
            setIsScrollToTheTopArrowVisible(false);
        }
    };

    const scrollToTop = () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    };

    useEffect(() => {
        window.addEventListener('scroll', toggleVisibility);

        return () => {
            window.removeEventListener('scroll', toggleVisibility);
        };
    }, []);

    const fetchUserUploads = async () => {
        const { data } = await AxiosClient.get('/get-user-uploads-data');
        return data;
    };

    const uploadImage = async (file) => {
        let formData = new FormData();
        formData.append("image", file);

        try {
            const response = await AxiosClient.post('/file-upload', formData);

            let okStatus= response.status;
            let successMessage = response.data.message;

            if(okStatus) {
                setShow(false);
            }

            toast.success(successMessage, {
                closeOnClick: false,
                closeButton: false,
                autoClose: 1000
            });

            return response.data;

        } catch (error) {
            if (error.response) {
                let errorMessage = error.response.data.message;

                toast.error(errorMessage, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1000
                });

                throw new Error('Upload failed');
            }
        }
    };

    const { data: gridData, isLoading } = useQuery('userUploads', fetchUserUploads);

    const uploadMutation = useMutation(uploadImage, {
        onSuccess: (newImage) => {

            // make sure `newImage` has the right structure and append it to existing data.
            queryClient.setQueryData('userUploads', (oldData) => [
                ...oldData,
                newImage
            ]);
            handleClose();
            // manually invalidate queries to force a refetch and re-render
            queryClient.invalidateQueries('userUploads');
        },
    });


    const handleImageChange = (e) => {
        const file = e.target.files[0];
        setFile(file);

        if (file && file.type.includes("image")) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setImagePreview(reader.result);
                handleShow();
            };
            reader.readAsDataURL(file);
        } else {
            console.error("Unsupported file type");
        }
    };

    const handleLike = (likedPhotoUserId, userName, likedPhotoId) => {

        let data = {
            'UserID': likedPhotoUserId,
            'userName' : userName,
            'likedPhotoId' : likedPhotoId
        };

        AxiosClient.post('/like', data)
            .then(resp => {

                if(resp.status === 200) {
                    userLikedPhotos[likedPhotoUserId] = true;
                    gridData.find(photo => photo.UserID === likedPhotoUserId).likes++;
                    gridData.find(photo => photo.UserID === likedPhotoUserId).is_liked = 1;
                    setUserLikedPhotos({...userLikedPhotos});

                    toast.success(`You liked ${userName}'s photo!`, {
                        closeOnClick: false,
                        progress: false,
                        closeButton: false,
                        autoClose: 1100
                    });
                }

            }).catch(err => {

                if(err.response.status !== 200) {
                    toast.error(err.response.data.message, {
                        closeOnClick: false,
                        progress: false,
                        closeButton: false,
                        autoClose: 1100
                    });
                }

            });
    };

    const handleDislike = (likedPhotoUserId, userName, likedPhotoId) => {

        let data = {
            'UserID': likedPhotoUserId,
            'userName' : userName,
            'dislikedPhotoId' : likedPhotoId
        };

        AxiosClient.post('/dislike', data)
            .then(resp => {

                if(resp.status === 200) {
                    delete userLikedPhotos[likedPhotoUserId];
                    gridData.find(photo => photo.UserID === likedPhotoUserId).likes--;
                    gridData.find(photo => photo.UserID === likedPhotoUserId).is_liked = 0;
                    setUserLikedPhotos({...userLikedPhotos});

                    toast.error(`You disliked ${userName}'s photo!`, {
                        closeOnClick: false,
                        progress: false,
                        closeButton: false,
                        autoClose: 1100
                    });

                }
            }).catch(err => {

                if(err.response.status !== 200) {
                    toast.error(err.response.data.message, {
                        closeOnClick: false,
                        progress: false,
                        closeButton: false,
                        autoClose: 1100
                    });
                }
            });
    };

    const deleteUserUpload = (likedPhotoUserId) => {
        AxiosClient.delete(`/delete-user-upload?UserID=${likedPhotoUserId}`)
            .then(resp => {

                if(resp.status === 200) {
                    toast.success(resp.data.message, {
                        closeOnClick: false,
                        closeButton: false,
                        autoClose: 1100
                    });
                }

            }).catch(err => {

            if(err.response.status !== 200) {
                toast.error(err.response.data.message, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1400
                });
            }
        });
    }

    const deleteMutation = useMutation(deleteUserUpload, {
        onSuccess: () => {
            queryClient.invalidateQueries('userUploads');
        },
        onMutate: async (likedPhotoUserId) => {
            await queryClient.cancelQueries('userUploads');

            const previousData = queryClient.getQueryData('userUploads');

            // remove the deleted item from the UI
            const newData = previousData.filter(
                (photo) => photo.UserID !== likedPhotoUserId
            );

            queryClient.setQueryData('userUploads', newData);

            return { previousData };
        },
        onError: (err, variables, context) => {
            // if deletion fails, go back to the previous state
            if(context?.previousData) {
                queryClient.setQueryData('userUploads', context.previousData);
            }
        },
    });

    const verifyDelete = (userId) => {
        return(
            <Modal show={showVerifyDelete} onHide={handleVerifyDeleteClose} centered size="lg">
                <Modal.Header closeButton>
                    <Modal.Title className="text-black">Photo Deletion</Modal.Title>
                </Modal.Header>
                <Modal.Body className="d-flex justify-content-center">
                    <h1 className="text-black text-center">Are you sure you want to delete your photo?</h1>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleVerifyDeleteClose}>
                        Close
                    </Button>
                    <Button className="bg-danger" onClick={() => {deleteMutation.mutate(userId); handleVerifyDeleteClose()}}>Delete</Button>
                </Modal.Footer>
            </Modal>
        );
    };

    const noUploadsYet = () => {
        if(uploadMutation.isLoading) {
            return;
        }

        return <h1 className={sharedClasses.centered}>No Uploads, yet.  Be the first to upload!</h1>
    };

    return(
        <>

            {uploadMutation.isLoading && <LoadingSpinner/>}

            {isScrollToTheTopArrowVisible && (
                <button onClick={scrollToTop} className="scroll-to-top">
                    ↑
                </button>
            )}

            <ToastContainer
                hideProgressBar
                closeButton={false}
            />

            <div className="btn-wrapper pt-5">
                <Form.Group controlId="formFile" className="mb-5">
                    <div className="custom-file-upload pt-5">
                        <label htmlFor="file" className="btn btn-outline-secondary">
                            Upload
                        </label>
                        <input
                            id="file"
                            type="file"
                            onChange={handleImageChange}
                            style={{ display: "none" }}
                            accept=".jpg, .jpeg, .png, .heic"
                        />
                    </div>
                </Form.Group>
            </div>

            <Modal show={show} onHide={handleClose} centered size="lg">
                <Modal.Header closeButton>
                    <Modal.Title>Image Preview</Modal.Title>
                </Modal.Header>
                <Modal.Body className="d-flex justify-content-center">
                    {imagePreview && (
                        <img src={imagePreview} alt="preview" className="img-fluid" />
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Close
                    </Button>
                    <Button
                        variant="primary"
                        onClick={() => {uploadMutation.mutate(file); handleClose()}}
                        disabled={uploadMutation.isLoading}
                    >
                        Submit
                    </Button>
                </Modal.Footer>
            </Modal>

            {verifyDelete(userId)}

            {
                gridData && gridData.length !== 0 ?
                    <section className="gallery vh-100">
                        <div className="container">
                            <div className="img-container">
                                {
                                    gridData.map((photos, index) => {
                                        return (
                                            <>
                                                <img src={photos.url} className="img-fluid" alt="photo" />

                                                <div className="userDetails">
                                                    <span className="likesAmt" style={{color: '#000000'}}>❤️ {photos.likes}</span><br/>
                                                    {!photos.is_liked ?
                                                        <Button className="bg-success" onClick={() => handleLike(photos.UserID, photos.name, photos.photo_id, photos.is_liked)}>👍</Button> :
                                                        <Button className="bg-danger" onClick={() => handleDislike(photos.UserID, photos.name, photos.photo_id, photos.is_liked)}>👎</Button>
                                                    }
                                                    <br/>
                                                    <span style={{ color: "black" }}>{photos.name} {userId === photos.UserID ? <h6 style={{ color: "black" }}>(You)</h6> : null}</span>
                                                    {userId === photos.UserID ? <Button className="bg-danger" onClick={() => handleVerifyDeleteShow(photos.UserID)}>Delete</Button> : null}
                                                </div>

                                                <br/>
                                            </>
                                        )
                                    })
                                }
                            </div>
                        </div>
                    </section> : noUploadsYet()
            }
        </>
    );
}

export default ImageGrid;
