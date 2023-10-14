import React, { useState } from 'react';
import ReactDOM from "react-dom/client";
import AxiosClient from "../utlities/AxiosClient.jsx";
import { toast, ToastContainer } from "react-toastify";

const ContactUs = () => {
    const [fullName, setFullName] = useState('');
    const [email, setEmail] = useState('');
    const [messageText, setMessageText] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();

        let data = new FormData();
        data.append('fullName', fullName)
        data.append('email', email);
        data.append('messageText', messageText);

        AxiosClient.post('/submit-contact-form', data)
            .then(resp => {

                let statusMessage = resp.data.message;

                toast.success(statusMessage, {
                    closeOnClick: false,
                    closeButton: false,
                    autoClose: 1400,
                });
            }).catch(error => {

            let errorMessage       = error.response.data.message;

            toast.error(errorMessage, {
                closeOnClick: false,
                closeButton: false,
                autoClose: 1700
            });
        });
    };

    return (
        <>
            <ToastContainer
                hideProgressBar
                closeButton={false}
            />

            <div className="max-w-md mx-auto bg-white p-6 rounded-md shadow-md mt-10 md:mt-20">
                <h1 className="text-2xl font-bold mb-5 text-gray-700 text-center">Contact us for anything</h1>
                <h4 className="text-center">We'll get back to you as soon as possible</h4>
                <br/>
                <form onSubmit={handleSubmit} method="POST">

                    <div className="mb-4">
                        <label htmlFor="fullname" className="block text-sm font-semibold text-gray-600">Full Name</label>
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            className="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                            onChange={(e) => setFullName(e.target.value)}
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label htmlFor="email" className="block text-sm font-semibold text-gray-600">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            className="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                            onChange={(e) => setEmail(e.target.value)}
                            required
                        />
                    </div>

                    <div className="mb-4">
                        <label htmlFor="messageText" className="block text-sm font-semibold text-gray-600">Message</label>
                        <textarea
                            id="messageText"
                            name="messageText"
                            rows="4"
                            className="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                            onChange={(e) => setMessageText(e.target.value)}
                            required
                        >
                        </textarea>
                    </div>

                    <button type="submit" className="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">Send Message</button>
                </form>
            </div>
        </>
    );
}

const Index = ReactDOM.createRoot(document.getElementById("contact-us"));
Index.render(<ContactUs/>);
