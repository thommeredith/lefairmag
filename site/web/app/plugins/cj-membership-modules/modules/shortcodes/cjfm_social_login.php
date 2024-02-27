<?php
function cjfm_social_login( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array(
		'return' => null,
		'service' => 'Facebook',
		'form_id' => 1,
		'redirect_url' => site_url(),
		'button_text' => null,
		'button_class' => null,
		'button_icon' => null,
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';

	$custom_forms_query = $wpdb->get_results("SELECT * FROM $forms_table ORDER BY id ASC");
	foreach ($custom_forms_query as $key => $form) {
		$custom_forms_array[$form->id] = $form->form_name;
	}

	$social_providers = cjfm_social_login_providers();
	$services_array['all'] = __('All Services', 'cjfm');
	foreach ($social_providers as $key => $sp) {
		if(cjfm_get_option($sp.'_appID') != '' && cjfm_get_option($sp.'_appSecret') != ''){
			$services_array[$sp] = $sp;
		}
	}

	$yes_no_array = array(
		'yes' => __('Yes', 'cjfm'),
		'no' => __('No', 'cjfm'),
	);

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This will enable social login for your website.', 'cjfm'),
		'service' => array(__('Select Social Service', 'cjfm'), 'dropdown', $services_array, __('Select Social Web Service<br>Buttons will only display if you have specified app settings under Social Media Setup page.', 'cjfm')),
		'form_id' => array(__('Select form to include fields', 'cjfm'), 'dropdown', $custom_forms_array, __('Form fields will be included as per the custom form specified.', 'cjfm')),
		'redirect_url' => array(__('Redirect Url', 'cjfm'), 'text', null, __('You can specify a custom Url to redirect user after social connect. (default: homepage)', 'cjfm')),
		//'button_icon' => array(__('Show Icon?', 'cjfm'), 'dropdown', $yes_no_array, __('You can enable or disable service icon on button.', 'cjfm')),
		'button_text' => array(__('Button Text', 'cjfm'), 'text', null, __('This will replace the button text.<br>If your theme support fontawesome or bootstrap you can add HTML to include the icon and use alignleft or alignright class to float the icon.', 'cjfm')),
		'button_class' => array(__('Button CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the submit button.', 'cjfm')),
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	$display[] = '';

	if(is_null($button_class)){
		$btn_color = cjfm_get_option('button_color');
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'cjfm-social-btn cjfm-social-btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$errors = null;
	if($service == 'all'){
		foreach ($services_array as $key => $service) {
			if($key != 'all'){
				$appid = cjfm_get_option(strtolower($service).'_appID');
				$appsecret = cjfm_get_option(strtolower($service).'_appSecret');
				$button_class .= ' '.strtolower($service);
				if($appid != '' && $appsecret != ''){
					$button_text = cjfm_get_option($service.'_text');
					$display[] = '<a href="'.cjfm_string(cjfm_current_url('only')).'cjfm-social-login='.$service.'&fid='.$form_id.'&redirect='.urlencode($redirect_url).'" class="'.$button_class.'">'.$button_text.'</a>';
				}
			}
		}
	}else{
		$appid = cjfm_get_option(strtolower($service).'_appID');
		$appsecret = cjfm_get_option(strtolower($service).'_appSecret');
		$button_class .= ' '.strtolower($service);
		if($appid != '' && $appsecret != ''){
			$display[] = '<a href="'.cjfm_string(cjfm_current_url('only')).'cjfm-social-login='.$service.'&fid='.$form_id.'&redirect='.urlencode($redirect_url).'" class="'.$button_class.'">'.$button_text.'</a>';
		}
	}

	if($return == null){
		if(is_null($errors)){
			return implode('', $display);
		}else{
			return implode('', $errors);
		}
	}else{
	    return serialize($options);
	}
}
add_shortcode( 'cjfm_social_login', 'cjfm_social_login' );


function cjfm_process_social_connect(){
	global $wpdb, $current_user;

	if(isset($_GET['cjfm-social-login']) && $_GET['cjfm-social-login'] != '' && isset($_GET['fid'])){

		if(!session_id()) {
		    session_start();
		}

		$service = $_GET['cjfm-social-login'];
		$scope = ($service == 'Facebook') ? 'email, user_about_me' : '';
		$form_id = $_GET['fid'];
		$appid = cjfm_get_option(strtolower($service).'_appID');
		$appsecret = cjfm_get_option(strtolower($service).'_appSecret');
		$redirect = (isset($_GET['redirect'])) ? urldecode($_GET['redirect']) : site_url();
		$debug = false;
		$error_msg = '';
		$config = array(
			"base_url" => sprintf('%s/shortcodes/hybridauth/hybridauth/', cjfm_item_path('modules_url')),
			"providers" => array (
				$service => array (
					"enabled" => true,
					"keys"    => array ( "key" => $appid, "id" => $appid, "secret" => $appsecret ),
					"scope"   => $scope, // optional
				)
			),
			"debug_mode" => $debug,
			"debug_file" => ABSPATH.'/cjfm-hybridauth-debug.txt',
		);

		// Initialize HybridAuth
		ob_start();
		require_once( "hybridauth/hybridauth/Hybrid/Auth.php" );

		require_once( "hybridauth/hybridauth/Hybrid/Endpoint.php" );
		if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])){
		    Hybrid_Endpoint::process();
		}

		$user_info = null;
		$social_profile = null;

		try{
			$hybridauth = new Hybrid_Auth( $config );
			if(!isset($_GET['process'])){
				$hybridauth->logoutAllProviders();
				$hybridauth_process_url = cjfm_string(cjfm_current_url('only')).'cjfm-social-login='.$service.'&fid='.$form_id.'&process=1&redirect='.$redirect;
				$adapter = $hybridauth->authenticate( $service , array('hauth_return_to' => $hybridauth_process_url));
				# then try to grab the user profile
				$social_profile = $adapter->getUserProfile();
			}
			if(isset($_GET['process']) && $_GET['process'] == '1'){
				$hybridauth_process_url = cjfm_string(cjfm_current_url('only')).'cjfm-social-login='.$service.'&fid='.$form_id.'&process=1';
				$adapter = $hybridauth->authenticate( $service , array('hauth_return_to' => $hybridauth_process_url));
				# then try to grab the user profile
				$social_profile = $adapter->getUserProfile();
				cjfm_social_login_process($social_profile, $form_id, $redirect);
			}
		}
		catch( Exception $e ){
			$hybridauth->logoutAllProviders();
			switch( $e->getCode() ){
				case 0 : $error_msg = __('Unspecified error.', 'cjfm'); break;
				case 1 : $error_msg = __('Hybriauth configuration error.', 'cjfm'); break;
				case 2 : $error_msg = __('Provider not properly configured.', 'cjfm'); break;
				case 3 : $error_msg = __('Unknown or disabled provider.', 'cjfm'); break;
				case 4 : $error_msg = __('Missing provider application credentials.', 'cjfm'); break;
				case 5 : $error_msg = __('Authentification failed. The user has canceled the authentication or the provider refused the connection.', 'cjfm'); break;
				case 6 : $error_msg = __('User profile request failed. Most likely the user is not connected to the provider and he should authenticate again.', 'cjfm'); break;
				case 7 : $error_msg = __('User not connected to the provider.', 'cjfm'); break;
				case 8 : $error_msg = __('Provider does not support this feature.', 'cjfm'); break;
				default : $error_msg = __('Something went wrong, please try again.', 'cjfm'); break;
			}
			$display[] = '<script type="text/javascript">';
			$display[] = 'alert("'.$error_msg.'");';
			$display[] = 'window.location = "'.cjfm_string(site_url()).'cjfm-social-login=error'.'"';
			$display[] = '</script>';
			echo implode(null, $display);
		}
	}
}
add_action('init', 'cjfm_process_social_connect');


