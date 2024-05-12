var pusher = new Pusher('86dfd2f47cc3ff669b3a', {
    cluster: 'eu'
});
Pusher.logToConsole = true;
var channel = pusher.subscribe('pz-channel');
channel.bind('cookie-claimed', function(data) {
    console.log(JSON.stringify(data));
    displayCountdown(data.nextDate, data.message);
});