import { useEffect, useState } from 'react';
import { useQuery } from 'react-query';
import Talk from 'talkjs';
import AxiosClient from "../../components/utlities/AxiosClient.jsx";

const fetchCurrentUserData = async () => {
    const response = await AxiosClient.get('/get-user-data-for-chat-box');
    return response.data;
};

export const useTalkJs = (chatRoomId) => {
    const [talkSession, setTalkSession] = useState(null);
    const { data: usersData, isLoading } = useQuery('usersData', fetchCurrentUserData);

    useEffect(() => {
        if (isLoading || !usersData || usersData.length === 0) return;

        console.log("Users Data:", usersData);

        Talk.ready.then(() => {
            if (typeof usersData[0].UserID !== 'string' && typeof usersData[0].UserID !== 'number') {
                console.error('Invalid ID:', usersData[0].UserID);
                return;
            }

            const me = new Talk.User({
                id: usersData[0].UserID,
                name: usersData[0].name,
                email: usersData[0].email
            });

            if (!talkSession) {
                const session = new Talk.Session({
                    appId: "tijlQRbF",
                    me: me
                });
                setTalkSession(session);

                const conversationId = 'sample_conversation';
                const conversation = session.getOrCreateConversation(conversationId);
                conversation.setParticipant(me);

                usersData.forEach(userData => {

                    if (typeof userData.UserID !== 'string' && typeof userData.UserID !== 'number') {
                        console.error('Invalid ID:', userData.UserID);
                        return;
                    }

                    const user = new Talk.User({
                        id: userData.UserID,
                        name: userData.name,
                        email: userData.email
                    });

                    conversation.setParticipant(user);
                });

                conversation.setAttributes({
                    photoUrl: "https://talkjs.com/docs/img/ronald.jpg",
                    subject: "Live Video Chat Room",
                });
            }
        });
    }, [usersData, isLoading, talkSession, chatRoomId]);

    return { talkSession, isLoading };
};
