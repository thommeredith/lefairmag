var helper = {
    langs : [],
    values : [],
    command : {},
    setLang : function (key, value) {
        this.langs[key] = value;
    },
    getLang: function (key) {
        if (this.values[key] != 'undefined') {
            return this.langs[key];
        }
        return '';
    },
    setValue : function (key, value) {
        this.values[key] = value;
    },
    getValue : function (key) {
        if (this.values[key] != 'undefined') {
            return this.values[key];
        }
        return null;
    }, 

    setCommand : function(value, is_work) {
        if (is_work == 'undefined') {
            is_work = 1;
        }
        this.command[value] = is_work;
    },

    getCommand : function(value, is_work) {

        if (is_work == 'undefined') {
            is_work = 1;
        }

        if (this.command[value] != 'undefined') {
            if (this.command[value] == is_work) {
                return this.command[value];
            }
        }
        return null;
    }

}
var show = '';
function shows_form(id, icon)
{
    disp = jQuery(id).css('display');
    if (disp == 'block') {
        jQuery(id).hide('slow');
        if (icon != 'undefined' && jQuery(icon).length > 0) {
            jQuery(icon).removeClass( "dashicons-arrow-up" ).addClass( "dashicons-arrow-down" );
        }
    } else {
        jQuery(id).show('slow');
        if (icon != 'undefined' && jQuery(icon).length > 0) {
            jQuery(icon).removeClass( "dashicons-arrow-down" ).addClass( "dashicons-arrow-up" );
        }
    }                
}

function showModal(id, from_params, to_params)
{
    jQuery('#' + id).arcticmodal({
        beforeOpen: function(data, el) {
            if (from_params != undefined) {
                if ( typeof(from_params) == "string") {
                    jQuery(from_params).html(to_params);
                } else {
                    for( i = 0; i < from_params.length; i++) {
                        jQuery(from_params[i]).html(to_params[i]);
                    }
                }
            }
            jQuery('#' + id).css('display','block');
        },
        afterClose: function(data, el) {
            jQuery('#' + id).css('display','none');
        }
    });
}
function sendMessageSupport(button)
{

    if(jQuery('#message').val().trim() == '') {
        alert('Please, describe your suggestion or issue and then click "Send" button.');
        jQuery('#message').focus();
        return;
    }

    var data = {};
    data['action'] = 'cl_support';
    data['nonce_support'] = cl_plugin.nonce_support;
    data['message'] = jQuery('#message').val();
    jQuery('#loading-field').css('display', 'table-row');
    jQuery('#message-result').css('display', 'none');
    jQuery('#button-ok').css('display', 'none');
    jQuery('#button-sent').css('display', 'none');
    jQuery('#message-field').css('display', 'none');
    jQuery.ajax({
        url: ajaxurl,
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(data_res) {
            jQuery('#message-result').css('display', 'table-row');
            jQuery('#button-ok').css('display', 'table-row');
            jQuery('#button-sent').css('display', 'none');
            jQuery('#loading-field').css('display', 'none');
            jQuery('#message-field').css('display', 'none');
            td = jQuery('#message-result').find('td');
            jQuery(td).css('color', '#624444');
            jQuery(td).html(data_res.msg);
            if (data_res.error) {
                jQuery(td).css('color', 'red');
            } else {
                jQuery(td).css('color', 'green');
            }
        }, 
    }); 
}
function closeSupport()
{
    jQuery.arcticmodal('close');
    jQuery('#message-result').css('display', 'none');
    jQuery('#button-ok').css('display', 'none');
    jQuery('#message-field').css('display', 'table-row');
    jQuery('#button-sent').css('display', 'table-row');
}
function submitFilter(cat_id)
{
    document.form_field.cat_id.value = cat_id;
    document.form_field.submit();
}
function setRule()
{
    if ( jQuery('#rule_on').prop( 'checked' ) ) {
        document.form_field.rules.value = 1;
        document.form_field.submit(); 
    }
    jQuery('#info-rules').arcticmodal('close');
}


