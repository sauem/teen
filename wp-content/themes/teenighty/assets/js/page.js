function getTimeRemaining(endtime) {
    let t = Date.parse(endtime) - Date.parse(new Date());
    let seconds = Math.floor((t / 1000) % 60);
    let minutes = Math.floor((t / 1000 / 60) % 60);
    let hours = Math.floor((t / (1000 * 60 * 60)) % 24);
    return {
        'total': t,
        'hours': hours,
        'minutes': minutes,
        'seconds': seconds
    };
}

function initializeClock(id, endtime) {

    let clock = $("#" + id);
    let hoursSpan = clock.find('.hours');
    let minutesSpan = clock.find('.minutes');
    let secondsSpan = clock.find('.seconds');

    function updateClock() {
        let t = getTimeRemaining(endtime);
        hoursSpan.text(t.hours + " : ").slice(-2);
        minutesSpan.text(t.minutes + " : ").slice(-2);
        secondsSpan.text(t.seconds).slice(-2);
        if (t.total <= 0) {
            clearInterval(timeinterval);
        }
    }

    updateClock();
    let timeinterval = setInterval(updateClock, 1000);
}
