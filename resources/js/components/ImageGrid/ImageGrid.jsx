import React, { useContext, useEffect, useState } from 'react';
import UserContext from "../UserContext.jsx";
import ApiClient from "../utlities/AxiosClient.jsx";
import 'react-toastify/dist/ReactToastify.css';
import { ToastContainer, toast } from "react-toastify";
import { Button, Image, Modal } from "react-bootstrap";

const ImageGrid = () => {
    const userId = useContext(UserContext);
    const [gridData, setGridData] = useState([]);
    const [userLikedPhotos, setUserLikedPhotos] = useState({});

    useEffect(() => {
        console.log(userId);

        ApiClient.get('/get-user-uploads-data',)
            .then(resp => {
                console.log(resp.data);
                setGridData(resp.data);
            }).catch(err => {
            console.log(err);
        });

    }, []);

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

        ApiClient.post('/like', data)
            .then(resp => {
                console.log(resp.data);
            }).catch(err => {
            console.log(err);
        });

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

        ApiClient.post('/dislike', data)
            .then(resp => {
                console.log(resp.data);
            }).catch(err => {
            console.log(err);
        });

        setUserLikedPhotos({...userLikedPhotos});

    };

    return(
        <>
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

                                                <img src={photos.url} className="img-fluid" alt="photo" loading="lazy"/>

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
