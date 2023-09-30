import React, { useContext } from 'react';
import UserContext from "../UserContext.jsx";
import ApiClient from "../utlities/AxiosClient.jsx";
const ImageGrid = () => {
    const userId = useContext(UserContext);

    return(
        <>
            <h1>fij</h1>
            <h1 style={{color : "#000000"}}>{userId}</h1>
        </>
    );
}

export default ImageGrid;
