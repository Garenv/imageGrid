import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '0abd5a3530534d463458',
    cluster: 'mt1',
    encrypted: true
});
