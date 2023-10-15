import React, { useState } from 'react';
import { makeStyles } from "@material-ui/core/styles";
import { CircularProgress } from "@mui/material";
import { toast, ToastContainer } from "react-toastify";
import Box from "@mui/material/Box";
import TextField from "@mui/material/TextField";
import Button from "@mui/material/Button";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import AxiosClient from "../../utlities/AxiosClient.jsx";

const useStyles = makeStyles(theme => ({
    spinner: {
        position: 'fixed',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
    },
}));
const UpdateEmail = () => {
    const {register, handleSubmit} = useForm();
    const navigate = useNavigate();
    const classes = useStyles();
    const [loading, setLoading] = useState(false);

    const onSubmit = (data) => {

        const formData = {
            updateEmail: data.updateEmail,
        };

        AxiosClient.post('update-email', formData)
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

                <h3 className="contact-form-title-text text-black mt-5"><u>Update Email</u></h3>

                <form onSubmit={handleSubmit(onSubmit)}>
                    <TextField
                        {...register('updateEmail')}
                        variant="standard"
                        margin="normal"
                        required
                        fullWidth
                        name="updateEmail"
                        label="Update Email"
                        type="email"
                        id="updateEmail"
                    />

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
}

export default UpdateEmail;
