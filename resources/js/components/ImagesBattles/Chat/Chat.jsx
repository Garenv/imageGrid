import { useCallback, useEffect, useState } from 'react';
import Talk from 'talkjs';
import { Chatbox } from '@talkjs/react';
import ChatSession from "./ChatSession.jsx";
import { IoChatboxEllipsesOutline } from "react-icons/io5";
import { IoMdClose } from "react-icons/io";
import { useSharedStyles } from "../../utlities/SharedStyles.jsx";
import AxiosClient from "../../utlities/AxiosClient.jsx";

const Chat = () => {

    const sharedStyles = useSharedStyles();
    const [isChatboxOpen, setIsChatboxOpen] = useState(false);
    const [userDataForChatBox, setUserDataForChatBox] = useState(null);
    const toggleChatbox = () => setIsChatboxOpen(!isChatboxOpen);

    useEffect(() => {
        AxiosClient.get('/get-user-data-for-chat-box')
            .then(resp => {
                console.log(resp);
                setUserDataForChatBox(resp);
            });
    }, []);

    useEffect(() => {
        AxiosClient.get('/get-user-data-for-chat-box')
            .then(resp => {
                console.log(resp);
                setUserDataForChatBox(resp);
            });
    }, []);

    const syncUser = useCallback(
        () =>
            new Talk.User({
                id: 'nina',
                name: 'Nina',
                email: 'nina@example.com',
                photoUrl: 'https://talkjs.com/new-web/avatar-7.jpg',
                welcomeMessage: 'Hi!',
                role: 'default',
            }),
        [],
   );

    const syncConversation = useCallback((session) => {
        const conversation = session.getOrCreateConversation('welcome');

        const other = new Talk.User({
            id: 'frank',
            name: 'Frank',
            email: 'frank@example.com',
            photoUrl: 'https://talkjs.com/new-web/avatar-8.jpg',
            welcomeMessage: 'Hey, how can I help?',
            role: 'default',
        });
        conversation.setParticipant(session.me);
        conversation.setParticipant(other);

        return conversation;
    }, []);

    return (
        <>
            <div style={{
                position: 'fixed',
                bottom: '10px',
                right: '10px',
                width: '60px', // width and height must be the same for a perfect circle
                height: '60px',
                backgroundColor: "#89cff0",
                borderRadius: '50%', // this will make it circular
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer',
                boxShadow: '0 2px 4px rgba(0,0,0,0.2)', // Optional: adds a shadow effect
                zIndex: 1000
            }} onClick={toggleChatbox}>
                {isChatboxOpen ? <IoMdClose size="3em"/> : <IoChatboxEllipsesOutline size="3em"/>}
            </div>

            {
                isChatboxOpen &&
                <ChatSession appId="tijlQRbF" syncUser={syncUser}>
                    <Chatbox
                        syncConversation={syncConversation}
                        style={{width: '100%', height: '500px'}}
                    ></Chatbox>
                </ChatSession>
            }

        </>
    );
}

export default Chat;
