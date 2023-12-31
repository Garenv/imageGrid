import React from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import ProtectedRoute from "../ProtectedRoute/ProtectedRoute.jsx";
import ImageGrid from "../ImageGrid/ImageGrid.jsx";
import Support from "../Navbar/Items/Support.jsx";
import Navbar from "../Navbar/Navbar.jsx";
import Settings from "../Navbar/Settings/Settings.jsx";
import UpdatePassword from "../Navbar/Settings/Update/UpdatePassword.jsx";
import UpdateEmail from "../Navbar/Settings/Update/UpdateEmail.jsx";
import UpdateName from "../Navbar/Settings/Update/UpdateName.jsx";
import PastUploads from "../Navbar/Items/PastUploads.jsx";
import PrizeStatus from "../Navbar/Items/PrizeStatus.jsx";
import Profile from "../Profile/Profile.jsx";
import LastWeeksWinners from "../Navbar/Items/LastWeeksWinners.jsx";

function AppRoutes() {
    return (
        <BrowserRouter>
            <Navbar/>
            <Routes>
                <Route element={<ProtectedRoute/>}>
                    <Route path="/grid" element={<ImageGrid/>} />
                    <Route path="/support" element={<Support/>} />
                    <Route path="/settings" element={<Settings/>} />
                    <Route path="/update-password" element={<UpdatePassword/>} />
                    <Route path="/update-email" element={<UpdateEmail/>} />
                    <Route path="/update-name" element={<UpdateName/>} />
                    <Route path="/past-uploads" element={<PastUploads/>} />
                    <Route path="/prize-status" element={<PrizeStatus/>} />
                    <Route path="/profile" element={<Profile/>} />
                    <Route path="/last-weeks-winners" element={<LastWeeksWinners/>} />
                </Route>
            </Routes>
        </BrowserRouter>
    );
}

export default AppRoutes;
