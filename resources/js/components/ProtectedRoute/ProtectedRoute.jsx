import { Navigate, Outlet } from 'react-router-dom';
import { useState, useEffect } from 'react';
import ApiClient from "../utlities/AxiosClient.jsx";
const ProtectedRoute = () => {
    const [isAuthenticated, setIsAuthenticated] = useState(null);

    useEffect(() => {

        ApiClient.get('/check-session').then(response => {
            setIsAuthenticated(response.data.authenticated);
        });

    }, []);

    if (isAuthenticated === null) return null; // will put a spinner here in the future

    return isAuthenticated ? <Outlet/> : <Navigate to="/" />;
}

export default ProtectedRoute;
