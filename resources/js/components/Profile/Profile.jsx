import React, {useCallback, useEffect, useRef, useState} from 'react';
import AxiosClient from "../utlities/AxiosClient.jsx";
import Card from 'react-bootstrap/Card';
import ListGroup from 'react-bootstrap/ListGroup';
import Button from 'react-bootstrap/Button';
import { useMutation, useQueryClient } from 'react-query';
import { ToastContainer, toast } from "react-toastify";
import LoadingSpinner from "../utlities/LoadingSpinner/LoadingSpinner.jsx";

const Profile = () => {
    const [profileData, setProfileData] = useState(null);
    const fileInputRef = useRef(null);
    const queryClient = useQueryClient();

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

                    {mutation.isLoading && (
                        <LoadingSpinner/>
                    )}

                    <Card style={{ width: '18rem' }}>

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

                    </Card>
                </div> : <h1>Something went wrong!</h1>
            }
        </>
    );
}

export default Profile;
