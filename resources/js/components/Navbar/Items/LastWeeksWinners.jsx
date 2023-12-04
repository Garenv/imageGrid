import React from 'react';
import { useQuery } from 'react-query';
import ApiClient from "../../utlities/AxiosClient.jsx";
import { useSharedStyles } from "../../utlities/SharedStyles.jsx";
import '../../../../sass/lastWeeksWinners.scss';

const LastWeeksWinners = () => {
    const sharedStyles = useSharedStyles();
    const fetchLastWeeksWinners = async () => {
        const { data } = await ApiClient.get('/get-last-weeks-winners');
        console.log(data);
        return data;
    };

    const { data: gridData } = useQuery('pastUploads', fetchLastWeeksWinners);

    return(
        <>
            <h3 className="contact-form-title-text text-black mt-5 pt-5"><u>Last Week's Winners</u></h3>
            {
                gridData && gridData.length !== 0 ?
                    <section className="gallery">
                        <div className="container">
                            <div className="img-container">
                                {
                                    gridData.map((photos, index) => {
                                        return (
                                            <>

                                                <div className="card_image"><img src={photos.url} className="giftCards img-fluid" alt="Gift Card Images"/></div>
                                                <div className={`card_content ${photos.place === "1st Place" ? 'firstPlace' : photos.place === "2nd Place" ? 'secondPlace' : photos.place === "3rd Place" ? 'thirdPlace' : null}`}>
                                                    <h2 className="card_title text-black">{photos.name}</h2>
                                                    <h2 className='card_title text-black'>{photos.place}</h2>
                                                    <h2 className="card_title text-black">Likes: {photos.likes}</h2>
                                                </div>

                                            </>
                                        )
                                    })
                                }
                            </div>
                        </div>
                    </section> : <h1 className={sharedStyles.centered}>You'll see last week's winners here at 12:00am EST every Sunday</h1>
            }
        </>
    );
};

export default LastWeeksWinners;
