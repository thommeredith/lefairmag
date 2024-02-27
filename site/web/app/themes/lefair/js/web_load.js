var js = document.createElement("script");
js.type = "text/javascript";
js.src = "http://www.lefairmag.com/wp-content/themes/lefair/js/jquery.fullscreen-popup.min.js";
document.head.appendChild(js);


if (screen.width > 425) {
    //alert("yes ");
    var js = document.createElement("script");
    js.type = "text/javascript";
    js.src = "http://www.lefairmag.com/wp-content/themes/lefair/js/isInViewport/lib/isInViewport.js";
    document.head.appendChild(js);
    var js = document.createElement("script");
    js.type = "text/javascript";
    js.src = "http://www.lefairmag.com/wp-content/themes/lefair/js/isInViewport/videoInViewport.js";
    document.head.appendChild(js);
    
    ;(function ($, window, document, undefined) {
        jQuery(document).ready(function () {
            jQuery(window).scroll(function() {
                jQuery('video').each(function(){
                    if ($(this).is(":in-viewport( 400 )")) {
                        $(this)[0].play();
                    } else {
                        $(this)[0].pause();
                    }
                });
            });
        });
    })(jQuery, window, document);
}

