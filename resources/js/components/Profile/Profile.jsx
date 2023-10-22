import React, {useCallback, useEffect, useRef, useState} from 'react';
import AxiosClient from "../utlities/AxiosClient.jsx";
import Card from 'react-bootstrap/Card';
import ListGroup from 'react-bootstrap/ListGroup';
import Button from 'react-bootstrap/Button';
import { useMutation, useQueryClient } from 'react-query';
import { ToastContainer, toast } from "react-toastify";
// import { CircularProgress } from "@mui/material";
import { makeStyles } from '@mui/styles';

const useStyles = makeStyles((theme) => ({
    heartbeatIcon: {
        animation: '$heartbeat 1s infinite'
    },

    '@keyframes heartbeat': {
        '0%': {
            transform: 'scale(1)',
        },
        '50%': {
            transform: 'scale(1.1)',
        },
        '100%': {
            transform: 'scale(1)',
        },
    },

    centered: {
        position: 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        background: 'grey',
        zIndex: 1000,
    },
}));

const Profile = () => {
    const [profileData, setProfileData] = useState(null);
    const fileInputRef = useRef(null);
    const queryClient = useQueryClient();
    const classes = useStyles();

    useEffect(() => {
        AxiosClient.get('/get-profile-data')
            .then(resp => {
                setProfileData(resp.data);
            })
    }, []);

    const mutation = useMutation(async (formData) => {

        return AxiosClient.post('/upload-avatar-image', formData)
            .then(resp => {
                console.log(resp);

                toast.success(resp.data.message, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1000
                });

                return resp.data;
            })
            .catch(err => {
                console.log(err.response.data);

                let errorMessage = err.response.data.message;

                toast.error(errorMessage, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1000
                });

                throw new Error('Upload failed');
            });
    }, {
        onSuccess: (data) => {
            queryClient.setQueryData('userAvatar', {avatarImage: data});
        },

        onSettled: () => {
            queryClient.invalidateQueries('userAvatar');
        }
    });

    const onFileChange = useCallback((event) => {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const formData = new FormData();
                formData.append('avatar', file);

                mutation.mutate(formData, {
                    onSettled: () => {
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
                    {/* Center CircularProgress in the middle of the screen */}

                    {mutation.isLoading && (
                        <div className={classes.centered}>
                            <img src="https://phopixel.s3.amazonaws.com/assets/images/logos/phopixelSpinner-nobackground-v2.png" alt="Icon" className={classes.heartbeatIcon} />
                        </div>
                    )}

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
