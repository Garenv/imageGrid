import React, { useState } from 'react';
import TextField from '@mui/material/TextField';
import Button from '@mui/material/Button';
import Box from '@mui/material/Box';
import AxiosClient from "../../utlities/AxiosClient.jsx";
import { useForm } from "react-hook-form";
import { toast, ToastContainer } from "react-toastify";
import { makeStyles } from '@material-ui/core/styles';
import { CircularProgress } from "@mui/material";
import { useNavigate } from "react-router-dom";

const useStyles = makeStyles(theme => ({
    spinner: {
        position: 'fixed',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
    },
}));

const UpdatePassword = () => {
    const { register, handleSubmit} = useForm();
    const classes = useStyles();
    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();

    const onSubmit = (data) => {

        const formData = {
            currentPassword: data.currentPassword,
            newPassword: data.newPassword
        };

        AxiosClient.post('update-password', formData)
            .then(resp => {
                console.log(resp);

                toast.success(resp.data.message, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1400,
                });

                setTimeout(() => {
                    navigate("/home");
                }, 4000);

                setLoading(true);

            }).catch(error => {

            setLoading(false);

            toast.error(error.response.data.message, {
                closeOnClick: false,
                closeButton: false,
                autoClose: 5000
            });
        });
    }

    return (
        <>
            {loading && <CircularProgress className={classes.spinner}/>}

            <ToastContainer
                hideProgressBar
                closeButton={false}
            />

            <Box
                display="flex"
                flexDirection="column"
                justifyContent="center"
                alignItems="center"
                minHeight="100vh"
            >
                <h3 className="contact-form-title-text text-black mt-5"><u>Update Password</u></h3>

                <form onSubmit={handleSubmit(onSubmit)}>
                    <div style={{ width: '200px' }}>

                        <TextField
                            {...register('currentPassword')}
                            variant="standard"
                            margin="normal"
                            required
                            fullWidth
                            id="currentPassword"
                            label="Current Password"
                            name="currentPassword"
                            type="password"
                            autoFocus
                        />
                    </div>

                    <div style={{ width: '200px' }}>
                        <TextField
                            {...register('newPassword')}
                            variant="standard"
                            margin="normal"
                            required
                            fullWidth
                            name="newPassword"
                            label="New Password"
                            type="password"
                            id="newPassword"
                        />
                    </div>

                    <Button
                        type="submit"
                        fullWidth
                        variant="contained"
                        color="primary"
                        sx={{mt: 3, mb: 2}}
                    >
                        Submit
                    </Button>
                </form>
            </Box>
        </>
    );
};

export default UpdatePassword;
