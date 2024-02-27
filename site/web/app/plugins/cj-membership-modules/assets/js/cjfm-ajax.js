jQuery(document).ready(function($) {

    // Ajax Validation
    var ajax_url = $('#cjfm-ajax-url').text();
    $('.cjfm-login-form form').submit(function() {
        $('.cjfm-btn.submit').attr('disabled', 'true');
        $(this).find('.cjfm-loading').html('<p class="cjfm alert alert-default"><span><i class="fa fa-spin fa-spinner"></i> Please wait..</span></p>')
        var errors = 0;
        var redirect_url = $(this).attr('data-redirect');
        $('.cjfm-login-form form').find('div.alert').remove();
        var data = {
            'action': 'cjfm_ajax_validation_login_form',
            'formdata': $(this).serialize()
        };
        $.post(ajax_url, data, function(response) {
            $('.cjfm-btn.submit').removeAttr('disabled');
            $('.cjfm-loading').html('')
            if (response != 0) {
                $('.cjfm-login-form form').prepend(response);
                return false;
            } else {
                window.location.href = redirect_url;
            }
        });
        return false;
    });


    $('.cjfm-ajax-recover-password-form form').submit(function() {
        $('.cjfm-btn.submit').attr('disabled', 'true');
        $(this).find('.cjfm-loading').html('<p class="cjfm alert alert-default"><span><i class="fa fa-spin fa-spinner"></i> Please wait..</span></p>')
        $('.cjfm-ajax-recover-password-form form').find('div.alert').remove();
        var data = {
            'action': 'cjfm_ajax_validation_reset_password_form',
            'formdata': $(this).serialize()
        };
        $.post(ajax_url, data, function(response) {
            $('.cjfm-btn.submit').removeAttr('disabled');
            $('.cjfm-loading').html('')
            $('.cjfm-ajax-recover-password-form form').prepend(response);
        });
        return false;
    });


    $('.cjfm-ajax-register-form form').submit(function() {
        $('.cjfm-btn.submit').attr('disabled', 'true');
        $(this).find('.cjfm-loading').html('<p class="cjfm alert alert-default"><span><i class="fa fa-spin fa-spinner"></i> Please wait..</span></p>')
        var redirect_url = $(this).attr('data-redirect');
        $('.cjfm-ajax-register-form form').find('div.alert').remove();
        var data = {
            'action': 'cjfm_ajax_validation_register_form',
            'formdata': $(this).serialize()
        };
        $.post(ajax_url, data, function(response) {
            $('.cjfm-btn.submit').removeAttr('disabled');
            $('.cjfm-loading').html('')
            if (response != 0) {
                $('.cjfm-ajax-register-form form').prepend(response);
                return false;
            } else {
                window.location.href = redirect_url;
            }
        });
        return false;
    });

});