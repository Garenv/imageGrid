import React, { useState } from 'react';
import { toast, ToastContainer } from "react-toastify";
import Box from "@mui/material/Box";
import TextField from "@mui/material/TextField";
import Button from "@mui/material/Button";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import AxiosClient from "../../../utlities/AxiosClient.jsx";
import LoadingSpinner from "../../../utlities/LoadingSpinner/LoadingSpinner.jsx";

const UpdateName = () => {
    const {register, handleSubmit} = useForm();
    const [isSpinning, setIsSpinning] = useState(false);
    const navigate = useNavigate();

    const onSubmit = (data) => {
        setIsSpinning(true);

        const formData = {
            updateName: data.updateName
        };

        AxiosClient.post('update-name', formData)
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
                }, 2500);

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
                <h3 className="contact-form-title-text text-black mt-5"><u>Update Name</u></h3>

                <form onSubmit={handleSubmit(onSubmit)}>
                    <TextField
                        {...register('updateName')}
                        variant="standard"
                        margin="normal"
                        required
                        fullWidth
                        name="updateName"
                        label="Update Name"
                        type="text"
                        id="updateName"
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

export default UpdateName;
