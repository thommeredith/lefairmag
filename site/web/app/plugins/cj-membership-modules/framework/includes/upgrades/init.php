<?php
add_action('init', 'cjfm_automatic_upgrades');
function cjfm_automatic_upgrades(){
	$cjfm_eon = sha1('cjfm_verify_epc'.site_url());
	$cjfm_eov = get_option($cjfm_eon);
	// Reset license
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'reset-license'){
		$url = 'http://api.cssjockey.com/?cj_action=reset_license&purchase_code='.$cjfm_eov.'&u='.site_url();
		$response = wp_remote_get($url);
		if(!is_wp_error($response)){
			if($response['body'] == 'removed'){
				delete_option($cjfm_eon);
				wp_redirect(cjfm_callback_url('core_welcome'));
				exit;
			}else{
				$location = cjfm_callback_url('core_welcome').'&cjmsg=pc-no-match';
				wp_redirect($location);
				exit;
			}
		}
	}

	if($cjfm_eov != ''){
		$item_type = cjfm_item_info('item_type');
		switch ($item_type) {
			case 'theme':
				require_once('themes/update.php');
				break;
			case 'plugin':
				require_once('plugins/update.php');
				break;
		}
	}
}

add_action('admin_notices', 'cjfm_verify_epcp');
function cjfm_verify_epcp(){
	global $wpdb, $current_user;
	$cjfm_eon = sha1('cjfm_verify_epc'.site_url());
	$cjfm_eov = get_option($cjfm_eon);
	$item_info = cjfm_item_info();

	$cssjockey_api_url = 'http://api.cssjockey.com/';

	$errors = null; $success = null; $error_msg = null; $success_msg = null;
	// Handle local install
	if(isset($_POST['cjfm_verify_purchase_code'])){
		if($_POST['cjfm_purchase_code'] == ''){
			$errors[] = __('Please enter purchase code for this product.', 'cjfm');
		}
		if(is_null($errors)){
			$pdata = array(
				'cj_action' => 'verify_envato_code',
				'purchase_code' => $_POST['cjfm_purchase_code'],
				'item_id' => $item_info['item_id'],
				'textdomain' => $item_info['page_slug'],
				'site_url' => site_url(),
			);
			$response = wp_remote_post($cssjockey_api_url , array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => $pdata,
				'cookies' => array()
			    )
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$errors[] = $error_message;
			} else {
				$result = json_decode($response['body']);
				if(isset($result->success)){
					update_option($cjfm_eon, $_POST['cjfm_purchase_code']);
					$location = cjfm_callback_url('core_welcome');
					wp_redirect( $location );
					exit;
				}
				if(isset($result->error)){
					$errors['envato'] = $result->error;
				}
			}
		}
		if(!is_null($errors)){
			$error_msg = '<p class="red">'.implode('<br>', $errors).'</p>';
		}
	}

	if(!cjfm_is_local() && cjfm_item_info('item_id') != 'NA'){
		$display[] = '<div id="verify-purchase-code" class="updated push-notification-message">';
		$display[] = '<div class="notification-icon">';
		$display[] = '<img src="http://api.cssjockey.com/files/leaf-64.png" />';
		$display[] = '</div>';
		$display[] = '<div class="notification-content">';
		$display[] = '<h3 style="margin:0 0 10px 0; line-height:1;">'.$item_info['item_name'].'</h3>';
		$display[] = $success_msg;
		$display[] = '<p>'.sprintf(__('Verify purchase code to enable automatic upgrades and use this %s on this installation.', 'cjfm'), $item_info['item_type']).'</p>';
		$display[] = '<form action="" method="post">';
		$display[] = $error_msg;
		$display[] = '<p><input name="cjfm_purchase_code" type="text" value="'.cjfm_post_default('cjfm_purchase_code', '').'" class="verify-input" /></p>';
		$display[] = '<p>';
		$display[] = '<button name="cjfm_verify_purchase_code" class="button-primary" style="margin-right:10px;" type="submit">'.__('Verify & Activate License', 'cjfm').'</button>';
		$display[] = sprintf(__('<a target="_blank" href="%s">Where can I find my Purchase Code?</a>', 'cjfm'), 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-');
		$display[] = '</p>';
		$display[] = '</form>';
		$display[] = '</div>';
		$display[] = '</div>';
	}else{
		$display[] = '';
	}

	if($cjfm_eov == ''){
		echo implode(null, $display);
	}
}
