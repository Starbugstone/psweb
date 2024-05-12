function displayCountdown(newDate, message = null) {
    const countdownElement = document.getElementById("cookieCountdown");
    if (!countdownElement) {
        return;
    }
    console.log(newDate);
    if(message) {
        console.log(message);
    }
    countdownElement.setAttribute('data-nextdate', newDate);
    handleCountdownElement();
}

function removeCountdown() {
    const countdownElement = document.getElementById("cookieCountdown");
    if (!countdownElement) {
        return;
    }
    countdownElement.innerHTML = "Ready to claim!";
}

function handleCountdownElement() {
    const countdownElement = document.getElementById("cookieCountdown");
    if (!countdownElement) {
        return;
    }

    const countdownDateRaw = countdownElement.getAttribute('data-nextdate');
    const countdownDate = new Date(countdownDateRaw).getTime();

// Update the count down every 1 second
    var x = setInterval(function () {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countdownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in an element with id="countdown"
        countdownElement.innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            removeCountdown();
        }
    }, 1000);
}
handleCountdownElement();
