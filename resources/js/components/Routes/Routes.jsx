import React from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import ProtectedRoute from "../ProtectedRoute/ProtectedRoute.jsx";
import ImageGrid from "../ImageGrid/ImageGrid.jsx";
import Support from "../Support/Support.jsx";
import Navbar from "../Navbar/Navbar.jsx";
import Settings from "../Settings/Settings.jsx";
import UpdatePassword from "../Settings/Update/UpdatePassword.jsx";
import UpdateEmail from "../Settings/Update/UpdateEmail.jsx";
import UpdateName from "../Settings/Update/UpdateName.jsx";

function AppRoutes() {
    return (
        <BrowserRouter>
            <Navbar/>
            <Routes>
                <Route element={<ProtectedRoute/>}>
                    <Route path="/home" element={<ImageGrid/>} />
                    <Route path="/support" element={<Support/>} />
                    <Route path="/settings" element={<Settings/>} />
                    <Route path="/update-password" element={<UpdatePassword/>} />
                    <Route path="/update-email" element={<UpdateEmail/>} />
                    <Route path="/update-name" element={<UpdateName/>} />
                </Route>
            </Routes>
        </BrowserRouter>
    );
}

export default AppRoutes;
