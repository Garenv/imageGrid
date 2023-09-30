import React from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import ProtectedRoute from "../ProtectedRoute/ProtectedRoute.jsx";
import ImageGrid from "../ImageGrid/ImageGrid.jsx";

function AppRoutes() {
    return (
        <BrowserRouter>
            <Routes>
                <Route element={<ProtectedRoute/>}>
                    <Route path="/home" element={<ImageGrid/>} />
                </Route>
            </Routes>
        </BrowserRouter>
    );
}

export default AppRoutes;
