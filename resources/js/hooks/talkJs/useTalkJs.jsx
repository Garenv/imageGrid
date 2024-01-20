import { useEffect, useState } from 'react';
import { useQuery } from 'react-query';
import Talk from 'talkjs';
import AxiosClient from "../../components/utlities/AxiosClient.jsx";

// This function fetches the current user data from your backend
const fetchCurrentUserData = async () => {
    const response = await AxiosClient.get('/get-current-user-data');
    return response.data;
};

export const useTalkJs = (chatRoomId) => {
    const [talkSession, setTalkSession] = useState(null);
    const { data: currentUserData, isLoading } = useQuery('currentUserData', fetchCurrentUserData);

    useEffect(() => {
        if (isLoading || !currentUserData || !currentUserData.id) return;

        Talk.ready.then(() => {
            const me = new Talk.User(currentUserData);

            if (!talkSession) {
                const session = new Talk.Session({
                    appId: "YOUR_APP_ID", // Replace with your actual TalkJS App ID
                    me: me
                });
                setTalkSession(session);

                // Use a fixed conversation ID for the chat room
                const conversationId = chatRoomId;
                const conversation = session.getOrCreateConversation(conversationId);
                conversation.setParticipant(me);
                // You can set conversation attributes such as photo, custom subject, etc.
                conversation.setAttributes({
                    photoUrl: "https://talkjs.com/docs/img/ronald.jpg",
                    subject: "Live Video Chat Room",
                });
            }
        });
    }, [currentUserData, isLoading, talkSession, chatRoomId]);

    return { talkSession, isLoading };
};
