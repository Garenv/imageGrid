import { Session } from '@talkjs/react';

function ChatSession(props) {
    // ensure that all props (and possibly children) are forwarded correctly to the Session component.
    // without this, the chat box will not appear and errors will not be displayed for some reason.
    return <Session {...props}></Session>;
}

export default ChatSession;
