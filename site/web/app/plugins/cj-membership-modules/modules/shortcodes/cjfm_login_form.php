<?php
function cjfm_form_login( $atts, $content) {
	global $wpdb, $current_user, $post;
	get_currentuserinfo();
	$defaults = array(
		'return' => null,
		'form_id' => 1, // test this and implement
		'redirect_url' => cjfm_current_url('only'), // test this and implement
		'user_login_label' => __('Username or Email address:', 'cjfm'),
		'user_login_placeholder' => '',
		'user_pass_label' => __('Password:', 'cjfm'),
		'user_pass_placeholder' => '',
		'show_icons' => 'yes',
		'show_remember_me' => 'yes',
		'button_text' => __('Login', 'cjfm'),
		'button_class' => null,
		'required_text' => __('*', 'cjfm'),
		'forgot_password_text' => 'Forgot password?',
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$yes_no_array = array(
		'yes' => 'yes',
		'no' => 'no',
	);

	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$custom_forms_query = $wpdb->get_results("SELECT * FROM $forms_table ORDER BY id ASC");
	foreach ($custom_forms_query as $key => $form) {
		$custom_forms_array[$form->id] = $form->form_name;
	}

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display a login form and redirect the user to specified redirect_url upon successful login.', 'cjfm'),
		'form_id' => array(__('Select custom form', 'cjfm'), 'dropdown', $custom_forms_array, __('Form fields will be included as per the custom form specified.', 'cjfm')),
		'redirect_url' => array(__('Redirect URL: (Default: Homepage)', 'cjfm'), 'text', null, __('Specify a valid Url where you would like users to go after successful login', 'cjfm')),
		'user_login_label' => array(__('Username Label', 'cjfm'), 'text', 'Username or Email address:', __('Specify username field label.', 'cjfm')),
		'user_login_placeholder' => array(__('Username Placeholder', 'cjfm'), 'text', null, __('Specify placeholder for user login input.', 'cjfm')),
		'user_pass_label' => array(__('Password Label', 'cjfm'), 'text', null, __('Specify password field label.', 'cjfm')),
		'user_pass_placeholder' => array(__('Password Placeholder', 'cjfm'), 'text', null, __('Specify placeholder for user pass input.', 'cjfm')),
		'show_icons' => array(__('Show icons', 'cjfm'), 'dropdown', $yes_no_array, __('You can choose to display font awesome icons for the textboxes.', 'cjfm')),
		'show_remember_me' => array(__('Show remember me checkbox', 'cjfm'), 'dropdown', $yes_no_array, __('Choose if you would like to display remember me checkbox or not.', 'cjfm')),
		'required_text' => array(__('Required Field Text', 'cjfm'), 'text', null, __('This text will be added to the username label. (Default: *)', 'cjfm')),
		'button_text' => array(__('Submit Button Text', 'cjfm'), 'text', null, __('This will replace the submit button text. (Default: Login)', 'cjfm')),
		'forgot_password_text' => array(__('Forgot Password Link Text', 'cjfm'), 'text', __('Forgot password?', 'cjfm'), __('Specify forgot password text', 'cjfm')),
		'button_class' => array(__('Submit Button CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the submit button.', 'cjfm')),
		'class' => array(__('Container CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the from container.', 'cjfm'))
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(is_null($button_class)){
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'cjfm-btn cjfm-btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$redirect = $redirect_url;

	if(isset($_GET['redirect_url'])){
		$redirect = $_GET['redirect_url']; //.'loggedin=1';
	}elseif(isset($_GET['redirect'])){
		$redirect = $_GET['redirect']; //.'loggedin=1';
	}elseif(isset($_GET['redirect_to'])){
		$redirect = $_GET['redirect_to']; //.'loggedin=1';
	}elseif(!isset($_GET['redirect']) && !isset($_GET['redirect_url'])){
		$redirect = $redirect_url; //.'loggedin=1';
	}elseif(is_null($redirect)){
		$redirect = site_url(); //.'loggedin=1';
	}

	// Check if the form id specified in the shortcode exists otherwise defaults to default form.
	$check_form_exists = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = '{$form_id}'");
	if(is_null($check_form_exists)){
		$form_id = 1;
	}

	// Resend activation key email
	if(isset($_GET['confirmation']) && $_GET['confirmation'] == 'sent'){
		$display[] = cjfm_show_message('success', __('We just sent the confirmation email again, please check your email.', 'cjfm'));
	}

	if(isset($_GET['resend_activation_email']) && $_GET['resend_activation_email'] == '1'){
		$verify_email_link = cjfm_string(cjfm_current_url('only')).'cjfm_verify='.$_GET['email'].'&key='.$_GET['key'];
		$post_data = array(
			'user_email' => $_GET['email'],
		);
		$verify_email_message = cjfm_parse_verification_email($post_data, $verify_email_link);
		$verify_email_data = array(
			'to' => urldecode($_GET['email']),
			'from_name' => cjfm_get_option('from_name'),
			'from_email' => cjfm_get_option('from_email'),
			'subject' => cjfm_get_option('verify_email_subject'),
			'message' => $verify_email_message,
		);
		cjfm_email($verify_email_data);
		$location = cjfm_string(cjfm_current_url('only')).'confirmation=sent';
		wp_redirect( $location );
		die();
	}



	// PROCESS FORM
	$display[] = '';
	$process_form = 'false';

	if ( empty($_POST) || !@wp_verify_nonce($_POST['cjfm_do_login_nonce'],'cjfm_do_login') ){
	   $process_form = 'false';
	}else{
	   $process_form = 'true';
	}

	if(isset($_POST['do_login']) && $process_form == 'true'){

		$errors = null;

		$errors = cjfm_spam_protection_process($_POST, 'login');
		$user_info = cjfm_user_info($_POST['login_form_user_login']);
		$user_login = $user_info['user_login'];
		$user_pass = $_POST['login_form_user_pass'];

		$table_temp_users = $wpdb->prefix.'cjfm_temp_users';

		$awaiting_users = $wpdb->get_results("SELECT * FROM $table_temp_users");
		if(!empty($awaiting_users)){
			foreach ($awaiting_users as $key => $auser) {
				$auser_info = unserialize($auser->user_data);
				if($_POST['login_form_user_login'] == $auser_info['user_login']){
					$resend_activation_link = cjfm_string(cjfm_current_url('only')).'resend_activation_email=1&email='.$auser->user_email.'&key='.$auser->activation_key;
					$errors['awaiting-confirmation'] = __('We have sent you a confirmation email, please check your email\'s junk folder if you do not see that in your inbox.', 'cjfm');
					$errors['awaiting-confirmation'] .= '<br><a href="'.$resend_activation_link.'" style="color:#fff; text-decoration:underline; border:none; display:inline-block; margin-top: 5px;">'.__('Resend confirmation email', 'cjfm').'</a>';
				}
			}
		}

		if(!is_null($errors)){
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}else{

			if(!is_wp_error( wp_authenticate( $user_login, $user_pass ) )){

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
						update_user_meta($user_info['ID'], 'cjfm_form_id', $form_id);
					}
					do_action( 'wp_login', $user_login );
					do_action('cjfm_login_done', $user_info);
					wp_redirect( $_POST['redirect_url'] );
					exit;
				}
			}else{
				$display[] = cjfm_show_message('error', __('Invalid username or password.', 'cjfm'));
			}
		}
	}


	$reset_password_url = cjfm_generate_url('page_reset_password');
	$logout_url = cjfm_generate_url('page_logout');
	$args = array(
		'user_login_label' => ($user_login_label != '') ? __($user_login_label, 'cjfm').' <span class="cjfm-required">'.__($required_text, 'cjfm').'</span>' : '',
		'user_pass_label' => ($user_pass_label != '') ? __($user_pass_label, 'cjfm').' <span class="cjfm-required">'.__($required_text, 'cjfm').'</span>' : '',
		'button_text' => $button_text,
		'button_class' => $button_class,
		'button_suffix' => ($forgot_password_text != '') ? '<a class="button-suffix forgot-password-link" href="'.$reset_password_url.'">'.$forgot_password_text.'</a>' : '',
	);

	$remember_me = ($show_remember_me == 'yes') ? '<span class="cjfm-inline-block button-suffix"><label><input name="remember_me" type="checkbox" /> '.__('Remember me', 'cjfm').' </label></span>' : '<input name="remember_me" type="checkbox" checked style="display:none;" />';

	$form_fields['login_form'] = array(
		array(
		    'type' => 'text',
		    'id' => 'login_form_user_login',
		    'label' => $args['user_login_label'],
		    'info' => '',
		    'suffix' => ($show_icons == 'yes') ? '<i class="fa fa-user"></i>' : '',
		    'prefix' => '',
		    'params' => array('class' => 'form-control form-type-login login_form_user_login', 'placeholder' => __($user_login_placeholder, 'cjfm')),
		    'default' => cjfm_post_default('login_form_user_login', ''),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'password',
		    'id' => 'login_form_user_pass',
		    'label' => $args['user_pass_label'],
		    'info' => '',
		    'class' => '',
		    'suffix' => ($show_icons == 'yes') ? '<i class="fa fa-lock"></i>' : '',
		    'prefix' => '',
		    'params' => array('class' => 'form-control form-type-login login_form_user_pass', 'placeholder' => __($user_pass_placeholder, 'cjfm')),
		    'default' => cjfm_post_default('login_form_user_pass', ''),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'hidden',
		    'id' => 'redirect_url',
		    'label' => null,
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => $redirect,
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		cjfm_spam_protection_field('login'),
		array(
		    'type' => 'submit',
		    'id' => 'do_login',
		    'label' => __($args['button_text'], 'cjfm'),
		    'info' => '',
		    'suffix' => $remember_me.$args['button_suffix'],
		    'prefix' => '',
		    'class' => $args['button_class'],
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	);

	if(!is_user_logged_in()){
		$display[] = '<div class="cjfm-form '.cjfm_form_custom('login').' cjfm-login-form '.$class.' ">';
		$display[] = '<form action="'.cjfm_current_url().'" method="post" data-redirect="'.$redirect.'">';
		$display[] = '<span class="cjfm-loading"></span>';
		$display[] = wp_nonce_field('cjfm_do_login','cjfm_do_login_nonce', '', false);
		$display[] = cjfm_display_form($form_fields['login_form']);
		$display[] = '</form>';
		$display[] = '</div>';
	}else{
		$display[] = '<div class="cjfm-form '.cjfm_form_custom('login').' cjfm-login-form '.$class.' ">';
		$display[] = '<p>'.sprintf(__('You are already logged in as %s. <a href="%s">Logout</a>', 'cjfm'), $current_user->user_login, $logout_url).'</p>';
		$display[] = '</div>';
	}

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_form_login', 'cjfm_form_login' );