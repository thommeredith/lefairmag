<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
$cjfm_envato_username = get_option('cjfm_envato_username');
$cjfm_envato_api_key = get_option('cjfm_envato_api_key');
$cjfm_envato_purchase_code = get_option('cjfm_envato_purchase_code');
$cjfm_envato_item_id = get_option('cjfm_envato_item_id');
$cjfm_last_update = get_option('cjfm_last_update');

// Download Url
$download_latest_version_url = 'http://marketplace.envato.com/api/edge/'.get_option('cjfm_envato_username').'/'.get_option('cjfm_envato_api_key').'/download-purchase:'.get_option('cjfm_envato_purchase_code').'.json';
$process_download_url = cjfm_string(cjfm_callback_url('core_upgrades')).'cjfm_action=process_download';
// Item Details Url
$item_info_url = 'http://marketplace.envato.com/api/edge/item:'.$cjfm_envato_item_id.'.json';

if(!isset($_GET['cjfm_action']) && $cjfm_envato_username != ''){
	$cjfm_check_upgrade = wp_remote_get($item_info_url);
	if(is_wp_error($cjfm_check_upgrade)){
		echo cjfm_show_message('error', $cjfm_check_upgrade->get_error_message());
	}else{
		$cjfm_check_upgrade = json_decode($cjfm_check_upgrade['body']);
		$remote_last_update_timestamp = strtotime($cjfm_check_upgrade->item->last_update);
		if($cjfm_last_update == ''){
			update_option('cjfm_last_update', time());
		}else{
			$cjfm_last_update = get_option('cjfm_last_update');
			if($remote_last_update_timestamp > $cjfm_last_update){
				echo cjfm_show_message('info', sprintf(__('<b>New version is available to download.</b> <br><a href="%s" class="bold">Click here</a> to download latest version.', 'cjfm'), $process_download_url));
			}else{
				echo cjfm_show_message('success', sprintf(__('<b>You are using the latest version.</b> <br>Last checked on %s', 'cjfm'), date('M dS, Y h:i:s A', time())));
			}
		}
	}
}


if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'process_download'){
	$download_latest_version = wp_remote_get($download_latest_version_url);
	if(is_wp_error( $download_latest_version )){
		echo cjfm_show_message('error', $download_latest_version->get_error_message());
	}else{
		$download_response = json_decode($download_latest_version['body']);
		foreach ($download_response as $key => $value) {
			if(isset($value->download_url)){
				wp_redirect( $value->download_url );
				update_option('cjfm_last_update', time());
			}
		}
	}
}



global $wpdb;
$cjfm_upgrade_errors = null;
if(isset($_POST['save_envato_info'])){
	if($_POST['cjfm_envato_username'] == ''){
		$cjfm_upgrade_errors[] = __('Envato Username is required.', 'cjfm');
	}elseif($_POST['cjfm_envato_api_key'] == ''){
		$cjfm_upgrade_errors[] = __('Envato API Key is required.', 'cjfm');
	}elseif($_POST['cjfm_envato_purchase_code'] == ''){
		$cjfm_upgrade_errors[] = __('Envato Item Purchase Code is required.', 'cjfm');
	}else{
		$url = 'http://marketplace.envato.com/api/v3/cssjockey/c2u03ax2x2iwd6hbxfxt1ixmn5sqi74w/verify-purchase:'.$_POST['cjfm_envato_purchase_code'].'.json';
		$response = wp_remote_get($url);
		if(is_wp_error($response)){
			$cjfm_upgrade_errors[] = $response->get_error_message();
		}else{
			$response = json_decode($response['body']);
			foreach ($response as $key => $value) {
				if(!isset($value->item_id)){
					$cjfm_upgrade_errors[] = __('Could not verify purchase, please try again.', 'cjfm');
				}else{
					update_option('cjfm_envato_item_id', $value->item_id);
				}
			}
		}
	}
	if(!is_null($cjfm_upgrade_errors)){
		echo cjfm_show_message('error', implode('<br>', $cjfm_upgrade_errors));
	}else{
		update_option('cjfm_envato_username', $_POST['cjfm_envato_username']);
		update_option('cjfm_envato_api_key', $_POST['cjfm_envato_api_key']);
		update_option('cjfm_envato_purchase_code', $_POST['cjfm_envato_purchase_code']);
	}
}

$cjfm_form_options['envato_api_info'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'envato_info_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s ~ Verify Purchase', 'cjfm'), cjfm_item_info('item_name')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'cjfm_envato_username',
		'label' => __('Envato Username', 'cjfm'),
		'info' => __('Specify your envato username here', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('cjfm_envato_username'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'cjfm_envato_api_key',
		'label' => __('Envato API Key', 'cjfm'),
		'info' => __('Specify your envato API Key here.<br>You can create or get your API Key from Envato Profile >> Settings >> API Keys.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('cjfm_envato_api_key'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'cjfm_envato_purchase_code',
		'label' => __('Purchase Code', 'cjfm'),
		'info' => __('Enter your item purchase code here.<br>You can download your purchase from Envato Profile >> Downloads Page', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('cjfm_envato_purchase_code'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => 'save_envato_info',
		'label' => __('Verify Purchase', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

echo '<form action="" method="post">';
cjfm_admin_form_raw($cjfm_form_options['envato_api_info']);
echo '</form>';