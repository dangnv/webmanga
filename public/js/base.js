$(function () {
    if (localStorage.getItem('night-mode') && localStorage.getItem('night-mode') == 'on') {
        turnOnNight();
    } else {
        turnOffNight();
    }
})
document.addEventListener("DOMContentLoaded", function() {
    var lazyloadImages = document.querySelectorAll("img.lazy");
    var lazyloadThrottleTimeout;

    function lazyload () {
        if(lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }

        lazyloadThrottleTimeout = setTimeout(function() {
            var scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if(img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                }
            });
            if(lazyloadImages.length == 0) {
                document.removeEventListener("scroll", lazyload);
                window.removeEventListener("resize", lazyload);
                window.removeEventListener("orientationChange", lazyload);
            }
        }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
});
function checkNightMode() {
    if (localStorage.getItem('night-mode') && localStorage.getItem('night-mode') == 'on') {
        localStorage.setItem('night-mode', 'off');
        turnOffNight();
    } else {
        localStorage.setItem('night-mode', 'on');
        turnOnNight();
    }
}
function turnOnNight () {
    $('body').addClass('night-mode');
    $('.container-night-mode').addClass('is_night_mode');
    $('#menu .navbar-expand-sm').addClass('navbar-dark');
    $('#menu .navbar-expand-sm').addClass('bg-dark');
    $('.btn-light').addClass('btn-dark');
    $('.btn-dark').removeClass('btn-light');
    $('#menu .navbar-expand-sm').removeClass('navbar-light');
    $('#menu .navbar-expand-sm').removeClass('bg-light');
}
function turnOffNight () {
    $('body').removeClass('night-mode');
    $('.container-night-mode').removeClass('is_night_mode');
    $('#menu .navbar-expand-sm').removeClass('navbar-dark');
    $('#menu .navbar-expand-sm').removeClass('bg-dark');
    $('#menu .navbar-expand-sm').addClass('navbar-light');
    $('#menu .navbar-expand-sm').addClass('bg-light');
    $('.btn-dark').addClass('btn-light');
    $('.btn-light').removeClass('btn-dark');
}
