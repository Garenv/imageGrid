import React from 'react';
import TextField from '@mui/material/TextField';

const DynamicTextField = ({ id, label, variant, value, onChange, ...props }) => {
    return (
        <TextField
            id={id}
            label={label}
            variant={variant}
            value={value}
            onChange={onChange}
            {...props} // Spread any additional props to allow further customization
        />
    );
};

export default DynamicTextField;
