<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
#########################################################################################
# Push Notifications
#########################################################################################

$cjfm_notification_name = sha1('cjfm_notification_'.site_url());
$cjfm_notification_value = get_option($cjfm_notification_name);
$cjfm_notification_timestamp_name = sha1('cjfm_notification_'.site_url().'timestamp');
$cjfm_notification_timestamp_value = get_option($cjfm_notification_timestamp_name);

// delete_option($cjfm_notification_name);
// delete_option($cjfm_notification_timestamp_name);
// die();

add_action( 'wp_ajax_cjfm_get_notifications', 'cjfm_get_notifications' );
function cjfm_get_notifications() {

	$cjfm_notification_name = sha1('cjfm_notification_'.site_url());
	$cjfm_notification_value = get_option($cjfm_notification_name);

	$cjfm_notification_timestamp_name = sha1('cjfm_notification_'.site_url().'timestamp');
	$cjfm_notification_timestamp_value = get_option($cjfm_notification_timestamp_name);

	$now = time();
	$check = $cjfm_notification_timestamp_value['timestamp'];

	$cjfm_notification_slug = cjfm_item_info('page_slug');
	$url = 'http://api.cssjockey.com/?cj_action=get-notifications&version='.cjfm_item_info('item_version').'&slug='.$cjfm_notification_slug;
	$cjfm_notifications = wp_remote_get( $url );
	if($cjfm_notifications['body'] != 'none'){
		$notification_response = json_decode($cjfm_notifications['body']);
		if($cjfm_notification_timestamp_value['ID'] != $notification_response->ID){
			update_option($cjfm_notification_timestamp_name, array('ID' => $notification_response->ID, 'timestamp' => strtotime('+1 day'), 'closed' => 0));
			update_option($cjfm_notification_name, $notification_response);
		}
	}

	die();
}

add_action( 'wp_ajax_cjfm_close_notification', 'cjfm_close_notification' );
function cjfm_close_notification() {
	$cjfm_notification_name = sha1('cjfm_notification_'.site_url());
	$cjfm_notification_value = get_option($cjfm_notification_name);
	$cjfm_notification_timestamp_name = sha1('cjfm_notification_'.site_url().'timestamp');
	$cjfm_notification_timestamp_value = get_option($cjfm_notification_timestamp_name);
	update_option($cjfm_notification_timestamp_name, array('ID' => $_POST['id'], 'timestamp' => strtotime('+1 day'), 'closed' => 1));
	die();
}

add_action('admin_notices' , 'cjfm_show_notification');
function cjfm_show_notification(){
	$cjfm_notification_name = sha1('cjfm_notification_'.site_url());
	$cjfm_notification_value = get_option($cjfm_notification_name);
	$cjfm_notification_timestamp_name = sha1('cjfm_notification_'.site_url().'timestamp');
	$cjfm_notification_timestamp_value = get_option($cjfm_notification_timestamp_name);
	if($cjfm_notification_value && $cjfm_notification_timestamp_value['closed'] != 1){
		$display[] = '<div id="notification-'.$cjfm_notification_value->ID.'" class="updated push-notification-message">';
		$display[] = '<div class="notification-icon">';
		$display[] = '<img src="http://cssjockey.com/files/leaf-64.png" />';
		$display[] = '</div>';
		$display[] = '<div class="notification-content">';
		$display[] = '<h3 style="margin:0 0 10px 0;">'.cjfm_item_info('item_name').'</h3>';
		$display[] = '<p style="font-size:14px; margin:0 0 0 0;"><b>'.$cjfm_notification_value->title.'</b><i style="color: #999;"> ~ '.$cjfm_notification_value->dated.'</i></p>';
		$display[] = '<div style="padding-right:50px;">'.$cjfm_notification_value->content.'</div>';
		$display[] = '</div>';
		$display[] = '<a href="#notification-'.$cjfm_notification_value->ID.'" data-id="'.$cjfm_notification_value->ID.'" class="notification-close">x</a>';
		$display[] = '</div>';
		echo implode('', $display);
	}
}



function cjfm_notifications_js(){
	$cjfm_notification_name = sha1('cjfm_notification_'.site_url());
	$cjfm_notification_value = get_option($cjfm_notification_name);
	$cjfm_notification_timestamp_name = sha1('cjfm_notification_'.site_url().'timestamp');
	$cjfm_notification_timestamp_value = get_option($cjfm_notification_timestamp_name);

	if(!isset($cjfm_notification_timestamp_value['timestamp'])){
		update_option($cjfm_notification_timestamp_name, array('ID' => 0, 'timestamp' => time('+1 minute'), 'closed' => 0));
	}

	$cjfm_notification_value = get_option($cjfm_notification_name);
	$cjfm_notification_timestamp_value = get_option($cjfm_notification_timestamp_name);

	$now = time();
	$check = $cjfm_notification_timestamp_value['timestamp'];

	if($check < $now){
		wp_enqueue_script('cj-push-notifications-js', cjfm_item_path('admin_assets_url') .'/js/push-notifications.js', array('jquery'),'',true);
	}
}
add_action( 'admin_enqueue_scripts' , 'cjfm_notifications_js', 10);







