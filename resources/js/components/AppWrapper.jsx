// AppWrapper.js
import React from 'react';
import { UserProvider } from './UserContext.jsx';

const AppWrapper = ({ children }) => {
    const userId = document.querySelector('meta[name="userInfo"]').getAttribute('content');

    return (
        <UserProvider value={userId}>
            {children}
        </UserProvider>
    );
}

export default AppWrapper;
