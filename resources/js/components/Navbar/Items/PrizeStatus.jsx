import React, { useEffect, useState } from 'react';
import AxiosClient from "../../utlities/AxiosClient.jsx";
import { useSharedStyles } from "../../utlities/SharedStyles.jsx";

const PrizeStatus = () => {
    const [thisWeeksWinnerData, setThisWeeksWinnerData] = useState(null);
    const sharedStyles = useSharedStyles();

    useEffect(() => {
        AxiosClient.get('/get-this-weeks-winners')
            .then(resp => {
                setThisWeeksWinnerData(resp.data);
            })
    }, []);

    return(
        <>
            {
                thisWeeksWinnerData ?
                    <section className="gallery" style={{ paddingTop: "5rem" }}>
                        <div className="container">
                            <div className="img-container">
                                {
                                    <>
                                        <img src={thisWeeksWinnerData.url} className="img-fluid" alt=""/>
                                        <div className="text-black">You've won {thisWeeksWinnerData.place}, congrats!  Your prize is on the way!</div>
                                    </>
                                }
                            </div>
                        </div>
                    </section> : <h1 className={sharedStyles.centered}>You didn't win this week.</h1>
            }
        </>
    );
};

export default PrizeStatus;
