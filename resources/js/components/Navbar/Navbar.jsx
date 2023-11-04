import { useState } from "react";
import UserContext from "../UserContext.jsx";
import {useQuery, useQueryClient} from 'react-query';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import Menu from '@mui/material/Menu';
import MenuIcon from '@mui/icons-material/Menu';
import Container from '@mui/material/Container';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import Tooltip from '@mui/material/Tooltip';
import MenuItem from '@mui/material/MenuItem';
import { useNavigate } from "react-router-dom";
import AxiosClient from "../utlities/AxiosClient.jsx";
// const pages = ['Prize Status', 'Your Prizes', `This Week's Winners`, 'Support'];
const pages = ['Past Uploads', 'Prize Status', 'Support'];
import { makeStyles } from '@material-ui/core/styles';
const settings = ['Profile', 'Account', 'Gallery', 'Logout'];

const useStyles = makeStyles((theme) => ({
    centeredText: {
        justifyContent: 'center'
    },

    noAnimation: {
        animation: 'none !important',
        transform: 'none !important',
        transition: 'none !important'
    }
}));

const Navbar = () => {
    const [anchorElNav, setAnchorElNav] = useState(null);
    const [anchorElUser, setAnchorElUser] = useState(null);
    const classes = useStyles();
    const navigate = useNavigate();

    const { data: avatarData } = useQuery('userAvatar', getAvatar, {
        refetchOnWindowFocus: false, // disable background refetching upon window focus
    });

    const avatarUrl = avatarData?.avatarImage;

    async function getAvatar() {
        const response = await AxiosClient.get('/get-avatar-image');

        if(response.status !== 200) {
            throw new Error('Network response was not ok');
        }

        return response.data;
    }

    const handleOpenNavMenu = (event) => {
        setAnchorElNav(event.currentTarget);
    };

    const handleOpenUserMenu = (event) => {
        setAnchorElUser(event.currentTarget);
    };

    const handleCloseNavMenu = () => {
        setAnchorElNav(null);
    };

    const handleCloseUserMenu = () => {
        setAnchorElUser(null);
    };

    const logout = () => {
        AxiosClient.post('/logout');
    }

    const handleSettingsClick = () => {
        navigate('/settings');
    };

    const handleProfileClick = () => {
        navigate('/profile');
    };

    const handleNavigation = () => {
        navigate('/grid');
    };

    const pageSelection = (page) => {
        switch (page) {
            case "Past Uploads":
                return "/past-uploads";
            case "Prize Status":
                return "/prize-status"
            case "Support":
                return "/support"
            default:
                return "Not Found";
        }
    };

    return (
        <AppBar position="fixed">
            <Container maxWidth="xl">
                <Toolbar disableGutters>
                    <Typography
                        variant="h6"
                        noWrap
                        component="div" // Must "div" and handling navigation with onClick
                        onClick={handleNavigation}
                        sx={{
                            mr: 2,
                            display: { xs: 'none', md: 'flex' },
                            fontFamily: 'monospace',
                            fontWeight: 700,
                            color: 'inherit',
                            textDecoration: 'none',
                            cursor: 'pointer'
                        }}
                    >
                        Phopixel
                    </Typography>

                    <Box sx={{ flexGrow: 1, display: { xs: 'flex', md: 'none' } }}>
                        <IconButton
                            size="large"
                            aria-label="account of current user"
                            aria-controls="menu-appbar"
                            aria-haspopup="true"
                            onClick={handleOpenNavMenu}
                            color="inherit"
                        >
                            <MenuIcon />
                        </IconButton>
                        <Menu
                            id="menu-appbar"
                            anchorEl={anchorElNav}
                            anchorOrigin={{
                                vertical: 'bottom',
                                horizontal: 'left',
                            }}
                            keepMounted
                            transformOrigin={{
                                vertical: 'top',
                                horizontal: 'left',
                            }}
                            open={Boolean(anchorElNav)}
                            onClose={handleCloseNavMenu}
                            sx={{
                                display: { xs: 'block', md: 'none' },
                            }}
                        >

                            {/* Mobile hamburger menu */}
                            {pages.map((page) => (
                                <MenuItem
                                    key={page}
                                    onClick={() => {
                                        handleCloseNavMenu();
                                        navigate(pageSelection(page));
                                    }}
                                >
                                    <Typography>
                                        {page}
                                    </Typography>
                                </MenuItem>
                            ))}
                        </Menu>
                    </Box>

                    <Typography
                        variant="h5"
                        noWrap
                        component="div" // Must "div" and handling navigation with onClick*/}
                        onClick={handleNavigation}
                        sx={{
                            mr: 2,
                            display: { xs: 'flex', md: 'none' },
                            flexGrow: 1,
                            fontFamily: 'monospace',
                            fontWeight: 700,
                            color: 'inherit',
                            textDecoration: 'none',
                        }}
                    >
                        Phopixel
                    </Typography>

                    {/* Desktop AppBar Pages */}
                    <Box sx={{ flexGrow: 2, display: { xs: 'none', md: 'flex' } }} className={`${classes.centeredText} ${classes.noAnimation}`}>
                        {pages.map((page) => (
                            <Button
                                key={page}
                                onClick={() => {
                                    handleCloseNavMenu();
                                    navigate(pageSelection(page));
                                }}
                                sx={{ my: 2, color: 'white', display: 'block' }}
                            >
                                {page}
                            </Button>
                        ))}
                    </Box>

                    <Box sx={{ flexGrow: 0 }}>
                        <Tooltip title="Open settings">
                            <IconButton onClick={handleOpenUserMenu} sx={{ p: 0 }}>
                                <Avatar src={avatarUrl} />
                            </IconButton>
                        </Tooltip>
                        <Menu
                            sx={{ mt: '45px' }}
                            id="menu-appbar"
                            anchorEl={anchorElUser}
                            anchorOrigin={{
                                vertical: 'top',
                                horizontal: 'right',
                            }}
                            keepMounted
                            transformOrigin={{
                                vertical: 'top',
                                horizontal: 'right',
                            }}
                            open={Boolean(anchorElUser)}
                            onClose={handleCloseUserMenu}
                        >

                        <Button variant="text" onClick={handleSettingsClick}>
                            Settings
                        </Button>

                        <br/>

                        <Button variant="text" onClick={handleProfileClick}>
                            Profile
                        </Button>

                        <br/>

                        <a href="/" className="myButton" onClick={logout}>
                            <Button onClick={logout} variant="text">
                                Logout
                            </Button>
                        </a>
                        </Menu>
                    </Box>
                </Toolbar>
            </Container>
        </AppBar>
    );
}
export default Navbar;
