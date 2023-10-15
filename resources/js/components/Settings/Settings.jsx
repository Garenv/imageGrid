import React from 'react';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';
import Box from '@mui/material/Box';

const Settings = () => {
    const navigate = useNavigate();

    const handleUpdatePasswordClick = () => {
       navigate('/update-password');
    };

    const handleUpdateEmailClick = () => {
        navigate('/update-email');
    };

    const handleUpdateNameClick = () => {
        navigate('/update-name');
    };

    return (
        <Box
            display="flex"
            flexDirection="column"
            justifyContent="center"
            alignItems="center"
            minHeight="100vh"
        >
            <Button
                variant="contained"
                onClick={handleUpdatePasswordClick}
                sx={{
                    backgroundColor: '#000',
                    color: '#fff',
                    '&:hover': {
                        backgroundColor: '#000',
                    },
                    marginBottom: '1em',
                }}
            >
                Update Password
            </Button>

            <Button
                variant="contained"
                onClick={handleUpdateEmailClick}
                sx={{
                    backgroundColor: '#000',
                    color: '#fff',
                    '&:hover': {
                        backgroundColor: '#000',
                    },
                    marginBottom: '1em',
                }}
            >
                Update Email
            </Button>

            <Button
                variant="contained"
                onClick={handleUpdateNameClick}
                sx={{
                    backgroundColor: '#000',
                    color: '#fff',
                    '&:hover': {
                        backgroundColor: '#000',
                    }
                }}
            >
                Update Name
            </Button>
        </Box>
    );
};

export default Settings;