// helpers
function cjfm_generate_unique_username($string, $separator = '', $first = 1){
    $user_login = explode('@', $string);
    $user_login = $user_login[0];
    if(!username_exists($user_login)){
        return strtolower($user_login);
    }else{
        preg_match('/(.+)'.$separator.'([0-9]+)$/', $user_login, $match);
        return strtolower(isset($match[2]) ? $match[1].$separator.($match[2] + 1) : $user_login.$separator.$first);
    }
}

function cjfm_generate_dummy_email($string, $separator = '', $first = 1){
    $user_email = $string.'@no-email.com';
    if(!email_exists($user_email)){
        return strtolower($user_email);
    }else{
        preg_match('/(.+)'.$separator.'([0-9]+)$/', $user_email, $match);
        return strtolower(isset($match[2]) ? $match[1].$separator.($match[2] + 1) : $user_email.$separator.$first);
    }
}

function cjfm_social_login_process($social_profile, $form_id, $redirect){
	global $wpdb, $current_user;
	$user_info = null;
	$service = $_GET['cjfm-social-login'];
	$form_id = $_GET['fid'];

	if(is_user_logged_in()){
		cjfm_social_update_existing_user($social_profile, $form_id, $redirect);
	}

	if(!is_user_logged_in()){
		// Check if user account exists via identifier
		$user_social_meta_key = 'cjfm_'.$service.'_id';
		$check_identifier = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE meta_value = '{$social_profile->identifier}'");
		if(!is_null($check_identifier)){
			$user_info = cjfm_user_info($check_identifier->user_id);
		}else{
			$user_social_meta_key = 'cjfm_suid';
			$check_identifier = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE meta_key = '{$user_social_meta_key}' AND meta_value = '{$social_profile->identifier}'");
			if(!is_null($check_identifier)){
				$user_info = cjfm_user_info($check_identifier->user_id);
			}
		}
		// Check if user account exists via user email
		if($social_profile->email != '' && email_exists( $social_profile->email ) && is_null($check_identifier)){
			$user_info = cjfm_user_info($social_profile->email);
		}
	}

	if(is_null($user_info)){
		// Create new user
		cjfm_social_register_user($social_profile, $form_id, $redirect);
	}else{
		// Login and Update Existing User;
		cjfm_social_login_user($social_profile, $user_info, $form_id, $redirect);
	}
}


