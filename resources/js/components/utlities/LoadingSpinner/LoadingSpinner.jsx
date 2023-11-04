import { makeStyles } from '@mui/styles';
import React from 'react';

const useStyles = makeStyles(() => ({
    spinIcon: {
        animation: '$uniqueSpinner 1s infinite linear'
    },

    '@keyframes uniqueSpinner': {
        '0%': {
            transform: 'rotate(0deg)',
        },
        '100%': {
            transform: 'rotate(360deg)',
        },
    },


    centered: {
        position: 'absolute',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        zIndex: 1000,
    },
}));

const LoadingSpinner = () => {
    const classes = useStyles();

    return(
        <div className={classes.centered}>
            <img src="https://phopixel.s3.amazonaws.com/stage/assets/images/logos/phopixel_icon_small.png" alt="Icon" className={classes.spinIcon} />
        </div>
    );
};

export default LoadingSpinner;
