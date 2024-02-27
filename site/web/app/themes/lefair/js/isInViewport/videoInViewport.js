jQuery(document).ready(function ($) {
    "use strict";
    
    /* activate pause for video if scrolled out of viewport */
    $(window).scroll(function() {
        $("video").each(function(){
            if ($(this).is(":in-viewport")) {
                $(this)[0].play();
            } else {
                $(this)[0].pause();
            }
        });
    });
});
