// BottomBar.jsx
import React, { useRef } from 'react';
import { AppBar, Toolbar, Fab, useTheme } from '@mui/material';
import AddIcon from '@mui/icons-material/Add';

const BottomBar = ({ onFileSelect }) => {
    const theme = useTheme();
    const fileInputRef = useRef();

    const handleFabClick = () => {
        fileInputRef.current.click();
    };

    return (
        <AppBar position="fixed" color="primary" sx={{ top: 'auto', bottom: 0, height: '10vh' }}>
            <Toolbar sx={{
                alignItems: 'center',
                justifyContent: 'center',
                position: 'relative',
            }}>
                {/* Hidden file input */}
                <input
                    ref={fileInputRef}
                    type="file"
                    style={{ display: 'none' }}
                    onChange={onFileSelect}
                    accept="image/*"
                />
                {/* Fab button that triggers file input */}
                <Fab
                    color="secondary"
                    aria-label="add"
                    sx={{
                        position: 'absolute',
                        top: `calc(50% - ${theme.spacing(2)})`,
                    }}
                    onClick={handleFabClick}
                >
                    <AddIcon />
                </Fab>
            </Toolbar>
        </AppBar>
    );
};

export default BottomBar;
