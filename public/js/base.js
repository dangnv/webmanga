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
function checkNightMode(isNight, appUrl) {
    console.log(isNight, appUrl);
    let url = window.location.href;
    let textBefore = '';
    if (url.includes('public/')) {
        textBefore = '/public'
    }
    if (isNight) {
        $('.container').removeClass('is_night_mode');
        window.location.href = url.replace(`${textBefore}/night-mode`, '');
    } else {
        $('.container').addClass('is_night_mode');
        window.location.href = url.replace(appUrl, appUrl + '/night-mode');
    }
}
