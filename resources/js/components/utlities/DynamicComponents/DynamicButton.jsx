import React from 'react';
import Button from '@mui/material/Button';

const DynamicButton = ({ variant, onClick, children }) => {
    return(
        <Button
            variant={variant}
            onClick={onClick}
        >
            {children}
        </Button>
    );
}

export default DynamicButton;
