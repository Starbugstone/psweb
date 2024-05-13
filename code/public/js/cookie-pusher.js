var pusher = new Pusher('86dfd2f47cc3ff669b3a', {
    cluster: 'eu'
});
Pusher.logToConsole = true;
const channel = pusher.subscribe('pz-channel');
channel.bind('cookie-claimed', function(data) {
    displayCountdown(data.nextDate, data.message);
});