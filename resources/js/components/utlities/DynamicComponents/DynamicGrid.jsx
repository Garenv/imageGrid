import React from 'react';
import '../../../../sass/imageBattles/hallOfFame.scss';
import Spinner from "react-bootstrap/Spinner";
import { useSharedStyles } from "../SharedStyles.jsx";

const DynamicGrid = ({ data, isLoading }) => {
    const sharedClasses = useSharedStyles();

    if (isLoading) return <Spinner animation="border" variant="primary" className={sharedClasses.centered}/>;

    return (
        data && data.length !== 0 ? (
            <article className="images-sec-wrap pt-5">
                <div className="images-sec">
                    <ul className="images-sec-middle" id="vid-grid">
                        <li className="thumb-wrap">
                            {data.map((item, index) => {
                                return (
                                    <div key={index}>
                                        <img className="thumb" alt="photo" src={item.image_url}/>
                                        <div className="thumb-info text-center">
                                            <h2 className="thumb-title">{item.name}</h2>
                                            <h2 className="thumb-title text-black"><u>Prompt:</u> {item.prompt}</h2>
                                            <br/>
                                        </div>
                                    </div>
                                );
                            })}
                        </li>
                    </ul>
                </div>
            </article>) : <div className="middle-of-screen">No Hall of Fame inductees, yet!</div>
    );
}

export default DynamicGrid;
