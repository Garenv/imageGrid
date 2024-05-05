import React from 'react';
import '../../../sass/notFound.scss';

const NotFound = () => {
    return (
        <div className="error-contain">

            <div className="error-text">
                <h1>404</h1>
                <p>Page not found.</p>
                <p>We searched high and low.</p>
            </div>

            <div className="binoculars">
                <div className="back-bino"></div>
                <div className="left-bino"></div>
                <div className="right-bino"></div>
                <div className="left-bino-lense">
                    <div className="eye"></div>
                </div>
                <div className="right-bino-lense">
                    <div className="eye"></div>
                </div>
            </div>

        </div>
    );
};

export default NotFound;