function cjfm_social_update_existing_user($social_profile, $form_id, $redirect){
	global $current_user, $wpdb;
	$service = $_GET['cjfm-social-login'];
	$form_id = $_GET['fid'];

	$user_info = cjfm_user_info($current_user->ID);
	update_user_meta($user_info['ID'], 'cjfm_'.$service.'_id', $social_profile->identifier);
	update_user_meta($user_info['ID'], 'cjfm_'.$service.'_profile', $social_profile);
	$redirect = get_permalink(cjfm_get_option('page_profile'));
	wp_redirect( $redirect );
	exit;
}

function cjfm_social_register_user($social_profile, $form_id, $redirect){
	global $wpdb;

	$service = $_GET['cjfm-social-login'];
	$form_id = $_GET['fid'];

	$must_fillup_signup_form = cjfm_get_option('social_must_register');
	if($must_fillup_signup_form == 'yes'){

		$social_data['identifier'] = $social_profile->identifier;
		$social_data['gender'] = $social_profile->gender;
		$social_data['cjfm_city'] = $social_profile->city;
		$social_data['cjfm_zipcode'] = $social_profile->zip;
		$social_data['first_name'] = $social_profile->firstName;
		$social_data['last_name'] = $social_profile->lastName;
		if($social_profile->email != ''){
			$social_data['user_email'] = $social_profile->email;
		}
		$social_data['display_name'] = $social_profile->firstName.''.$social_profile->lastName;
		$social_data = base64_encode(serialize($social_data));
		$location = cjfm_string(cjfm_generate_url('page_register')).'cjfm-social-profile='.$service.'&data='.$social_data;
		wp_redirect( $location );
		exit;
	}

	// Create new user
	$user_login = cjfm_generate_unique_username($social_profile->firstName.$social_profile->lastName);
	$user_pass = wp_generate_password( 10, false, false );
	$user_email = ($social_profile->email != '') ? $social_profile->email : cjfm_generate_dummy_email($social_profile->firstName.$social_profile->lastName);
	$new_user_id = wp_create_user( $user_login, $user_pass, $user_email );

	if(!is_wp_error($new_user_id)){
		$social_state = explode(',', $social_profile->region);
		$social_state = (is_array($social_state)) ? trim($social_state[0]) : '';
		update_user_meta($new_user_id, 'cjfm_user_salt', base64_encode($user_pass));
		update_user_meta($new_user_id, 'first_name', $social_profile->firstName);
		update_user_meta($new_user_id, 'last_name', $social_profile->lastName);
		update_user_meta($new_user_id, 'gender', $social_profile->gender);
		update_user_meta($new_user_id, 'display_name', $social_profile->displayName);
		update_user_meta($new_user_id, 'cjfm_address1', $social_profile->address);
		update_user_meta($new_user_id, 'cjfm_city', $social_profile->city);
		update_user_meta($new_user_id, 'cjfm_state', $social_state);
		update_user_meta($new_user_id, 'cjfm_zipcode', $social_profile->zip);
		update_user_meta($new_user_id, 'cjfm_country', cjfm_countries_array($social_profile->country));
		update_user_meta($new_user_id, 'user_avatar', $social_profile->photoURL);
		update_user_meta($new_user_id, 'description', $social_profile->description);
		update_user_meta($new_user_id, strtolower($service).'_url', $social_profile->profileURL);

		// Login and Update Existing User;
		$user_info = cjfm_user_info($new_user_id);
		$user_pass = base64_decode($user_info['cjfm_user_salt']);
		$creds = array();
		$creds['user_login'] = $user_info['user_login'];
		$creds['user_password'] = $user_pass;
		$creds['remember'] = (isset($_POST['remember_me'])) ? true : false;
		$user = wp_signon( $creds, is_ssl() );
		if ( !is_wp_error($user) ){
			update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($user_pass));
			update_user_meta($user->ID, 'cjfm_last_login', time());
			update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());
			$user_info = cjfm_user_info($user->ID);
			if(!isset($user_info['cjfm_form_id'])){
				update_user_meta($user_info['ID'], 'cjfm_form_id', $_GET['fid']);
			}
			$user_info = cjfm_user_info($user->ID);
			update_user_meta($user_info['ID'], 'cjfm_'.$service.'_id', $social_profile->identifier);
			update_user_meta($user_info['ID'], 'cjfm_'.$service.'_profile', $social_profile);
			do_action('cjfm_login_done', $user_info);
			do_action( 'wp_login', $user_login );
			// Notify Admin
			cjfm_send_new_user_email_to_admin($user_info['ID'], $user_info['cjfm_form_id']);

			// Send welcome email to user
			$dummy_email = explode('@', $user_info['user_email']);
			if($dummy_email[1] == 'no-email.com'){
				$user_data['ID'] = $user_info['ID'];
				$user_data['user_email'] = '';
				wp_update_user($user_data);
			}else{
				$email_subject = cjfm_get_option('welcome_email_subject');
				$email_message = cjfm_parse_email('welcome_email_message', $user_info);
				$email_data = array(
					'to' => $user_info['user_email'],
					'from_name' => cjfm_get_option('from_name'),
					'from_email' => cjfm_get_option('from_email'),
					'subject' => $email_subject,
					'message' => $email_message,
				);
				cjfm_email($email_data);
			}

			wp_redirect( $redirect );
			exit;
		}

	}
}


