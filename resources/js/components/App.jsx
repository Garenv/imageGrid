import React from 'react';
import ReactDOM from 'react-dom/client';
import UserContext from "./UserContext.jsx";
import { getUserIdFromMeta } from "./utlities/UserData.jsx";
import AppRoutes from "./Routes/Routes.jsx";
function App() {
    return (
        <>
            <UserContext.Provider value={getUserIdFromMeta()}>
                <AppRoutes/>
            </UserContext.Provider>
        </>
    );
}

export default App;

if (document.getElementById('example')) {
    const Index = ReactDOM.createRoot(document.getElementById("example"));

    Index.render(<App/>)
}
