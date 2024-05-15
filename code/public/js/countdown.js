function displayCountdown(newDate, message = null) {
    const countdownElement = document.getElementById("cookieCountdown");
    if (!countdownElement) {
        return;
    }
    if (message) {
        showToast(message, 'warning');
    }
    countdownElement.setAttribute('data-nextdate', newDate);
    handleCountdownElement();
}

function removeCountdown() {
    const countdownElement = document.getElementById("cookieCountdown");
    if (!countdownElement) {
        return;
    }
    countdownElement.innerHTML = "<b>Ready to claim!</b>";
}

function updateCookieJarTable(cookieJars) {
    const table = document.getElementById("cookie-jar-winners-body");
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => row.remove());

    cookieJars.forEach(cookieJar => {
        const row = document.createElement('tr');
        const winnerCell = document.createElement('td');
        winnerCell.innerText = cookieJar.lastCookieWinner;
        const itemCell = document.createElement('td');
        itemCell.innerText = cookieJar.lastCookieItem;
        row.appendChild(winnerCell);
        row.appendChild(itemCell);
        table.appendChild(row);
    });
}

function handleCountdownElement() {
    const countdownElement = document.getElementById("cookieCountdown");
    if (!countdownElement) {
        return;
    }

    const countdownDateRaw = countdownElement.getAttribute('data-nextdate');
    const countdownDate = new Date(countdownDateRaw).getTime();

// Update the count down every 1 second
    let x = setInterval(function () {

        // Get today's date and time
        const now = new Date().getTime();

        // Find the distance between now and the count down date
        const distance = countdownDate - now;

        // Time calculations for days, hours, minutes and seconds
        // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        // var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in an element with id="countdown"
        // countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
        countdownElement.innerHTML = minutes + "m " + seconds + "s ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            removeCountdown();
        }
    }, 1000);
}

handleCountdownElement();
