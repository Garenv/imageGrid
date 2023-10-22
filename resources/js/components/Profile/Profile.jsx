import React, {useCallback, useEffect, useRef, useState} from 'react';
import AxiosClient from "../utlities/AxiosClient.jsx";
import Card from 'react-bootstrap/Card';
import ListGroup from 'react-bootstrap/ListGroup';
import Button from 'react-bootstrap/Button';
import { useMutation, useQueryClient } from 'react-query';
import { ToastContainer, toast } from "react-toastify";

const Profile = () => {
    const [profileData, setProfileData] = useState(null);
    // const [uploadedImage, setUploadedImage] = useState(null);
    const fileInputRef = useRef(null);
    const queryClient = useQueryClient();

    useEffect(() => {
        AxiosClient.get('/get-profile-data')
            .then(resp => {
                setProfileData(resp.data);
            })
    }, []);

    const mutation = useMutation(async (formData) => {
        // Prepare the file for sending
        // const formData = new FormData();
        // formData.append('avatar', newAvatar); // 'avatar' is the field name. You can adjust as per your server's requirements

        // Send the avatar to the server
        return AxiosClient.post('/upload-avatar-image', formData)
            .then(resp => {
                console.log(resp);
                // If you want to update the cache with a response from the server (e.g., a new image URL or some confirmation), you can do it here
                return resp.data;
            })
            .catch(err => {
                console.log(err);
                throw err; // Ensures the mutation catches this as an error
            });
    }, {
        onSuccess: (data) => {
            queryClient.setQueryData('userAvatar', {avatarImage: data});
        }
    });

    const onFileChange = useCallback((event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const formData = new FormData();
                formData.append('avatar', file);

                // Provide an optimistic update.
                const previousAvatar = queryClient.getQueryData('userAvatar');
                queryClient.setQueryData('userAvatar', {avatarImage: e.target.result});

                mutation.mutate(formData, {
                    onError: () => {
                        // If there's an error, rollback to the previous avatar.
                        queryClient.setQueryData('userAvatar', previousAvatar);
                    },
                    onSettled: () => {
                        // Refetch the data after the mutation is done (whether it's successful or failed)
                        queryClient.invalidateQueries('userAvatar');
                    }
                });
            };
            reader.readAsDataURL(file);
        }
    }, [mutation, queryClient]);




    const onImageClick = useCallback(() => {
        fileInputRef.current.click();
    }, []);

    return(
        <>
            <ToastContainer
                hideProgressBar
                closeButton={false}
            />

            {
                profileData ?
                <div className="d-flex justify-content-center align-items-center" style={{ height: "100vh" }}>
                    <Card style={{ width: '18rem' }}>

                        {/*<Card.Body>*/}
                        {/*    <Card.Title>Card Title</Card.Title>*/}
                        {/*    <Card.Text>*/}
                        {/*        Some quick example text to build on the card title and make up the*/}
                        {/*        bulk of the card's content.*/}
                        {/*    </Card.Text>*/}
                        {/*</Card.Body>*/}
                        <ListGroup className="list-group-flush">
                            <ListGroup.Item>{profileData.name}</ListGroup.Item>
                            <ListGroup.Item>{profileData.email}</ListGroup.Item>
                        </ListGroup>

                        <Button
                            variant="outline-primary"
                            onClick={onImageClick}
                        >
                            Upload Avatar Photo
                        </Button>
                        <input
                            type="file"
                            ref={fileInputRef}
                            onChange={onFileChange}
                            style={{ display: "none" }}
                            name="avatar"
                        />
                        {/*<div style={{ marginTop: '20px' }}>*/}
                        {/*    { uploadedImage &&*/}
                        {/*        <img src={uploadedImage} alt="Uploaded" style={{ width: '100%', maxHeight: '180px' }} />*/}
                        {/*    }*/}
                        {/*</div>*/}

                    </Card>
                </div> : <h1>Something went wrong!</h1>
            }
        </>
    );
}

export default Profile;
