import React, { useContext, useEffect, useState } from 'react';
import { useMutation } from "react-query";
import DynamicTextField from "../utlities/DynamicComponents/DynamicTextField.jsx";
import DynamicButton from "../utlities/DynamicComponents/DynamicButton.jsx";
import LoadingSpinner from "../utlities/LoadingSpinner/LoadingSpinner.jsx";
import UserContext from "../UserContext.jsx";
import AxiosClient from "../utlities/AxiosClient.jsx";
// import { Gallery } from "react-grid-gallery";
// import ImageBattlesGrid from "./ImageBattlesGrid/ImageBattlesGrid.jsx";

const FormExample = () => {
    const [prompt, setPrompt] = useState(null);
    const userId = useContext(UserContext).userId;

    // generate image
    const { mutate: generateImageMutate, isLoading: generateImageLoading, data: generateImageData, error: generateImageError } = useMutation(data => {
        return AxiosClient.post('api/generate-image', data, {
            headers: {
                "Authorization": `Bearer ${window.Laravel.apiToken}`,
                "Content-Type": "application/json"
            }
        }).then(resp => {
            console.log(resp);
        })
    });

    useEffect(() => {
        getImagesMutate();
    }, []);

    const clickButton = (prompt) => {

        console.log("clicked", userId);

        generateImageMutate({
            "prompt" : prompt,
            "UserID" : userId
        });
    };

    // get images
    const { mutate: getImagesMutate, isLoading: getImagesIsLoading, data: getImagesData, error: getImagesError } = useMutation(data => {
        return AxiosClient.get('api/get-image-battles-data')
            .then(resp => {
            console.log(resp);
        }).catch(err => {
            console.log(err);
        });
    });

    const handleChange = (event) => {
        setPrompt(event.target.value);
    };

    return (
        <>
            {generateImageLoading ? <LoadingSpinner /> : generateImageError ? <div>Error: {generateImageError.message}</div>
                : (
                <div>
                    {generateImageData && <div>Image generated successfully!</div>}
                    {/* Image battles grid will go here */}
                </div>
            )}

            <div className="p-3 bg-light fixed-bottom">
                <form noValidate autoComplete="off">
                    <DynamicTextField
                        id="my-textfield"
                        label="Enter your prompt!"
                        variant="outlined"
                        // value={myValue}
                        onChange={handleChange}
                        // You can pass additional props that TextField supports
                        fullWidth
                        margin="normal"
                    />
                </form>
                <DynamicButton
                    variant="contained"
                    onClick={() => clickButton(prompt)}
                >
                    Submit
                </DynamicButton>
            </div>
        </>
    );
};

export default FormExample;
