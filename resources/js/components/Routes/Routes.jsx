import React from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import ProtectedRoute from "../ProtectedRoute/ProtectedRoute.jsx";
import ImageGrid from "../ImageGrid/ImageGrid.jsx";
import Support from "../Support/Support.jsx";

function AppRoutes() {
    return (
        <BrowserRouter>
            <Routes>
                <Route element={<ProtectedRoute/>}>
                    <Route path="/home" element={<ImageGrid/>} />
                    <Route path="/support" element={<Support/>} />
                </Route>
            </Routes>
        </BrowserRouter>
    );
}

export default AppRoutes;