function displayProInfo()
{

    jQuery('.hide-pro').each(function() {
        if (jQuery(this).css('display') == 'none') {
            jQuery(this).show('slow');
            jQuery('.icon-more-pro').css('display', 'block');
            jQuery('.title-more-pro').html('Hide <span class="icon-more-pro dashicons dashicons-arrow-up"></span>');
        } else {
            jQuery(this).hide('slow');
            jQuery('.title-more-pro').html('Show more...');
            jQuery('.icon-more-pro').css('display', 'none');
        }
    });
}


function stopShows()
{
    jQuery('#task_stop').prop('disabled', true);
    jQuery('#task_start').prop('disabled', false);
    jQuery('#task_clear').prop('disabled', false);
    jQuery('.stop-button-task').hide(500);
    jQuery('.task-title').find('.title-delete').hide(400);
    jQuery('.task-title').removeAttr('style');
    jQuery('.task-title').removeClass('animate');
    jQuery('.task-description').hide('slow');
    jQuery('.log-title').hide(500);
}

function startShows()
{
    jQuery('#task_stop').prop('disabled', false);
    jQuery('#task_clear').prop('disabled', true);
    jQuery('#task_start').prop('disabled', true);
    jQuery('.task-title').attr('style', 'text-transform:uppercase;font-weight: 600; color: green;');
    jQuery('.task-title').find('.title-delete').show(400);
    jQuery('.stop-button-task').show(500);

    jQuery('.log-title').show(500);
}   

function processLinks(command)
{
    if (command == 'start') {
        startShows();
    } else if (command == 'stop') {
        stopShows();
        cl_stop_log = 1;
    }

    jQuery('.task-title').removeClass('animate');

    jQuery.ajax({
        type: "POST",
        url: cl_plugin.ajaxurl,
        data: {action : 'processing', command : command},
        success: function(response){
            if (command == 'clear') {
                location.reload();
            } else {
                jQuery('.task-description').hide('slow');
                startCron();
                getLog();
            }
        }
    });
}
var cl_stop_log = 0;
function getLog(show)
{
    var data_send = {action : 'cl_log'};
    if (typeof(show) != 'undefined') {
        data_send['log_show'] = show;
    }
    jQuery('.task-bar .logs').show(500);
    jQuery.ajax({
        type: "POST",
        url: cl_plugin.ajaxurl,
        data: data_send,
        success: function(response){
            eval('var p=' + response);
            if (p.log && p.log.length > 0) {
                for(i = 0; i < p.log.length; i++) {
                    jQuery('.log-content-box').prepend('<div>' + p.log[i] + '</div>');
                }
            }
            if ( !is_wp_cron && wp_cron_time != 0 ) {
                setTimeout('startCron()', ( wp_cron_time * 1000 ) );
            }
            if (p.operation) {
                setOperation(p.operation);
            }
            if (p.stop) {
                cl_stop_log = 1;
                stopShows();
            } else {
                if (cl_stop_log == 0) {
                    setTimeout('getLog()', 20000);
                }
            }
        }
    });
}

function startCron()
{
    jQuery.ajax({
        type: "POST",
        url: cl_plugin.ajaxurl,
        data: {action : 'cl_init'},
        success: function(response){

        }
    });
}

function goToByScroll(id)
{
    // Scroll
    $('html,body').animate({
        scrollTop: $("#"+id).offset().top},
    1500);
}

function setOperation(data)
{
    for(i in data) {
        if (data[i]['all']) {
            if (data[i]['all'] > 0) {
                jQuery('#' + i + '-process').css('display', 'block');
                jQuery('.stats-' + i ).css('display', 'inline');
            }
            jQuery('#all_' + i).html(data[i]['all']);
        }
        if (data[i]['count']) { 
            jQuery('#count_' + i).html(data[i]['count']);
        }
        if (data[i]['procent']) { 
            jQuery('#procent_' + i).html(data[i]['procent'] + '%');
        }
        jQuery('.procent-' + i).css('width', data[i]['procent'] + '%');

    }
}
