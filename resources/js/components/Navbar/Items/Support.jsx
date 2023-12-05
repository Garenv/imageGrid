import React, { useState } from 'react';
import '../../../../sass/support/support.scss';
import { toast, ToastContainer } from "react-toastify";
import ApiClient from "../../utlities/AxiosClient.jsx";
import MenuItem from "@mui/material/MenuItem";
import { FormControl, InputLabel, Select } from "@mui/material";
import { makeStyles } from "@material-ui/core/styles";
import TextField from "@mui/material/TextField";
import LoadingSpinner from "../../utlities/LoadingSpinner/LoadingSpinner.jsx";
import { useSharedStyles } from "../../utlities/SharedStyles.jsx";

const useStyles = makeStyles((theme) => ({
    formControl: {
        marginBottom: theme.spacing(2),
    },

    outlined: {
        borderColor: "#000000 !important"
    },

    whiteLabel: {
        color: "#fff",
        "&.Mui-focused": {
            color: "#fff",
        }
    }

}));

const Support = () => {
    const [fileName, setFileName]                                          = useState("");
    const [fileChosen, setFileChosen]                                    = useState(false);
    const [fileContent, setFileContent]                                           = useState(null);
    const [messageText, setMessageText]                                    = useState("");
    const [subject, setSubject]                                            = useState('');
    const [isSpinning, setIsSpinning]                                    = useState(false);
    const classes                                           = useStyles();
    const sharedStyles = useSharedStyles();

    // Create a reference to the hidden file input element
    const hiddenFileInput = React.useRef(null);

    const handleClick = () => {
        // Programmatically click the hidden file input element
        // when the Button component is clicked
        hiddenFileInput.current.click();
    };

    // Call a function (passed as a prop from the parent component to handle the user-selected file
    const handleChange = (event) => {
        setFileChosen(true);
        setFileContent(event.target.files[0]);
        setFileName(event.target.files[0].name);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setIsSpinning(true);

        let data = new FormData();
        data.append('subject', subject)
        data.append('messageText', messageText);

        if(fileContent !== null) {
            data.append('file', fileContent);
        }

        ApiClient.post('/support', data)
            .then(resp => {

                if(resp.status === 200) {
                    setIsSpinning(false);

                    toast.success(resp.data.message, {
                        closeOnClick: false,
                        closeButton: false,
                        autoClose: 1400,
                    });
                }

            }).catch(error => {
            let errorMessage       = error.response.data.message;

            toast.error(errorMessage, {
                closeOnClick: false,
                closeButton: false,
                autoClose: 1400
            });
        });
    };

    return(
        <>

            {isSpinning && <LoadingSpinner/>}

            <ToastContainer
                hideProgressBar
                closeButton={false}
            />

            <div className="contact-form-title pt-5">
                <h3 className="contact-form-title-text text-black mt-5">Questions, comments and/or concerns?  Let us know.</h3>
            </div>

            <div className="background">
                <div className="container mt-5">
                    <div className="screen">
                        <div className="screen-header">
                            <div className="screen-header-left">
                                <div className="screen-header-button close"></div>
                                <div className="screen-header-button maximize"></div>
                                <div className="screen-header-button minimize"></div>
                            </div>
                            <div className="screen-header-right">
                                <div className="screen-header-ellipsis"></div>
                                <div className="screen-header-ellipsis"></div>
                                <div className="screen-header-ellipsis"></div>
                            </div>
                        </div>
                        <div className="screen-body">
                            <div className="screen-body-item">
                                <form onSubmit={handleSubmit} method="POST" encType="multipart/form-data">
                                    <div className="app-form">
                                        <FormControl fullWidth variant="outlined">
                                            <InputLabel id="demo-simple-select-outlined-label">Subject</InputLabel>
                                            <Select
                                                labelId="demo-simple-select-outlined-label"
                                                id="demo-simple-select-outlined"
                                                label="Subject"
                                                onChange={(e) => setSubject(e.target.value)}
                                                value={subject}
                                                className={classes.outlined}
                                            >
                                                <MenuItem value="Website loading slowly">Website loading slowly</MenuItem>
                                                <MenuItem value="My Prize Has Not Been Sent Yet">I haven't received my prize</MenuItem>
                                                <MenuItem value="Other">Other</MenuItem>
                                            </Select>
                                        </FormControl>
                                        <br/>
                                        <br/>

                                        <TextField
                                            fullWidth
                                            variant="outlined"
                                            label="Message Box"
                                            multiline
                                            rows={4}
                                            name="messageText"
                                            onChange={(e) => setMessageText(e.target.value)}
                                            value={messageText}
                                            className={classes.formControl}
                                        />

                                        <br/>
                                        <br/>

                                        <div className="contact-form-group">
                                            <label htmlFor="file"/>
                                            <input type="file" id="file" name="file" hidden onChange={handleChange} ref={hiddenFileInput}/>
                                            <button type="button" id="custom-button" onClick={handleClick}>Choose File</button>
                                            <span id="custom-text">{!fileChosen ? "No file chosen" : fileName}</span>
                                        </div>
                                        <div className="contact-form-group buttons">
                                            <button className="app-form-button">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Support;
