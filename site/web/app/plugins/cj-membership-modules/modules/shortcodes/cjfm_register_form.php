<?php
function cjfm_form_register( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array(
		'return' => null,
		"form_id" => 1,
		"button_text" => "Create new account",
		'button_class' => null,
		'required_text' => __('*', 'cjfm'),
		"class" => "",
		'redirect_url' => cjfm_current_url('only'),
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';
	$logout_url = cjfm_generate_url('page_logout');

	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$custom_forms_query = $wpdb->get_results("SELECT * FROM $forms_table ORDER BY id ASC");
	foreach ($custom_forms_query as $key => $form) {
		$custom_forms_array[$form->id] = $form->form_name;
	}

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display Registration Form with custom fields specified under Configuration >> Custom Form Fields.', 'cjfm'),
		'form_id' => array(__('Select form to include fields', 'cjfm'), 'dropdown', $custom_forms_array, __('Form fields will be included as per the custom form specified.', 'cjfm')),
		'redirect_url' => array(__('Redirect URL (default: homepage)', 'cjfm'), 'text', null, __('User will be redirected to this Url after successful registration.', 'cjfm')),
		'required_text' => array(__('Required Field Text', 'cjfm'), 'text', null, __('This text will be added to the username label. (Default: *)', 'cjfm')),
		"button_text" => array(__('Button Text (default: Create new account)', 'cjfm'), 'text', null, __('Default Button text will be replaced by this value.', 'cjfm')),
		"button_class" => array(__('Button CSS Class', 'cjfm'), 'text', null, __('Specify custom CSS classes for the button', 'cjfm')),
		"class" => array(__('Container CSS Class', 'cjfm'), 'text', null, __('Specify custom CSS class for the form container.', 'cjfm')),
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(isset($_GET['redirect_url'])){
		$redirect = $_GET['redirect_url'];
	}elseif(isset($_GET['redirect'])){
		$redirect = $_GET['redirect'];
	}elseif(isset($_GET['redirect_to'])){
		$redirect = $_GET['redirect_to'];
	}elseif(!isset($_GET['redirect']) && !isset($_GET['redirect_url'])){
		$redirect = $redirect_url;
	}elseif(is_null($redirect)){
		$redirect = site_url();
	}

	// Check if the form id specified in the shortcode exists otherwise defaults to default form.
	$check_form_exists = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = '{$form_id}'");
	if(is_null($check_form_exists)){
		$form_id = 1;
	}

	$register_page = cjfm_get_option('page_register');
	$register_page_url = get_permalink(cjfm_get_option('page_register'));
	$current_page = (isset($post) && !empty($post) && $register_page == $post->ID) ? 'yes' : 'no';
	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';
	$must_verify_email = cjfm_get_option('verify_email_address');

	$fields_has_errors = array();

	if(is_user_logged_in()){
		$display[] = '<div class="'.$class.'">';
		$display[] = '<p>'.sprintf(__('You are already logged in as %s. <a href="%s">Logout</a>', 'cjfm'), $current_user->user_login, $logout_url).'</p>';
		$display[] = '</div>';
		if($return == null){
		    return implode('', $display);
		}else{
		    return serialize($options);
		}
	}

	if(is_null($button_class)){
		$btn_color = cjfm_get_option('button_color');
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'cjfm-btn cjfm-btn-'.$btn_color.' cjfm-btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$registration_type = cjfm_get_option('register_type');

	if($registration_type == 'invitations' && !isset($_GET['invitation_token'])){
		$form_type = 'invitation';
	}
	if($registration_type == 'invitations' && isset($_GET['invitation_token'])){
		$form_type = 'register';
	}
	if($registration_type == 'approvals' || $registration_type == 'normal'){
		$form_type = 'register';
	}
	if($registration_type == 'profile'){
		$form_type = 'profile';
	}

	// Normal Registration Process
	if($registration_type == 'normal' || $registration_type == 'approvals'){
		if(isset($_POST['cjfm_process_registration'])){
			$process_normal_registration = cjfm_process_registration_form_normal($_POST['cjfm_form_id'], $redirect);
			if(is_array($process_normal_registration)){
				$display[] = cjfm_show_message('error', implode('<br>', $process_normal_registration['errors']));
				$fields_has_errors = $process_normal_registration['field_has_errors'];
			}
		}
		if(isset($_GET['confirmation-mail']) && $_GET['confirmation-mail'] == 'sent'){
			$display[] = cjfm_show_message('success', cjfm_get_option('verify_email_onscreen_message'));
		}
		if(isset($_GET['confirmation-key']) && $_GET['confirmation-key'] == 'invalid'){
			$display[] = cjfm_show_message('error', __('Invalid activation key, please try again.', 'cjfm'));
		}

		if(isset($_GET['cjfm-social-profile']) && $_GET['cjfm-social-profile'] != '' && isset($_GET['data'])){
			$display[] = sprintf(__('<p>You are connected via %s. <br>Please fill out the form below to complete your registration.</p>', 'cjfm'), $_GET['cjfm-social-profile']);
		}
		$social_data = null;
		if(isset($_GET['cjfm-social-profile']) && $_GET['cjfm-social-profile'] != '' && isset($_GET['data'])){
			$social_data = unserialize(base64_decode($_GET['data']));
		}

		$predefined_profile_data = (is_null($social_data)) ? array() : $social_data;

		// Generate form fields
		$args = array(
			'button_name' => 'cjfm_process_registration',
			'button_text' => $button_text,
			'button_class' => $button_class,
			'class' => $class,
			'has_errors' => $fields_has_errors,
			'required_text' => $required_text,
			'predefined_data' => $predefined_profile_data,
			'redirect_url' => $redirect,
		);
		$form_fields = cjfm_custom_form_fields($form_id, $form_type, $args);
	}


	// Invitations Type Registration Process
	if($registration_type == 'invitations'){

		if(isset($_POST['cjfm_process_registration']) && !isset($_GET['invitation_token'])){
			$process_invitations_registration = cjfm_process_registration_form_invitations($_POST['cjfm_form_id'], $redirect);
			if(is_array($process_invitations_registration)){
				$display[] = cjfm_show_message('error', implode('<br>', $process_invitations_registration['errors']));
				$fields_has_errors = $process_invitations_registration['field_has_errors'];
			}
		}

		if(isset($_POST['cjfm_process_registration']) && isset($_GET['invitation_token'])){
			$process_normal_registration = cjfm_process_registration_form_normal($_POST['cjfm_form_id'], $redirect);
			if(is_array($process_normal_registration)){
				$display[] = cjfm_show_message('error', implode('<br>', $process_normal_registration['errors']));
				$fields_has_errors = $process_normal_registration['field_has_errors'];
			}
		}

		if(isset($_GET['invitation']) && $_GET['invitation'] == 'sent'){
			$display[] = cjfm_show_message('success', __('Thank You! Your invitation request has been received.', 'cjfm'));
		}
		if(isset($_GET['invitation']) && $_GET['invitation'] == 'exists'){
			$display[] = cjfm_show_message('success', __('Your invitation request is awaiting approval.', 'cjfm'));
		}
		if(isset($_GET['invitation']) && $_GET['invitation'] == 'approved'){
			$display[] = cjfm_show_message('success', __('Your invitation request has been approved, please check your email for registration link.', 'cjfm'));
		}
		if(isset($_GET['invitation']) && $_GET['invitation'] == 'declined'){
			$display[] = cjfm_show_message('error', __('Your invitation request has been declined.', 'cjfm'));
		}
		// Invitation Email
		$invite_data = array();
		if(isset($_GET['invitation_token'])){
			$invite_token = $_GET['invitation_token'];
			$invitation_info = $wpdb->get_row("SELECT * FROM $invitations_table WHERE invitation_key = '{$invite_token}'");
			if(!empty($invitation_info)){
				$invite_email = $invitation_info->user_email;
				$invite_data = unserialize($invitation_info->user_data);
				if(isset($invite_data['cjfm_form_id'])){
					$form_id = $invite_data['cjfm_form_id'];
				}
			}else{
				wp_redirect( cjfm_current_url() );
				exit;
			}
		}

		// Generate form fields
		$args = array(
			'button_name' => 'cjfm_process_registration',
			'button_text' => $button_text,
			'button_class' => $button_class,
			'class' => $class,
			'has_errors' => $fields_has_errors,
			'required_text' => $required_text,
			'predefined_data' => $invite_data,
			'redirect_url' => $redirect,
		);
		$form_fields = cjfm_custom_form_fields($form_id, $form_type, $args);
	}

	if(!isset($form_fields['user_login']) && !isset($form_fields['user_email'])){
		$display[] = cjfm_show_message('error', __('Registration without user_login and user_email fields is not allowed.', 'cjfm'));
	}else{

		$custom_styles_register_page = ($registration_type == 'invitations') ? 'invitation' : 'register';
		$enable_ajax_class = ($registration_type != 'invitations') ? 'cjfm-ajax-register-form' : '';
		$display[] = '<div class="cjfm-form cjfm-register-form '.cjfm_form_custom($custom_styles_register_page).' '.$class.' '.$enable_ajax_class.'">';
		$display[] = ($registration_type == 'invitations') ? cjfm_get_option('invitations_content') : '';
		$display[] = ($registration_type == 'approvals') ? cjfm_get_option('approvals_content') : '';
		$display[] = '<form action="" method="post" data-redirect="'.$redirect.'" enctype="multipart/form-data" autocomplete="off">';
		$display[] = '<span class="cjfm-loading"></span>';
		$display[] = wp_nonce_field('cjfm_do_register','cjfm_do_register_nonce', '', false);
		$display[] = cjfm_display_form($form_fields);
		$display[] = '</form>';
		$display[] = '</div>';
	}

	$display = apply_filters( 'registration_form_filter', $display );

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}
}
add_shortcode( 'cjfm_form_register', 'cjfm_form_register' );