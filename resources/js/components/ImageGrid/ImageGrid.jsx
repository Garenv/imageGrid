import React, { useContext, useEffect, useState } from 'react';
import UserContext from "../UserContext.jsx";
import ApiClient from "../utlities/AxiosClient.jsx";
import 'react-toastify/dist/ReactToastify.css';
import { ToastContainer, toast } from "react-toastify";
import { Modal, Button, Form } from "react-bootstrap";
import '../../../sass/imageGrid.scss';


const ImageGrid = () => {
    const userId = useContext(UserContext);
    const [gridData, setGridData] = useState([]);
    const [userLikedPhotos, setUserLikedPhotos] = useState({});
    const [show, setShow] = useState(false);
    const [imagePreview, setImagePreview] = useState(null);

    useEffect(() => {
        ApiClient.get('/get-user-uploads-data')
            .then(resp => {
                setGridData(resp.data);
            }).catch(err => {
            console.log(err);
        });

    }, []);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const handleImageChange = (e) => {
        const file = e.target.files[0];

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

    const fileUpload = () => {
        let formData = new FormData();
        let uploadedFile = document.querySelector('#file');
        formData.append("image", uploadedFile.files[0]);

        ApiClient.post('/file-upload', formData)
            .then(resp => {

                console.log(resp.data);

                let okStatus       = resp.status;
                let successMessage = resp.data.message;

                if(okStatus) {
                    setShow(false);
                }

                toast.success(successMessage, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1400,
                });

            }).catch(error => {
            let errorMessage       = error.response.data.message;

            toast.error(errorMessage, {
                closeOnClick: false,
                closeButton: false,
                autoClose: 1400
            });
        });
    };

    const handleLike = (likedPhotoUserId, userName, likedPhotoId) => {

        let data = {
            'UserID': likedPhotoUserId,
            'userName' : userName,
            'likedPhotoId' : likedPhotoId
        };

        userLikedPhotos[likedPhotoUserId] = true;
        gridData.find(photo => photo.UserID === likedPhotoUserId).likes++;
        gridData.find(photo => photo.UserID === likedPhotoUserId).is_liked = 1;

        toast.success(`You liked ${userName}'s photo!`, {
            closeOnClick: false,
            progress: false,
            closeButton: false
        });

        ApiClient.post('/like', data).catch(err => { console.log(err); });

        setUserLikedPhotos({...userLikedPhotos});
    };

    const handleDislike = (likedPhotoUserId, userName, likedPhotoId) => {

        let data = {
            'UserID': likedPhotoUserId,
            'userName' : userName,
            'dislikedPhotoId' : likedPhotoId
        };

        // dislike
        delete userLikedPhotos[likedPhotoUserId];
        gridData.find(photo => photo.UserID === likedPhotoUserId).likes--;
        gridData.find(photo => photo.UserID === likedPhotoUserId).is_liked = 0;

        toast.error(`You disliked ${userName}'s photo!`, {
            closeOnClick: false,
            progress: false,
            closeButton: false
        });

        ApiClient.post('/dislike', data).catch(err => {console.log(err);});

        setUserLikedPhotos({...userLikedPhotos});

    };

    const deleteUserUpload = (likedPhotoUserId) => {

        ApiClient.delete(`/delete-user-upload?UserID=${likedPhotoUserId}`)
            .then(resp => {
                let okStatus       = resp.status;
                let successMessage = resp.data.message;

                if(okStatus) {
                    setShow(false);
                }

                toast.success(successMessage, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1400
                });

                setTimeout(() => {
                    window.location.reload(false);
                },1400);

            }).catch(error => {
            let errorMessage       = error.response.data.message;

            toast.error(errorMessage, {
                closeOnClick: false,
                closeButton: false,
                autoClose: 1400
            });

            setTimeout(() => {
                window.location.reload(false);
            },1400);

        });
    }

    return(
        <>
            <Form.Group controlId="formFile" className="mb-3">
                <Form.Label>Upload Image</Form.Label>
                <div className="custom-file-upload">
                    <label htmlFor="file" className="btn btn-outline-secondary">
                        Choose File
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
                    <Button variant="primary" onClick={fileUpload}>Save Changes</Button>
                </Modal.Footer>
            </Modal>

            {
                gridData.length !== 0 ?
                    <section className="gallery">
                        <div className="container">
                            <div className="img-container">
                                {
                                    gridData.map((photos, index) => {
                                        console.log(photos);
                                        return (
                                            <>
                                                <ToastContainer
                                                    hideProgressBar
                                                    closeButton={false}
                                                />

                                                <img src={photos.url} className="img-fluid" alt="photo" loading="lazy" />

                                                <div className="userDetails">
                                                    <span className="likesAmt" style={{color: '#000000'}}>❤️ {photos.likes}</span><br/>
                                                    {!photos.is_liked ?
                                                        <Button className="bg-success" onClick={() => handleLike(photos.UserID, photos.name, photos.photo_id, photos.is_liked)}>👍</Button> :
                                                        <Button className="bg-danger" onClick={() => handleDislike(photos.UserID, photos.name, photos.photo_id, photos.is_liked)}>👎</Button>
                                                    }
                                                    <br/>
                                                    <span className="name">{photos.name} {userId === photos.UserID ? <h6 className="you">(You)</h6> : null}</span>
                                                    {userId === photos.UserID ? <Button variant="danger" onClick={() => deleteUserUpload(photos.UserID)}>Delete</Button> : null}
                                                </div>
                                            </>
                                        )
                                    })
                                }
                            </div>
                        </div>
                    </section> : <h1>No Uploads, yet.  Be the first to upload!</h1>
            }
        </>
    );
}

export default ImageGrid;
