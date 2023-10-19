import React from 'react';
import { useQuery } from 'react-query';
import ApiClient from "../../utlities/AxiosClient.jsx";


const PastUploads = () => {
    const fetchPastUploads = async () => {
        const { data } = await ApiClient.get('/get-users-past-uploads');
        return data;
    };

    const { data: gridData } = useQuery('pastUploads', fetchPastUploads);

    return(
        <>
            <h3 className="contact-form-title-text text-black mt-5 pt-5"><u>Past Uploads</u></h3>
            {
                gridData && gridData.map ?
                    <section className="gallery">
                        <div className="container">
                            <div className="img-container">
                                {
                                    gridData.map((photos, index) => {
                                        return (
                                            <>
                                                <img src={photos.url} className="img-fluid" alt="photo" />

                                                <div className="userDetails">
                                                    <span className="likesAmt" style={{color: '#000000'}}>❤️ {photos.likes}</span><br/>
                                                </div>

                                                <br/>
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
};

export default PastUploads;
