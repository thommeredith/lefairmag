jQuery(document).ready(function($) {

    // Password Meter
    $('.cjfm-pw input[type="password"], .cjfm-cpw input[type="password"]').on('keyup', function() {

        // Must have capital letter, numbers and lowercase letters
        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        // Must have either capitals and lowercase letters or lowercase and numbers
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");

        var weak_string = $('span.weak_string').text();
        var medium_string = $('span.medium_string').text();
        var strong_string = $('span.strong_string').text();

        var weak = '<span class="weak">' + weak_string + '</span>';
        var medium = '<span class="medium">' + medium_string + '</span>';
        var strong = '<span class="strong">' + strong_string + '</span>';

        if ($(this).val() === '') {
            $(this).closest('div.cjfm-pw').find('.cjfm-pw-strength').html('');
        } else if (strongRegex.test($(this).val())) {
            // If reg ex matches strong password
            $(this).closest('div.cjfm-pw').find('.cjfm-pw-strength').html(strong);
        } else if (mediumRegex.test($(this).val())) {
            // If medium password matches the reg ex
            $(this).closest('div.cjfm-pw').find('.cjfm-pw-strength').html(medium);
        } else {
            // If password is ok
            $(this).closest('div.cjfm-pw').find('.cjfm-pw-strength').html(weak);
        }
        return true;
    });

    // Confirm message
    $('.confirm, .cj-confirm').click(function() {
        var confirm;
        var msg = $(this).attr('data-confirm');
        var confmsg = confirm(msg);
        if (confmsg === true) {
            return true;
        } else {
            return false;
        }
    });

});
