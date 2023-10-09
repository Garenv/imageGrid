import React from 'react';
import ReactDOM from 'react-dom/client';
import UserContext from "./UserContext.jsx";
import { getUserIdFromMeta } from "./utlities/UserData.jsx";
import AppRoutes from "./Routes/Routes.jsx";
import { QueryClient, QueryClientProvider } from 'react-query';

const queryClient = new QueryClient();

function App() {
    return (
        <>
            <UserContext.Provider value={getUserIdFromMeta()}>
                <QueryClientProvider client={queryClient}>
                    <AppRoutes/>
                </QueryClientProvider>
            </UserContext.Provider>
        </>
    );
}

export default App;

if (document.getElementById('example')) {
    const Index = ReactDOM.createRoot(document.getElementById("example"));
    Index.render(<App/>)
}
