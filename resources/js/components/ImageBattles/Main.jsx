import React, { useContext, useState } from 'react';
import { useMutation, useQuery } from "react-query";
import DynamicTextField from "../utlities/DynamicComponents/DynamicTextField.jsx";
import DynamicButton from "../utlities/DynamicComponents/DynamicButton.jsx";
import LoadingSpinner from "../utlities/LoadingSpinner/LoadingSpinner.jsx";
import UserContext from "../UserContext.jsx";
import AxiosClient from "../utlities/AxiosClient.jsx";
import { Gallery } from "react-grid-gallery";

const FormExample = () => {
    const [prompt, setPrompt] = useState(null);
    const userId = useContext(UserContext).userId;
    const name = useContext(UserContext).name;

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

    const clickButton = (prompt) => {

        console.log("clicked", userId);

        generateImageMutate({
            "prompt" : prompt,
            "UserID" : userId,
            "name" : name
        });
    };

    const { isLoading: getImagesIsLoading, data: getImagesData, error: getImagesError } = useQuery('myData', () =>
        AxiosClient.get('api/get-users-image-battles-data')
            .then(resp => {
                console.log("axios", resp.data);
                return resp.data
            }).catch(err => {
                console.log(err);
        })
    );

    const handleChange = (event) => {
        setPrompt(event.target.value);
    };

    const thumbnailCaption = (item) => {
        return(
            <div>
                <h1>{item.name}</h1>
                <h1>{item.prompt}</h1>
            </div>
        );
    };

    return (
        <>
            {generateImageLoading ? <LoadingSpinner /> : generateImageError ? <div>Error: {generateImageError.message}</div>
                : (
                <div>{generateImageData && <div>Image generated successfully!</div>}</div>
            )}

            {/*{getImagesIsLoading && <div>Loading...</div>}*/}
            {/*{getImagesError && <div>Error: {getImagesError.message}</div>}*/}
            {
                getImagesData && (
                    <>
                        <Gallery images={
                            getImagesData.image_battles_data.map(item => ({
                            src: item.image_url,
                            thumbnailCaption: thumbnailCaption(item)
                        }))}/>
                    </>
                )
            }

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