function cjfm_social_login_user($social_profile, $user_info, $form_id, $redirect){
	global $wpdb, $current_user;
	$service = $_GET['cjfm-social-login'];
	$form_id = $_GET['fid'];
	if(isset($user_info['cjfm_user_salt'])){
		$user_pass = base64_decode($user_info['cjfm_user_salt']);
	}else{
		$user_pass = wp_generate_password( 10, false, false );
		wp_set_password( $user_pass, $user_info['ID'] );
		update_user_meta($user_info['ID'], 'cjfm_user_salt', base64_encode($user_pass));
	}
	$creds = array();
	$creds['user_login'] = $user_info['user_login'];
	$creds['user_password'] = $user_pass;
	$creds['remember'] = (isset($_POST['remember_me'])) ? true : false;
	$user = wp_signon( $creds, is_ssl() );
	if ( !is_wp_error($user) ){
		update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($user_pass));
		update_user_meta($user->ID, 'cjfm_last_login', time());
		update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());
		$user_info = cjfm_user_info($user->ID);
		if(!isset($user_info['cjfm_form_id'])){
			update_user_meta($user_info['ID'], 'cjfm_form_id', $_GET['fid']);
		}
		update_user_meta($user_info['ID'], 'cjfm_'.$service.'_id', $social_profile->identifier);
		update_user_meta($user_info['ID'], 'cjfm_'.$service.'_profile', $social_profile);
		do_action('cjfm_login_done', $user_info);
		do_action( 'wp_login', $user_login );
		wp_redirect( $redirect );
		exit;
	}
}