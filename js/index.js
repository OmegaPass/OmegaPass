$(document).ready(function() {
    $('.welcome-gif-wrapper').fadeIn(200);

    setTimeout(() => {
        let fadeOutTime = 200;
        $('.welcome-gif-wrapper').fadeOut(fadeOutTime);

        setTimeout(() => $('.welcome-gif-wrapper').hide(), fadeOutTime);
    }, 3510)
});
