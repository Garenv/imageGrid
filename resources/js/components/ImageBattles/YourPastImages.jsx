import React from 'react';
import DynamicGrid from "../utlities/DynamicComponents/DynamicGrid.jsx";
import { useQuery } from "react-query";
import AxiosClient from "../utlities/AxiosClient.jsx";
import {toast} from "react-toastify";

async function getYourPastImages() {

    try {
        const response = await AxiosClient.get("get-your-past-images");

        return response.data;
    } catch(err) {
        return err;
    }

}

const YourPastImages = () => {

    const { isLoading, error, data } = useQuery('getYourPastImages', getYourPastImages, {
        refetchOnWindowFocus: false
    });

    if (error) {
        return toast.error("An error has occurred!", {
            closeOnClick: false,
            closeButton: false,
            autoClose: 1400
        });
    }

    return (
        <>
            <DynamicGrid data={data} isLoading={isLoading}/>
        </>
    );
}

export default YourPastImages;
