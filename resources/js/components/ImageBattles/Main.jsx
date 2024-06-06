import React, { useContext, useEffect, useState } from 'react';
import { useMutation, useQuery, useQueryClient } from "react-query";
import { toast, ToastContainer } from "react-toastify";
import { useSharedStyles } from "../utlities/SharedStyles.jsx";
import DynamicTextField from "../utlities/DynamicComponents/DynamicTextField.jsx";
import DynamicButton from "../utlities/DynamicComponents/DynamicButton.jsx";
import LoadingSpinner from "../utlities/LoadingSpinner/LoadingSpinner.jsx";
import '../../components/utlities/RealTime/Echo.jsx';
import '../../../sass/imageBattles/imageBattles.scss';
import UserContext from "../UserContext.jsx";
import AxiosClient from "../utlities/AxiosClient.jsx";
import Button from "@mui/material/Button";
import DoneOutlineIcon from '@mui/icons-material/DoneOutline';
import Box from '@mui/material/Box';
import Typography from '@mui/material/Typography';
import Modal from '@mui/material/Modal';
import SouthWestIcon from '@mui/icons-material/SouthWest';
import EditNoteIcon from '@mui/icons-material/EditNote';

const onBoardingStyles = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 400,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4
};

const FormExample = () => {
    const maxPromptLength = 312;
    const [maxPromptLengthError, setMaxPromptLengthError] = useState(false);
    const [prompt, setPrompt] = useState('');
    const [flash, setFlash] = useState(false);
    const [open, setOpen] = useState(false);
    const [minimizePromptField, setMinimizePromptField] = useState(false);
    const queryClient = useQueryClient();
    const handleClose = () => setOpen(false);
    const userId = useContext(UserContext).userId;
    const name = useContext(UserContext).name;
    const sharedClasses = useSharedStyles();

    // generate image
    const { mutate: generateImageMutate, isLoading: generateImageLoading, data: generateImageData, error: generateImageError } = useMutation(data => {

        return AxiosClient.post('api/generate-image', data, {
            headers: {
                "Authorization": `Bearer ${window.Laravel.apiToken}`,
                "Content-Type": "application/json"
            }
        }).then(resp => {

            if(resp.status === 200) {
                toast.success(resp.data.message, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1100
                });
            }

        }).catch(err => {

            if(err.response.status !== 200) {
                toast.error(err.response.data.message, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1400
                });
            }
        });
    });

    const { data: getImagesData} = useQuery('myData', () =>
        AxiosClient.get('get-all-users-image-battles-data')
            .then(resp => {
                return resp.data;
            }).catch(err => {
                console.log(err);
        }),
        {
            refetchOnWindowFocus: false // ensures that data doesn't automatically refetch every time you switch back to the tab, thus preventing unnecessary API calls
        }
    );

    useEffect(() => {

        const hasModalBeenShown = localStorage.getItem('modalShown');

        if (!hasModalBeenShown) {
            setOpen(true);
            localStorage.setItem('modalShown', 'true');
        }

    }, []);

    useEffect(() => {
        // not to be used in prod
        // Pusher.logToConsole = true;

        const subscribeToChannel = async () => {
            const channel = window.Echo.channel('image-battles');

            await channel.listen('.image-battles-asset-generated', () => {
                queryClient.invalidateQueries('myData');
            });
        }

        subscribeToChannel();

    }, [queryClient]);

    const handleChange = (event) => {
        const val = event.target.value;

        if(val.length >= maxPromptLength) {
            setMaxPromptLengthError(true);
        } else {
            setPrompt(val);
            setMaxPromptLengthError(false);
        }

    };

    const generateImageButton = (prompt) => {

        generateImageMutate({
            "prompt" : prompt,
            "UserID" : userId,
            "name" : name
        });

    };

    const clickUpVoteButton = (item) => {

        const data = {
            'UserID' : item.UserID,
            'asset_id' : item.asset_id
        };

        AxiosClient.post('up-vote', data)
            .then(resp => {

                if(resp.status === 200) {
                    setFlash(true);

                    // automatically update the UI
                    // upon the use upvoting an asset
                    // simple and clean like Kingdom Hearts
                    queryClient.invalidateQueries('myData');

                    toast.success(resp.data.message, {
                        closeOnClick: false,
                        closeButton: false,
                        autoClose: 1100
                    });
                }

            }).catch(err => {

                if(err.response.status !== 200) {
                    toast.error(err.response.data.message, {
                        closeOnClick: false,
                        closeButton: false,
                        autoClose: 1400
                    });
                }
        });
    };

    const minimizeField = () => {
        setMinimizePromptField(!minimizePromptField);
    }

    const handleKeyPress = (e) => {
        if(e.key === "Enter") {
            e.preventDefault();
            generateImageButton(prompt);
        }
    }

    return (
        <>
            <ToastContainer
                hideProgressBar
                closeButton={false}
            />

            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="modal-modal-title"
                aria-describedby="modal-modal-description"
            >
                <Box sx={onBoardingStyles}>
                    <Typography id="modal-modal-title" variant="h6" component="h2">
                        Welcome to Image Battles!
                    </Typography>
                    <Typography id="modal-modal-description" sx={{mt: 2}}>
                        <ul>
                            <li><Typography>Enter a prompt</Typography></li>
                            <li><Typography>Image gets generated</Typography></li>
                            <li><Typography>Wait 24 hours to see if you won</Typography></li>
                            <li><Typography>If you win, you'll receive an email stating so</Typography></li>
                        </ul>
                        <h1>Good luck! 👍</h1>
                    </Typography>
                    <div className="">
                        <Button onClick={handleClose} style={{marginTop: '20px', textAlign: 'center'}}>
                            Close
                        </Button>
                    </div>
                </Box>
            </Modal>

            { generateImageLoading ? <LoadingSpinner/> : generateImageError ?
                <div>Error: {generateImageError.message}</div> : null }
            {
                Array.isArray(getImagesData) && getImagesData.length > 0 ? (
                    getImagesData.map((item) => (
                        <>
                            <div className="card-container image-battles-grid-container">
                                <div className="card">
                                    <div className="container-fluid">
                                        <img src={item.image_url} className="img-fluid" alt="User Images"/>
                                    </div>
                                    <div className="card-content">
                                        <h1 className="newColor">{item.name}</h1>
                                        <h1><u>prompt</u> - {item.prompt}</h1>
                                        {!item.upvoted ? (
                                            <Button variant="contained" onClick={() => clickUpVoteButton(item)}
                                                    color="success">UpVote</Button>
                                        ) : (
                                            <DoneOutlineIcon color="success" className={flash ? 'flashEffect' : ''}/>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </>
                    ))
                ) : (
                    <h1 className={sharedClasses.centered}>No images, yet. Be the first to generate an image!</h1>
                )
            }

            {
                !minimizePromptField ?
                    <div className={`p-3 bg-dark-subtle fixed-bottom ${minimizePromptField ? `d-none` : `display-block`}`}>
                        <div className="minimize-icon">
                            <SouthWestIcon onClick={() => minimizeField()}/>
                        </div>
                        <form noValidate autoComplete="off">
                            <DynamicTextField
                                id="my-textfield"
                                label="Enter your prompt!"
                                variant="outlined"
                                onChange={handleChange}
                                fullWidth
                                margin="normal"
                                onKeyPress={handleKeyPress}
                                inputProps={{
                                    maxLength: 312,
                                }}
                                error={maxPromptLengthError}
                                helperText={maxPromptLengthError ? `Maximum ${maxPromptLength} characters allowed.` : `${maxPromptLength - prompt.length} characters left`}
                            />
                        </form>
                        <DynamicButton
                            variant="contained"
                            type="button"
                            onClick={() => generateImageButton(prompt)}
                        >
                            Submit
                        </DynamicButton>
                    </div>
                    :
                <EditNoteIcon onClick={() => minimizeField()} />
            }
        </>
    );
};

export default FormExample;
