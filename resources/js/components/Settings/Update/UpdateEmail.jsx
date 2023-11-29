import React, { useState } from 'react';
import { toast, ToastContainer } from "react-toastify";
import Box from "@mui/material/Box";
import TextField from "@mui/material/TextField";
import Button from "@mui/material/Button";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import AxiosClient from "../../utlities/AxiosClient.jsx";
import LoadingSpinner from "../../utlities/LoadingSpinner/LoadingSpinner.jsx";

const UpdateEmail = () => {
    const {register, handleSubmit} = useForm();
    const navigate = useNavigate();
    const [isSpinning, setIsSpinning] = useState(false);

    const onSubmit = (data) => {
        setIsSpinning(true);

        const formData = {
            updateEmail: data.updateEmail,
        };

        AxiosClient.post('update-email', formData)
            .then(resp => {

                if(resp.status === 200) {
                    setIsSpinning(false);

                    toast.success(resp.data.message, {
                        closeOnClick: false,
                        closeButton: false,
                        autoClose: 1400,
                    });
                }

                setTimeout(() => {
                    navigate("/grid");
                }, 4000);

            }).catch(error => {

            if(error.response.data.status !== 200) {
                setIsSpinning(false);

                toast.error(error.response.data.message, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 20000
                });
            }
        });
    }

    return (
        <>
            {isSpinning && <LoadingSpinner/>}

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
