import { Chatbox } from '@talkjs/react';
import ChatSession from "./ChatSession.jsx";
import { useSharedStyles } from "../../utlities/SharedStyles.jsx";

const Chat = () => {

    const sharedStyles = useSharedStyles();

    return(
        <>
            <div>
                <ChatSession appId="tijlQRbF" userId="sample_user_alice">
                    <Chatbox
                        conversationId="sample_conversation"
                        style={{ width: '100%', height: '500px', zIndex: 10000 }}
                        className={sharedStyles.centered}
                    />
                </ChatSession>
                <h1>hey</h1>
            </div>
        </>
    );
};

export default Chat;
