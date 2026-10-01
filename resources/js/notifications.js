// Import Laravel Echo and socket.io-client
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

// Initialize Laravel Echo with socket.io-client
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});