<?php
function cjfm_user_profile( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array(
		'return' => null,
		'form_id' => 1, // test this and implement
		'redirect' => cjfm_current_url('only-url'),
		'button_text' => __('Update Profile', 'cjfm'),
		'button_class' => null,
		'class' => '',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$enable_disable_array =  array(
		'enable' => __('Enable', 'cjfm'),
		'disable' => __('Disable', 'cjfm'),
	);

	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$custom_forms_query = $wpdb->get_results("SELECT * FROM $forms_table ORDER BY id ASC");
	foreach ($custom_forms_query as $key => $form) {
		$custom_forms_array[$form->id] = $form->form_name;
	}

	$options = array(
		'stype' => 'single', // single or closed
		'form_id' => array(__('Select custom form', 'cjfm'), 'dropdown', $custom_forms_array, __('Form fields will be included as per the custom form specified.', 'cjfm')),
		'description' => __('This shortcode will display edit profile form and will include custom fields specified to be displayed on edit profile page.', 'cjfm'),
		'button_text' => array(__('Submit Button Text', 'cjfm'), 'text', null, __('This will replace the submit button text. (Default: Login)', 'cjfm')),
		'button_class' => array(__('Submit Button CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the submit button.', 'cjfm')),
		'class' => array(__('Container CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS classes for the from container.', 'cjfm'))
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	if(is_null($button_class)){
		$btn_color = cjfm_get_option('button_color');
		$btn_size = cjfm_get_option('button_size');
		$button_class = 'cjfm-btn cjfm-btn-'.$btn_color.' cjfm-btn-'.$btn_size.' ';
	}else{
		$button_class = $button_class;
	}

	$login_url = cjfm_generate_url('page_login');

	$cjfm_user_profile_page = cjfm_get_option('page_profile');

	# Check if user is logged in
	if(!is_user_logged_in() && is_page( $cjfm_user_profile_page )){
		$login_url_profile = cjfm_string($login_url).'redirect='.urlencode(cjfm_current_url('url-only'));
		wp_redirect( $login_url_profile );
		exit;
	}else{
		$user_info = cjfm_user_info($current_user->ID);
	}

	if(!isset($user_info['cjfm_form_id'])){
		update_user_meta($user_info['ID'], 'cjfm_form_id', $form_id);
		$user_info = cjfm_user_info($current_user->ID);
	}

	// Check if the form id specified in the shortcode exists otherwise defaults to default form.
	$check_form_exists = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = '{$user_info['cjfm_form_id']}'");
	if(is_null($check_form_exists)){
		update_user_meta($user_info['ID'], 'cjfm_form_id', 1);
		$user_info = cjfm_user_info($current_user->ID);
	}

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	# Process Form
	$errors = null;
	$fields_has_errors = null;
	if(isset($_POST['do_update_profile']) && is_null($return)){

		// Required Fields
		$exclude_fields = array('user_pass', 'user_pass_conf', 'user_avatar', 'heading', 'custom_html', 'paragraph', 'file');
		$required_fields = $wpdb->get_results("SELECT unique_id, field_type FROM $fields_table WHERE form_id = '{$user_info['cjfm_form_id']}' AND required = 'yes' AND profile = 'yes' AND enabled = 'yes' ORDER BY sort_order ASC");
		foreach ($required_fields as $key => $rf) {
			if(!in_array($rf->unique_id, $exclude_fields) && !in_array($rf->field_type, $exclude_fields)){
				if(@$_POST[$rf->unique_id] == ''){
					$required_fields_array[] = $rf->unique_id;
					$errors['missing'] = __('Missing required fields', 'cjfm');
					$fields_has_errors[] = $rf->unique_id;
				}
			}
		}

		// Email Check
		if(!is_email($_POST['user_email'])){
			$errors[] = __('Invalid email address', 'cjfm');
			$fields_has_errors[] = 'user_email';
		}elseif(email_exists( $_POST['user_email'] ) && $_POST['user_email'] != $current_user->user_email){
			$errors[] = __('Email address assigned to another account.', 'cjfm');
			$fields_has_errors[] = 'user_email';
		}

		// Password Check
		if($_POST['user_pass'] != ''){
			if($_POST['user_pass'] != $_POST['user_pass_conf']){
				$errors['no_match'] = __('Password fields does not match', 'cjfm');
				$fields_has_errors[] = 'user_pass';
				$fields_has_errors[] = 'user_pass_conf';
			}elseif(strlen($_POST['user_pass']) < cjfm_get_option('password_length')){
				$errors[] = sprintf(__('Password must be %d characters long', 'cjfm'), cjfm_get_option('password_length'));
				$fields_has_errors[] = 'user_pass';
			}
		}

		if(is_null($errors)){
			$filter_errors = apply_filters('edit_profile_errors', $_POST);
			if(isset($filter_errors['error'])){
				$errors = $filter_errors['error'];
			}
		}


		if(!is_null($errors)){
			$display[] = cjfm_show_message('error', implode('<br>', $errors));
		}


		if(is_null($errors)){
			foreach ($_POST as $key => $value) {
				if($key != 'user_pass' && $key != 'user_pass_conf'){
					update_user_meta($current_user->ID, $key, $value);
				}
				if($key == 'user_pass' && $_POST['user_pass'] != '' && $_POST['user_pass_conf'] != '' && $_POST['user_pass'] == $_POST['user_pass_conf']){
					wp_set_password( $_POST['user_pass'], $current_user->ID );
				}
			}

			$update_user_data = array(
				'ID' => $current_user->ID,
				'user_email' => $_POST['user_email'],
				'user_url' => (isset($_POST['user_url'])) ? $_POST['user_url'] : '',
				'display_name' => (isset($_POST['display_name'])) ? $_POST['display_name'] : $user_info['display_name'],
			);
			wp_update_user( $update_user_data );


			if(isset($_FILES)){
				foreach ($_FILES as $key => $file) {
					if($file['name'] != '' && $key != 'user_avatar'){
						$allowed_file_types = $wpdb->get_row("SELECT * FROM $fields_table WHERE unique_id = '{$key}'");
						$file_url = cjfm_file_upload($key, null, null, $allowed_file_types->description, 'guid');
						if(is_array($file_url)){
							$errors = $file_url;
						}else{
							update_user_meta($current_user->ID, $key, $file_url);
						}

					}
				}
			}

			// User Avatar
			if(cjfm_get_option('user_avatar_type') == 'custom'){
				if(isset($_FILES) && $_FILES['user_avatar']['error'] == ''){
					$user_avatar_url = cjfm_file_upload('user_avatar', null, null, cjfm_get_option('user_avatar_filetypes'), 'guid', cjfm_get_option('user_avatar_filesize'));
					if(!is_array($user_avatar_url)){
						if($user_avatar_url != get_user_meta($current_user->ID, 'user_avatar', true)){
							update_user_meta($current_user->ID, 'user_avatar', $user_avatar_url);
						}
					}else{
						$errors = $user_avatar_url;
						$fields_has_errors[] = 'user_avatar';
					}
				}
			}else{
				update_user_meta($current_user->ID, 'user_avatar', cjfm_gravatar_url($current_user->ID));
			}

			if(!is_null($errors)){
				$display[] = cjfm_show_message('error', implode('<br>', $errors));
			}else{

				$user_info = cjfm_user_info($current_user->ID);
				do_action('cjfm_profile_updated', $user_info);

				$location = cjfm_string(cjfm_current_url('only-url')).'profile=updated';
				wp_redirect( $location, $status = 302 );
				exit;

			}

		}
	}

	if(isset($_GET['disconnect-service']) && $_GET['disconnect-service'] != ''){
		delete_user_meta($current_user->ID, 'cjfm_'.$_GET['disconnect-service'].'_id');
		delete_user_meta($current_user->ID, 'cjfm_'.$_GET['disconnect-service'].'_profile');
		wp_redirect(cjfm_current_url('only'));
		exit;
	}

	/*$social_services = cjfm_get_option('cjfm_social_services');
	foreach ($social_services as $skey => $svalue) {
		$option_name = 'cjfm_'.$skey.'_id';
		if(get_user_meta($current_user->ID, $option_name, true) != ''){
			$display[] = sprintf(__('<p class="cjfm-social-connect-profile"><b>Your account is connected with %s</b>. <a class="cjfm-confirm" data-confirm="Are you sure? This cannot be undone." href="%s">Disconnect</a></p>', 'cjfm'), $skey, cjfm_string(cjfm_current_url('only-url')).'disconnect-service='.$skey);
		}
	}*/

	if(is_null($errors) && isset($_GET['profile']) && $_GET['profile'] == 'updated'){
		$display[] = cjfm_show_message('success', __('Profile information updated.', 'cjfm'));
	}

	if($user_info['user_email'] == ''){
		$display[] = cjfm_show_message('error', __('You must specify your email address.', 'cjfm'));
	}




	// Generate form fields
	$form_type = 'profile';
	$form_id = (isset($user_info['cjfm_form_id'])) ? $user_info['cjfm_form_id'] : $form_id;
	$args = array(
		'button_name' => 'do_update_profile',
		'button_text' => $button_text,
		'button_class' => $button_class,
		'class' => $class,
		'has_errors' => $fields_has_errors,
		'predefined_data' => array(),
	);

	$form_fields = cjfm_custom_form_fields($form_id, $form_type, $args);

	$display[] = '<div class="cjfm-form '.cjfm_form_custom('edit_profile_form').' cj-form-edit-profile '.$class.'">';
	$display[] = '<form action="'.cjfm_current_url().'" method="post" enctype="multipart/form-data" autocomplete="off">';
	$display[] = cjfm_display_form($form_fields);
	$display[] = '</form>';
	$display[] = '</div>';


	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_user_profile', 'cjfm_user_profile' );