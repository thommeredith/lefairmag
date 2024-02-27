<?php
/**
 * Plugin Settings
 *
 * Setup plugin functionality, scripts and other functions.
 *
 * @author      Mohit Aneja - CSSJockey.com
 * @category    Framework
 * @package     CSSJockey/Framework
 * @version     1.0
 * @since       1.0
 */

global $wpdb, $current_user;

add_action('init', 'cjfm_session_start', 1);
function cjfm_session_start() {
    if(!session_id()) {
        session_start();
    }
}


# Plugin Styles & Scripts
####################################################################################################
add_action('wp_enqueue_scripts', 'cjfm_plugin_scripts', 999);
function cjfm_plugin_scripts(){
	if(!is_admin()){
		$plugin_css = cjfm_get_option('plugin_css');
		if($plugin_css == 'yes'){
			wp_register_style('font-awesome','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css');
    		wp_enqueue_style( 'font-awesome');
			wp_enqueue_style('cjfm', cjfm_item_path('item_url').'/assets/css/cjfm.css', null, cjfm_item_info('item_version'), false);
		}
		// Javascript
		wp_enqueue_script('jquery-ui-datepicker');

		if(cjfm_get_option('plugin_jquery_ui_css') == 'yes'){
			wp_enqueue_style('cjfm-jquery-ui', cjfm_item_path('item_url').'/framework/assets/admin/helpers/jquery-ui/css/smoothness/jquery-ui.min.css', null, cjfm_item_info('item_version'), false);
		}

		wp_register_script('cjfm_js', cjfm_item_path('item_url').'/assets/js/cjfm.js', array('jquery'), cjfm_item_info('item_version'), true);

		// Localize the script with new data
		$translation_array = array(
			'weak' => __( 'Weak', 'cjfm' ),
			'medium' => __( 'Medium', 'cjfm' ),
			'strong' => __( 'Strong', 'cjfm' ),
			'please_wait' => __( 'Please wait..', 'cjfm' ),
		);
		wp_localize_script( 'cjfm_js', 'cjfm_locale', $translation_array );
		wp_enqueue_script('cjfm_js');

		if(cjfm_get_option('plugin_ajax') == 'yes'){
			wp_enqueue_script('cjfm_ajax_js', cjfm_item_path('item_url').'/assets/js/cjfm-ajax.js', array('jquery'), cjfm_item_info('item_version'), true);
		}

		wp_enqueue_script('cjfm_custom_js', cjfm_item_path('item_url').'/cjfm-custom.js', array('jquery'), cjfm_item_info('item_version'), true);
		wp_enqueue_style('cjfm_custom_css', cjfm_item_path('item_url').'/cjfm-custom.css', null, cjfm_item_info('item_version'), false);
		//wp_enqueue_style('cjfm_icons_css', cjfm_item_path('item_url').'/assets/cjfm-icons/style.css', null, cjfm_item_info('item_version'), false);
	}
}

// Use shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );

add_action('wp_footer', 'cjfm_custom_scripts');
function cjfm_custom_scripts(){

	$icon_position_top = str_replace('px', '', cjfm_get_option('icon_position_top')).'px';
	$icon_position_right = str_replace('px', '', cjfm_get_option('icon_position_right')).'px';
	$icon_position_right_select = str_replace('px', '', cjfm_get_option('icon_position_right') + 12 ).'px';

	$form_bg = cjfm_get_option('form_bg');
	if(is_array($form_bg)){
		$form_bg_color = $form_bg['color'];
		$form_bg_image = 'url('.$form_bg['image'].')';
		$form_bg_repeat = $form_bg['bg_repeat'];
		$form_bg_size = $form_bg['bg_size'];
		$form_bg_attachment = $form_bg['bg_attachment'];
		$form_bg_position = $form_bg['bg_position'];
	}else{
		$form_bg_color = 'inherit';
		$form_bg_image = 'inherit';
		$form_bg_repeat = 'inherit';
		$form_bg_size = 'inherit';
		$form_bg_attachment = 'inherit';
		$form_bg_position = 'inherit';
	}
	$form_padding = cjfm_get_option('form_padding');
	$form_max_width = cjfm_get_option('form_max_width');
	$form_text_color = cjfm_get_option('form_text_color');
	$form_link_color = cjfm_get_option('form_link_color');
	$form_link_hover_color = cjfm_get_option('form_link_hover_color');
	$form_button_bg = cjfm_get_option('form_button_bg');
	$form_button_bg_border = cjfm_color_brightness($form_button_bg, -20);
	$form_button_text_color = cjfm_get_option('form_button_text_color');

	$display[] = <<<EOD
<style type="text/css">
.cjfm-form{
	max-width: {$form_max_width};
}
.control-group i.fa{
	top: {$icon_position_top};
	right: {$icon_position_right};
}
.control-group.select i.fa{
	top: {$icon_position_top};
	right: {$icon_position_right_select};
}
.cjfm-form .cjfm-btn{
	background: {$form_button_bg};
	border: 1px solid {$form_button_bg_border};
	color: {$form_button_text_color};
}
.cjfm-form .cjfm-btn:hover{
	background: {$form_button_bg_border};
	border: 1px solid {$form_button_bg_border};
	color: {$form_button_text_color};
}


.cjfm-form-custom{
    background-color: {$form_bg_color};
    background-image: {$form_bg_image};
    background-repeat: {$form_bg_repeat};
    background-size: {$form_bg_size};
    background-position: {$form_bg_position};
    background-attachment: {$form_bg_attachment};
    color: {$form_text_color};
    padding: {$form_padding};
}
.cjfm-form-custom a{
	color: {$form_link_color};
}
.cjfm-form-custom a:hover{
	color: {$form_link_hover_color};
}

.cjfm-form-custom .cjfm-btn{
	background: {$form_button_bg};
	border: 1px solid {$form_button_bg_border};
	color: {$form_button_text_color};
}
.cjfm-form-custom .cjfm-btn:hover{
	background: {$form_button_bg_border};
	border: 1px solid {$form_button_bg_border};
	color: {$form_button_text_color};
}

</style>
EOD;

	$display[] = cjfm_get_option('custom_css');
	$display[] = cjfm_get_option('custom_js');

	echo implode("\n", $display);
}

# Set Defaults
####################################################################################################
add_action( 'init', 'cjfm_set_defaults');
function cjfm_set_defaults(){
	global $wpdb, $current_user;
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$default_form_check = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = 1");
	if(is_null($default_form_check)){
		$default_form_data = array(
			'id' => 1,
			'form_name' => 'Default',
			'default_user_role' => get_option('default_role'),
			'can_remove' => 0,
		);
		$form_id = cjfm_insert($forms_table, $default_form_data);
	}
}

# Social Login Providers
####################################################################################################
function cjfm_social_login_providers($provider = null){
	$providers = array(
		'OpenID', 'Google', 'Facebook', 'Twitter', 'Yahoo', 'Live', 'LinkedIn', 'Foursquare', 'Github', 'LastFM', 'Vimeo', 'Viadeo', 'Identica', 'Tumblr', 'Goodreads', 'QQ', 'Sina', 'Murmur', 'Pixnet', 'Plurk', 'Skyrock', 'Geni', 'FamilySearch', 'MyHeritage', 'px500', 'Vkontakte', 'Mail.ru', 'Yandex', 'Odnoklassniki', 'Instagram', 'TwitchTV', 'Steam'
	);
	if(is_null($provider)){
		return $providers;
	}else{
		return $providers[$provider];
	}
}


# Admin Notices
####################################################################################################
add_action( 'admin_notices', 'cjfm_admin_notices');
function cjfm_admin_notices(){

	// Page Setup Admin Notice
	$required_pages = array(
		'page_login',
		'page_logout',
		'page_register',
		'page_reset_password',
		'page_profile',
	);
	$count = 0;
	$page_setup[] = 0;
	foreach ($required_pages as $key => $value) {
		$count++;
		if(cjfm_get_option($value) != 0){
			$page_setup[] = 1;
		}
	}

	if($count != array_sum($page_setup)){
		$page_setup_url = cjfm_callback_url('cjfm_page_setup');
		echo '<div class="error" style="margin-top:10px;">
		      <p>'.sprintf(__('<b>%s %s</b> requires a few pages to be setup. <a href="%s">Click here</a> to setup required pages.', 'cjfm'), cjfm_item_info('item_name'), ucwords(cjfm_item_info('item_type')), $page_setup_url).'
		      </p></div>';
	}

}

// Automatic page setup
add_action('init', 'cjfm_auto_page_setup');
function cjfm_auto_page_setup(){
	global $current_user, $wpdb;

	if(isset($_GET['cjfm_do_action']) && $_GET['cjfm_do_action'] == 'create_pages'){
		$reset_pages = get_option( 'cjfm_auto_page_setup' );
		if(is_array($reset_pages)){
			foreach ($reset_pages as $key => $value) {
				wp_delete_post( $value, true );
				cjfm_update_option($key, '');
			}
		}
		delete_option('cjfm_auto_page_setup');
		$pages['page_login'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Login', 'cjfm'),
			'post_content'   => '[cjfm_form_login redirect_url="'.site_url().'" user_login_label="" user_pass_label="" required_text="" button_text="" button_class="" class=""]',
			'post_name'      => 'login',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_register'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Register', 'cjfm'),
			'post_content'   => '[cjfm_form_register redirect_url="'.site_url().'" button_text="" button_class="" class=""]',
			'post_name'      => 'register',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_logout'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Logout', 'cjfm'),
			'post_content'   => '[cjfm_logout redirect="'.site_url().'" type="direct-logout" button_text="" button_class="" class=""]This content will be displayed to the user if type is set to message.[/cjfm_logout]',
			'post_name'      => 'logout',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_reset_password'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Recover Password', 'cjfm'),
			'post_content'   => '[cjfm_form_reset_password user_login_label="" required_text="" button_text="" button_class="" class=""]',
			'post_name'      => 'recover-password',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);
		$pages['page_profile'] = array(
			'post_type'      => 'page',
			'post_title'     => __('Edit Profile', 'cjfm'),
			'post_content'   => '[cjfm_user_profile button_text="" button_class="" class=""]',
			'post_name'      => 'profile',
			'post_author'    => $current_user->ID,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
		);

		foreach ($pages as $key => $value) {
			if(get_option( 'cjfm_auto_page_setup') != 1){
				$post_id = wp_insert_post( $value );
				cjfm_update_option($key, $post_id);
				$post_ids[$key] = $post_id;
			}
		}
		update_option('cjfm_auto_page_setup', $post_ids);
		$location = cjfm_callback_url('cjfm_page_setup');
		wp_redirect( $location );
		exit;
	}
}

function cjfm_insert_default_form_fields($form_id){
	global $wpdb;
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	// Insert default fields
	$new_form_fields['user_login'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_login',
		'unique_id' => 'user_login',
		'label' => __('Choose Username', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '0',
	);

	$new_form_fields['user_email'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_email',
		'unique_id' => 'user_email',
		'label' => __('Your email address', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'yes',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '1',
	);

	$new_form_fields['user_pass'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_pass',
		'unique_id' => 'user_pass',
		'label' => __('Choose a password', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '2',
	);

	$new_form_fields['user_pass_conf'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_pass_conf',
		'unique_id' => 'user_pass_conf',
		'label' => __('Type password again', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '3',
	);

	$new_form_fields['user_avatar'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_avatar',
		'unique_id' => 'user_avatar',
		'label' => __('Profile Picture', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'no',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '4',
	);

	foreach ($new_form_fields as $key => $df) {
		$new_form_fields_query = $wpdb->get_row("SELECT * FROM $fields_table WHERE unique_id = '{$key}' AND form_id = '{$form_id}'");
		if(is_null($new_form_fields_query)){
			cjfm_insert($fields_table, $df);
		}
	}
}


# Email Messages
####################################################################################################
function cjfm_parse_email($option_name_or_email, $user_info = null, $raw = null, $verify_email_link = null){
	global $wpdb;

	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';

	if(is_null($raw)){
		$message = cjfm_get_option($option_name_or_email);
	}else{
		$message = $option_name_or_email;
	}

	$date_time_string = get_option( 'date_format' ).' '.get_option( 'time_format' );

	$user_info = cjfm_user_info($user_info['ID']);

	$display_name_fallback = (isset($user_info['user_login'])) ? $user_info['user_login'] : __('User', 'cjfm');

	if(is_null($verify_email_link) && is_null($user_info)){
		$dynamic_variables = array(
			"site_name" => get_bloginfo( 'name' ),
			"site_url" => site_url(),
			"signature" => cjfm_get_option('signature'),
			"login_url" => cjfm_generate_url('page_login'),
			"register_url" => cjfm_generate_url('page_register'),
			"reset_password_url" => cjfm_generate_url('page_reset_password'),
			"profile_url" => cjfm_generate_url('page_profile'),
			"verify_email_link" => $verify_email_link,
		);
	}else{
		$dynamic_variables = array(
			"site_name" => get_bloginfo( 'name' ),
			"site_url" => site_url(),
			"signature" => cjfm_get_option('signature'),
			"login_url" => cjfm_generate_url('page_login'),
			"register_url" => cjfm_generate_url('page_register'),
			"reset_password_url" => cjfm_generate_url('page_reset_password'),
			"profile_url" => cjfm_generate_url('page_profile'),
			"ID" => $user_info['ID'],
			"user_login" => $user_info['user_login'],
			"user_email" => $user_info['user_email'],
			"user_url" => $user_info['user_url'],
			"user_registered" => date($date_time_string, strtotime($user_info['user_registered'])),
			"display_name" => (!empty($user_info['display_name'])) ? $user_info['display_name'] : $display_name_fallback,
			"first_name" => (!empty($user_info['first_name'])) ? $user_info['first_name'] : $user_info['user_login'],
			"last_name" => (!empty($user_info['last_name'])) ? $user_info['last_name'] : '',
			"description" => @$user_info['description'],
			"aim" => @$user_info['aim'],
			"yim" => @$user_info['yim'],
			"jabber" => @$user_info['jabber'],
			"cjfm_rp" => @base64_decode($user_info['cjfm_user_salt']),
			"cjfm_last_login" => (isset($user_info['cjfm_last_login'])) ? date($date_time_string, strtotime($user_info['cjfm_last_login'])) : '',
			"cjfm_login_ip" => @$user_info['cjfm_login_ip'],
			"reset_password_confirmation_link" => cjfm_string(get_permalink(cjfm_get_option('page_reset_password'))).'cjfm_action=rp&key='.@$user_info['cjfm_reset_password_key'],
			"verify_email_link" => $verify_email_link,
		);
	}

	// Custom fields
	$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$custom_fields = $wpdb->get_results("SELECT * FROM $custom_fields_table ORDER BY sort_order ASC");
	$exclude_fields = array('user_pass', 'user_pass_conf', 'user_avatar');
	foreach ($custom_fields as $ckey => $cvalue) {
		if(!in_array($cvalue->unique_id, $exclude_fields)){
			$dynamic_variables[$cvalue->unique_id] = cjfm_user_info($user_info['ID'], $cvalue->unique_id);
		}
	}

	foreach ($dynamic_variables as $key => $value) {
		$user_info = cjfm_user_info($user_info['ID']);
		$message = str_replace("%%{$key}%%", $value, $message);
	}

	return $message;
}

# Parse verification email message
function cjfm_parse_verification_email($post_data, $verify_email_link = null){
	global $wpdb;
	$table_temp_users = $wpdb->prefix.'cjfm_temp_users';
	$temp_user_query = $wpdb->get_row("SELECT * FROM $table_temp_users WHERE user_email = '{$post_data['user_email']}'", ARRAY_A);
	$temp_user_info = unserialize($temp_user_query['user_data']);
	unset($temp_user_info['user_pass']);
	unset($temp_user_info['user_pass_conf']);
	$verify_email_message = cjfm_get_option('verify_email_address_message');
	foreach ($temp_user_info as $tkey => $tvalue) {
		if(!is_array($tvalue)){
			$verify_email_message = str_replace('%%'.$tkey.'%%', $tvalue, $verify_email_message);
		}
	}
	$dynamic_variables = array(
		"site_name" => get_bloginfo( 'name' ),
		"site_url" => site_url(),
		"signature" => cjfm_get_option('signature'),
		"login_url" => cjfm_generate_url('page_login'),
		"register_url" => cjfm_generate_url('page_register'),
		"reset_password_url" => cjfm_generate_url('page_reset_password'),
		"profile_url" => cjfm_generate_url('page_profile'),
		"verify_email_link" => $verify_email_link,
	);
	foreach ($dynamic_variables as $dkey => $dvalue) {
		$verify_email_message = str_replace('%%'.$dkey.'%%', $dvalue, $verify_email_message);
	}
	return $verify_email_message;
}


# Default Fields DB Setup
####################################################################################################
add_action('init', 'cjfm_default_fields_setup');
function cjfm_default_fields_setup(){
	global $wpdb;
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	// Insert default form
	$default_form_query = $wpdb->get_row("SELECT * FROM $forms_table WHERE can_remove = 0");
	if(empty($default_form_query)){
		$default_form_data = array(
			'form_name' => 'Default',
			'default_user_role' => get_option('default_role'),
			'can_remove' => 0,
		);
		$form_id = cjfm_insert($forms_table, $default_form_data);
	}else{
		$form_id = $default_form_query->id;
	}

	// Insert default fields
	$default_fields['user_login'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_login',
		'unique_id' => 'user_login',
		'label' => __('Choose Username', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '0',
	);

	$default_fields['user_email'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_email',
		'unique_id' => 'user_email',
		'label' => __('Your email address', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'yes',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '1',
	);

	$default_fields['user_pass'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_pass',
		'unique_id' => 'user_pass',
		'label' => __('Choose a password', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '2',
	);

	$default_fields['user_pass_conf'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_pass_conf',
		'unique_id' => 'user_pass_conf',
		'label' => __('Type password again', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'yes',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '3',
	);

	$default_fields['user_avatar'] = array(
		'form_id' => $form_id,
		'field_type' => 'user_avatar',
		'unique_id' => 'user_avatar',
		'label' => __('Profile Picture', 'cjfm'),
		'description' => '',
		'required' => 'yes',
		'register' => 'no',
		'invitation' => 'no',
		'profile' => 'yes',
		'enabled' => 'yes',
		'options' => 'NA',
		'sort_order' => '4',
	);

	foreach ($default_fields as $key => $df) {
		$default_fields_query = $wpdb->get_results("SELECT * FROM $fields_table WHERE unique_id = '{$key}' AND form_id = '{$form_id}'");
		if(empty($default_fields_query)){
			cjfm_insert($fields_table, $df);
		}
	}

}

# Generate spam protection field
####################################################################################################
function cjfm_spam_protection_field($page = null, $required_text = null){
	$type = cjfm_get_option('spam_protection_type');
	switch ($type) {
		case 'none':
			$return = null;
		break;
		case 'qa':
			$show = 0;
			$return = null;
			if(in_array($page, cjfm_get_option('spam_protection_pages'))){ $show = 1; }
			if($show == 1){
				$return = array(
				    'type' => 'text',
				    'id' => 'spam_protection',
				    'label' => cjfm_get_option('spam_question').' <span class="required">'.$required_text.'</span>',
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'params' => array('placeholder' => cjfm_get_option('spam_answer_placeholder'), 'class' => 'form-control spam-protection-textbox'),
				    'default' => '',
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);
			}
		break;
		case 'recaptcha':

			$show = 0;
			$return = '';

			if(in_array($page, cjfm_get_option('spam_protection_pages'))){ $show = 1; }

			if($show == 1){

				$cjfm_recaptcha_key = cjfm_get_option('recaptcha_public_key');
				$cjfm_recaptcha_theme = cjfm_get_option('recaptcha_theme');

				$recaptcha_id = cjfm_unique_string().rand(100, microtime());
				$recaptcha_id = 'recaptcha_'.strtolower($recaptcha_id);
				$return .= '<div class="form-group"><div id="'.$recaptcha_id.'" class="g-recaptcha" data-id="'.$recaptcha_id.'" data-theme="'.$cjfm_recaptcha_theme.'" data-sitekey="'.$cjfm_recaptcha_key.'"></div></div>';

				$return = array(
				    'type' => 'custom_html',
				    'id' => 'spam_protection',
				    'label' => '',
				    'info' => '',
				    'suffix' => '',
				    'prefix' => '',
				    'default' => $return,
				    'options' => '', // array in case of dropdown, checkbox and radio buttons
				);
			}
		break;
	}
	return $return;
}

# Render reCaptcha theme script
####################################################################################################
add_action('wp_footer', 'cjfm_recaptcha_theme_script');
function cjfm_recaptcha_theme_script(){
	$type = cjfm_get_option('spam_protection_type');
	if($type == 'recaptcha'){
		// echo '<script type="text/javascript">var RecaptchaOptions = {theme : "'.cjfm_get_option('recaptcha_theme').'", hl : "'.cjfm_get_option('recaptcha_language').'"};</script>'."\n";
		// echo '<script type="text/javascript">var RecaptchaOptions = {theme : "'.cjfm_get_option('recaptcha_theme').'", hl : "'.__('zh-TW', 'cjfm').'"};</script>'."\n";
		echo '<script src="https://www.google.com/recaptcha/api.js?hl='.cjfm_get_option('recaptcha_language').'&onload=cjfm_recaptcha_callback&render=explicit"></script>';

		$cjfm_recaptcha_key = cjfm_get_option('recaptcha_public_key');
		$cjfm_recaptcha_theme = cjfm_get_option('recaptcha_theme');

		$script = <<<EOD
<script type="text/javascript">
	var cjfm_recaptcha_callback = function(){
		var gcaptchas = document.getElementsByClassName('g-recaptcha');
		for (var i = gcaptchas.length - 1; i >= 0; i--) {
			grecaptcha.render(gcaptchas[i].id, {
				'sitekey' : '{$cjfm_recaptcha_key}',
				'theme' : '{$cjfm_recaptcha_theme}',
			});
		};
	}
</script>
EOD;

	echo $script;

	}
}

# Process spam protection
####################################################################################################
function cjfm_spam_protection_process($post,  $page){

	$type = cjfm_get_option('spam_protection_type');

	$errors = null;

	switch ($type) {
		case 'none':
			$return = null;
		break;
		case 'qa':
			if(strtolower(@$post['spam_protection']) != strtolower(cjfm_get_option('spam_answer'))){
				$errors[] = __('Invalid answer, please try again.', 'cjfm');
			}
		break;
		case 'recaptcha':
			$recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify?secret='.cjfm_get_option('recaptcha_private_key').'&response='.@$post['g-recaptcha-response'].'&remoteip='.cjfm_current_ip_address();
			$response = wp_remote_post( $recaptcha_verify_url, array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => (isset($recaptcha_post_fields)) ? $recaptcha_post_fields : '',
				'cookies' => array()
			    )
			);

			$recaptcha_response = json_decode($response['body']);

			if($recaptcha_response->success == false){
				$errors[] = __('Verification failed, please verify that you are not a robot.', 'cjfm');
			}


		break;
	}

	$spam_protection_pages_array = cjfm_get_option('spam_protection_pages');
	if(@in_array($page, $spam_protection_pages_array)){
		return $errors;
	}else{
		return null;
	}
}


# Show Custom Profile Fields with WordPress Profile
####################################################################################################
function cjfm_sync_profile_fields($user){
	global $wpdb, $current_user;

	if(is_user_logged_in()){
		$user_info = (isset($_GET['user_id'])) ? cjfm_user_info($_GET['user_id']) : cjfm_user_info($current_user->ID);
		if(!isset($user_info['cjfm_form_id'])){
			update_user_meta($user_info['ID'], 'cjfm_form_id', 1);
			$user_info = cjfm_user_info($current_user->ID);
		}
	}

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	if(current_user_can('manage_options')){
		$fields = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = '{$user_info['cjfm_form_id']}' ORDER BY sort_order ASC");
	}else{
		$fields = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = '{$user_info['cjfm_form_id']}' AND enabled = 'yes' AND profile = 'yes' ORDER BY sort_order ASC");
	}



	$exclude_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber', 'user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf', 'heading', 'custom_html', 'paragraph');
	$text_fields = array('text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode', 'user_avatar', 'facebook_url', 'twitter_url', 'google_plus_url', 'youtube_url', 'vimeo_url');

	$display[] = (cjfm_get_option('profile_sync_heading') != '') ? '<h3>'.cjfm_get_option('profile_sync_heading').'</h3>' : '';

	$display[] = '<table id="custom_user_field_table" class="form-table">';

	foreach ($fields as $key => $field) {
		if(!in_array($field->field_type, $exclude_fields)){
			$display[] = '<tr id="'.$field->unique_id.'_field">';

			if($field->field_type == 'heading'){
				$display[] = '<th><h3>'.$field->label.'</h3></th>';
			}else{
				$display[] = '<th><label for="'.$field->unique_id.'">'.$field->label.'</label></th>';
			}

			$display[] = '<td>';

			if($field->field_type == 'text' || in_array($field->field_type, $text_fields) && $field->unique_id != 'user_avatar'){
				$display[] = '<input type="text" name="'.$field->unique_id.'" id="'.$field->unique_id.'" value="'.get_user_meta($user->ID, $field->unique_id, true).'" class="regular-text" />';
			}

			if($field->field_type == 'user_avatar' || in_array($field->field_type, $text_fields) && $field->unique_id == 'user_avatar'){
				$user_avatar = cjfm_gravatar_url($user->ID, 256);
				if(cjfm_get_option('user_avatar_type') == 'custom' && isset($user_info['user_avatar']) && $user_info['user_avatar'] != ''){
					$user_avatar = get_user_meta( $user->ID, 'user_avatar', true );
				}
				$display[] = '<input type="text" name="'.$field->unique_id.'" id="'.$field->unique_id.'" value="'.$user_avatar.'" class="regular-text" />';
				$display[] = '<p><img src="'.$user_avatar.'" style="width:100px; background:#f9f9f9; border:1px solid #ddd; padding:5px;" /></p>';
			}



			if($field->field_type == 'textarea'){
				$display[] = '<textarea rows="5" cols="30" name="'.$field->unique_id.'" id="'.$field->unique_id.'">'.get_user_meta($user->ID, $field->unique_id, true).'</textarea>';
			}

			if($field->field_type == 'dropdown' || $field->field_type == 'select'){
				$display[] = '<select name="'.$field->unique_id.'" id="'.$field->unique_id.'">';
				$opts = '';
				$opts = explode("\n", $field->options);
				if(is_array($opts)){
					foreach ($opts as $okey => $ovalue) {
						if(trim(get_user_meta($user->ID, $field->unique_id, true)) == strip_tags(trim($ovalue))){
							$display[] = '<option selected value="'.strip_tags($ovalue).'">'.$ovalue.'</option>';
						}else{
							$display[] = '<option value="'.strip_tags($ovalue).'">'.$ovalue.'</option>';
						}
					}
				}
				$display[] = '</select>';
			}

			if($field->field_type == 'radio'){
				$opts = '';
				$opts = explode("\n", $field->options);
				if(is_array($opts)){
					foreach ($opts as $okey => $ovalue) {
						if(trim(get_user_meta($user->ID, $field->unique_id, true)) == strip_tags(trim($ovalue))){
							$display[] = '<label><input checked type="radio" name="'.$field->unique_id.'" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';
						}else{
							$display[] = '<label><input type="radio" name="'.$field->unique_id.'" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';
						}
					}
				}
			}

			if($field->field_type == 'checkbox'){
				$opts = '';
				$opts = explode("\n", $field->options);
				if(is_array($opts)){
					foreach ($opts as $okey => $ovalue) {
						$saved_values = (is_serialized(get_user_meta($user->ID, $field->unique_id, true))) ? unserialize(get_user_meta($user->ID, $field->unique_id, true)) : get_user_meta($user->ID, $field->unique_id, true);
						if(@in_array(strip_tags(trim($ovalue)), $saved_values)){
							$display[] = '<label><input checked type="checkbox" name="'.$field->unique_id.'[]" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';
						}else{
							$display[] = '<label><input type="checkbox" name="'.$field->unique_id.'[]" value="'.strip_tags(trim($ovalue)).'" />&nbsp; &nbsp;'.trim($ovalue).'</label><br />';
						}
					}
				}
			}

			if($field->field_type == 'multidropdown' || $field->field_type == 'multiselect'){
				$display[] = '<select multiple name="'.$field->unique_id.'[]" id="'.$field->unique_id.'">';
				$opts = '';
				$opts = explode("\n", $field->options);
				if(is_array($opts)){
					foreach ($opts as $okey => $ovalue) {
						$saved_values = (is_serialized(get_user_meta($user->ID, $field->unique_id, true))) ? unserialize(get_user_meta($user->ID, $field->unique_id, true)) : get_user_meta($user->ID, $field->unique_id, true);
						if(@in_array(strip_tags(trim($ovalue)), $saved_values)){
							$display[] = '<option selected value="'.strip_tags(trim($ovalue)).'">'.trim($ovalue).'</option>';
						}else{
							$display[] = '<option value="'.strip_tags(trim($ovalue)).'">'.trim($ovalue).'</option>';
						}
					}
				}
				$display[] = '</select>';
			}

			if($field->field_type == 'cjfm_country'){
				$display[] = '<select name="'.$field->unique_id.'" id="'.$field->unique_id.'">';
				$countries_array = cjfm_countries_array();
				if(is_array($countries_array)){
					foreach ($countries_array as $okey => $ovalue) {
						if(get_user_meta($user->ID, $field->unique_id, true) == $okey){
							$display[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
						}else{
							$display[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
						}
					}
				}
				$display[] = '</select>';
			}

			$display[] = ($field->description != '') ? '<br /><span class="description">'.$field->description.'</span>' : '';
			$display[] = '</td>';
			$display[] = '</tr>';
		}
	}

	$display[] = '</table>';
	echo implode('', $display);
}


function cjfm_save_profile_fields($user_id){
	global $wpdb, $current_user;

	if ( !current_user_can( 'edit_user', $user_id ) )
	return FALSE;

	if(is_user_logged_in()){
		$user_info = cjfm_user_info($current_user->ID);
		if(!isset($user_info['cjfm_form_id'])){
			update_user_meta($user_info['ID'], 'cjfm_form_id', 1);
			$user_info = cjfm_user_info($current_user->ID);
		}
	}

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';

	$fields = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = '{$user_info['cjfm_form_id']}' AND enabled = 'yes' AND profile = 'yes' ORDER BY sort_order ASC");

	$exclude_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber', 'user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf', 'custom_html');
	$text_fields = array('user_login', 'user_email', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode', 'user_avatar');

	foreach ($fields as $key => $field) {
		if(!in_array($field->field_type, $exclude_fields)){

			if(is_array($_POST[$field->unique_id])){
				$post_value = serialize($_POST[$field->unique_id]);
			}else{
				$post_value = $_POST[$field->unique_id];
			}

			update_user_meta($user_id, $field->unique_id, $post_value);
		}
	}

}

if(cjfm_get_option('profile_sync') == 'yes'){
	add_action( 'show_user_profile', 'cjfm_sync_profile_fields' );
	add_action( 'edit_user_profile', 'cjfm_sync_profile_fields' );
	add_action( 'personal_options_update', 'cjfm_save_profile_fields' );
	add_action( 'edit_user_profile_update', 'cjfm_save_profile_fields' );
}


# Disable WordPress Admin Bar
####################################################################################################
function cjfm_disable_admin_bar(){
	global $wpdb, $current_user;
	$disable_for = cjfm_get_option('disable_admin_bar_for');
	$user_role = cjfm_user_role($current_user->ID);
	if(is_user_logged_in() && is_array($disable_for)){
		if(in_array($user_role, $disable_for)){
			add_filter('show_admin_bar', '__return_false');
		}
	}
}
add_action('init', 'cjfm_disable_admin_bar');

# WordPress Admin Dashboard Access
####################################################################################################
function cjfm_dashboard_access(){
	global $wpdb, $current_user;
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return;
	}
	if ( FALSE !== strpos( $_SERVER[ 'PHP_SELF' ], 'async-upload.php' ) ) {
		return;
	}
	if(is_user_logged_in() && !current_user_can( 'manage_options' )){
		$dashboard_access = cjfm_get_option('wp_dashboard_access');
		$user_role = cjfm_user_role($current_user->ID);
		if(!in_array($user_role, $dashboard_access)){
			wp_redirect( site_url(), $status = 302 );
			exit;
		}
	}
}
add_action('admin_init', 'cjfm_dashboard_access');

# WordPress Default Page Redirect
####################################################################################################
function cjfm_default_page_redirect(){
	global $wpdb, $current_user;
	$cjfm_current_url = cjfm_current_url();
	$login_url = cjfm_generate_url('page_login');
	$register_url = cjfm_generate_url('page_register');
	$reset_password_url = cjfm_generate_url('page_reset_password');

	if(cjfm_get_option('page_login') != '' && !current_user_can('manage_options')){

		if(strpos($cjfm_current_url, 'wp-signup') > 0){
			wp_redirect( $register_url, $status = 302 );
			exit;
		}
		if(strpos($cjfm_current_url, 'wp-login.php') > 0){
			if(!isset($_GET['action'])){
				wp_redirect( $login_url, $status = 302 );
				exit;
			}else{
				if($_GET['action'] == 'register'){
					wp_redirect( $register_url, $status = 302 );
					exit;
				}
				if($_GET['action'] == 'lostpassword'){
					wp_redirect( $reset_password_url, $status = 302 );
					exit;
				}
			}
		}

	}

}
if(cjfm_get_option('wp_default_page_redirect') == 'yes'){
	add_action('init', 'cjfm_default_page_redirect');
}


# Awaiting approval users handle
####################################################################################################
add_action('init', 'cjfm_awaiting_approvals_handle');
function cjfm_awaiting_approvals_handle(){
	global $wpdb, $current_user;
	if(is_user_logged_in()){
		if(get_user_meta($current_user->ID, 'cjfm_account_approved', true) == '0'){
			wp_logout();
			$location = cjfm_string(site_url()).'cjfm_action=awm';
			wp_redirect( $location, $status = 302 );
			exit;
		}
	}
}

add_action('wp_footer', 'cjfm_awating_approvals_msg');
function cjfm_awating_approvals_msg(){
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'awm'){
		$msg = __("Your account is being reviewed. Please check back later.", 'cjfm');
		echo '<script type="text/javascript">alert("'.$msg.'");</script>';
	}
}


# RESTRICT ACCESS
####################################################################################################
function cjfm_restrict_access(){
	global $cjfm, $wpdb, $post, $wp_query;

	if(!is_user_logged_in()):

		$restrict_site = cjfm_get_option('restrict_site');

		if($restrict_site == 'yes'){
			$login_page = cjfm_get_option('page_login');
			$register_page = cjfm_get_option('page_register');
			$reset_password_page = cjfm_get_option('page_reset_password');
			$logout_page = cjfm_get_option('page_logout');
			if(!is_page($login_page) && !is_page($register_page) && !is_page($reset_password_page) && !is_page($logout_page)){
				$redirect = cjfm_string(get_permalink($login_page)).'redirect_url='.cjfm_current_url();
				wp_redirect($redirect);
				exit;
			}
		}



		$login_page_url = cjfm_string(cjfm_generate_url('page_login')).'redirect='.urlencode(cjfm_current_url('only-url')).'&cjfm_action=stop';
		$r_cats = cjfm_get_option('restrict_categories');
		$r_pages = cjfm_get_option('restrict_pages');
		$r_tags = cjfm_get_option('restrict_tags');
		$r_tax = cjfm_get_option('restrict_taxonomies');

		$r_post_types = cjfm_get_option('restrict_post_types');

		if(!empty($r_cats) && array_sum($r_cats) > 0 && is_category($r_cats) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_cats) && array_sum($r_cats) > 0 && in_category($r_cats) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_pages) && array_sum($r_pages) > 0 && is_page($r_pages) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_tags) && count($r_tags) > 0 && is_tag($r_tags) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		if(!empty($r_tags) && is_single() && has_tag($r_tags) && @$_GET['cjfm_action'] != 'stop'){
			//wp_redirect($login_page_url);
			//exit;
		}

		// Specifically added for woocommerce and other custom post types
		if(!empty($r_post_types) && in_array($post->post_type, $r_post_types)){
			wp_redirect($login_page_url);
			exit;
		}

		if(!empty($r_tax) && count($r_tax) > 0){
			foreach ($r_tax as $key => $val) {
				$exp = explode('~~~~', $val);
				if(has_term( @$exp[0], @$exp[1], $post )){
					wp_redirect($login_page_url);
					exit;
				}
			}
		}


		add_filter('the_content', 'cjfm_hide_restricted_post_content');

	endif;

	if(cjfm_get_option('password_strength_meter') == 'no'){
		echo '<style type="text/css">';
		echo '.cjfm-pw-strength{display:none}';
		echo '</style>';
	}


}
add_action('wp_head', 'cjfm_restrict_access');


function cjfm_hide_restricted_post_content($content){
	global $cjfm, $wpdb, $post;

	$login_page_url = cjfm_generate_url('page_login');

	$r_cats = cjfm_get_option('restrict_categories');
	$r_pages = cjfm_get_option('restrict_pages');
	$r_tags = cjfm_get_option('restrict_tags');
	$r_tax = cjfm_get_option('restrict_taxonomies');

	if(!empty($r_tax) && count($r_tax) > 0){
		foreach ($r_tax as $key => $val) {
			$exp = explode('~~~~', $val);
			$rterms[] = @$exp[0];
			$rtaxonomies[] = @$exp[1];
		}
	}

	$login_link = '<a href="'.cjfm_string(cjfm_generate_url('page_login')).'redirect='.urlencode(cjfm_current_url()).'&cjfm_action=stop">Login</a>';
	$register_link = '<a href="'.cjfm_string(cjfm_generate_url('page_register')).'redirect='.urlencode(cjfm_current_url()).'&cjfm_action=stop">Register</a>';

	$message = cjfm_get_option('restricted_login_message');
	$message = str_replace('%%login_link%%', $login_link, $message);
	$message = str_replace('%%register_link%%', $register_link, $message);

	if(!is_null($post)){
		if(!empty($r_cats) && array_sum($r_cats) > 0 && in_category($r_cats, $post->ID)){
			$output = $message;
		}elseif(!empty($r_tags) && count($r_tags) > 0 && has_tag($r_tags, $post->ID)){
			$output = $message;
		}elseif(!empty($r_tax) && count($r_tax) > 0 && @has_term( $rterms, $rtaxonomies, $post->ID )){
			$output = $message;
		}elseif(!empty($r_pages) && array_sum($r_pages) > 0 && is_page($r_pages, $post->ID)){
			$output = $message;
		}else{
			$output = $content;
		}
		return $output;
	}
}


function cjfm_modalbox_forms(){

	global $wpdb, $post, $current_user;

	$login_page = cjfm_get_option('page_login');
	$register_page = cjfm_get_option('page_register');

	if(!is_page($login_page) && !is_page($register_page)){

		$login_form_class = isset($_POST['do_login']) ? 'show' : '';
		$register_form_class = isset($_POST['do_create_account']) ? 'show' : '';

		echo '<div style="display:none;" class="cjfm-modalbox '.$login_form_class.' '.$register_form_class.'"></div>';

		echo '<div id="cjfm-modalbox-login-form" class="'.$login_form_class.'" style="display:none;">';
		if(cjfm_get_option('modalbox_login_form_heading') != ''){
			echo '<h3>'.cjfm_get_option('modalbox_login_form_heading').'</h3>';
		}
		echo '<div class="cjfm-modalbox-login-content">';
		echo do_shortcode(cjfm_get_option('modalbox_login_form_content'));
		echo '</div>';
		echo '<a href="#close" class="cjfm-close-modalbox">x</a>';
		echo '</div>';

		echo '<div id="cjfm-modalbox-register-form" class="'.$register_form_class.'" style="display:none;">';
		if(cjfm_get_option('modalbox_register_form_heading') != ''){
			echo '<h3>'.cjfm_get_option('modalbox_register_form_heading').'</h3>';
		}
		echo '<div class="cjfm-modalbox-register-content">';
		echo do_shortcode(cjfm_get_option('modalbox_register_form_content'));
		echo '</div>';
		echo '<a href="#close" class="cjfm-close-modalbox">x</a>';
		echo '</div>';

	}

}
if(cjfm_get_option('modalbox_forms') == 'yes'){
	add_action('wp_footer', 'cjfm_modalbox_forms');
}


function cjfm_register_nav_menus(){
	$nav_menus = cjfm_item_vars('nav_menus');
	register_nav_menus($nav_menus);
}
add_action('init', 'cjfm_register_nav_menus');


function cjfm_navigation_menu(){
	global $current_user;
	if(is_user_logged_in()){
		return 'cjfm_users_menu';
	}else{
		return 'cjfm_visitors_menu';
	}
}


function cjfm_extend_maintenance_mode_head(){
	$cjfm_css_url = cjfm_item_path('item_url').'/assets/css/cjfm.css';
	echo '<link href="'.$cjfm_css_url.'" rel="stylesheet" />';
}
add_action('cjfm_maintenance_mode_head', 'cjfm_extend_maintenance_mode_head');



# Parse .csv data
####################################################################################################
function cjfm_parse_csv($file_url, $return = 'data'){
	$class = cjfm_item_path('item_dir').'/options/inc';
	require_once(sprintf('%s/parsecsv.lib.php', $class));

	# create new parseCSV object.
	$csv = new parseCSV();

	/*$csv->delimiter = "{$delimiter}";   # tab delimited
	$csv->parse('_books.csv');*/

	# Parse '_books.csv' using automatic delimiter detection...
	$csv->auto($file_url);

	if($return == 'data'){
		return $csv->data;
	}else{
		return $csv;
	}
}


function cjfm_create_csv($filename = null, $headings_array = null, $row_array = null){
	$output_file = (is_null($filename)) ? date('Y-m-d-H-i-s') : $filename;

	if(is_null($row_array)) { return; }

	// output headers so that the file is downloaded rather than displayed
	header ("Content-type: Application/CSV");
	header('Content-Disposition: attachment; filename='.$output_file.'.csv');

	// create a file pointer connected to the output stream
	$output = @fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, $headings_array);

	// loop over the rows, outputting them
	foreach ($row_array as $key => $value) {
		fputcsv($output, $value);
	}

	fclose($output);
	exit;
}


// Replace WordPress Gravatar with user uploaded photo
function cjfm_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
	$user = false;
    if ( is_numeric( $id_or_email ) ) {
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
        } elseif ( is_object( $id_or_email ) ) {
            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by( 'id' , $id );
            }
    } else {
        $user = get_user_by( 'email', $id_or_email );
    }
    if ( $user && is_object( $user ) ){
    	$custom_avatar = cjfm_user_info($user->data->ID, 'user_avatar');
    	if ($custom_avatar){
    		$return = '<img src="'.$custom_avatar.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" class="avatar avatar-'.$size.' photo" />';
    	}elseif ($avatar) {
    		$return = $avatar;
    	}else{
    		$return = '<img src="'.$default.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" class="avatar avatar-'.$size.' photo" />';
    	}
    	return $return;
    }else{
    	return $avatar;
    }
}
if(cjfm_get_option('user_avatar_type') == 'custom'){
	add_filter('get_avatar', 'cjfm_gravatar_filter', 10, 5);
}


function cjfm_custom_form_fields($form_id, $form_type, $args = array()){
	global $wpdb, $current_user;

	// predefined data
	$predefined_data = $args['predefined_data'];
	$has_errors = (isset($args['has_errors'])) ? $args['has_errors'] : array();
	$required_text = (isset($args['required_text'])) ? $args['required_text'] : __('*', 'cjfm');

	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';

	$form_query = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = '{$form_id}'");
	$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = '{$form_id}' AND enabled = 'yes' AND '{$form_type}' = 'yes' ORDER BY sort_order ASC");

	$cjfm_password_fields = cjfm_get_option('register_password_type');
	$form_role = $form_query->default_user_role;

	$current_user_info = (is_user_logged_in()) ? cjfm_user_info($current_user->ID) : null;


	if(empty($form_fields_query) && !is_null($form_query)){
		// Insert default fields
		$new_form_fields['user_login'] = array(
			'form_id' => $form_id,
			'field_type' => 'user_login',
			'unique_id' => 'user_login',
			'label' => __('Choose Username', 'cjfm'),
			'description' => '',
			'required' => 'yes',
			'register' => 'yes',
			'invitation' => 'no',
			'profile' => 'yes',
			'enabled' => 'yes',
			'options' => 'NA',
			'sort_order' => '0',
		);

		$new_form_fields['user_email'] = array(
			'form_id' => $form_id,
			'field_type' => 'user_email',
			'unique_id' => 'user_email',
			'label' => __('Your email address', 'cjfm'),
			'description' => '',
			'required' => 'yes',
			'register' => 'yes',
			'invitation' => 'yes',
			'profile' => 'yes',
			'enabled' => 'yes',
			'options' => 'NA',
			'sort_order' => '1',
		);

		$new_form_fields['user_pass'] = array(
			'form_id' => $form_id,
			'field_type' => 'user_pass',
			'unique_id' => 'user_pass',
			'label' => __('Choose a password', 'cjfm'),
			'description' => '',
			'required' => 'yes',
			'register' => 'yes',
			'invitation' => 'no',
			'profile' => 'yes',
			'enabled' => 'yes',
			'options' => 'NA',
			'sort_order' => '2',
		);

		$new_form_fields['user_pass_conf'] = array(
			'form_id' => $form_id,
			'field_type' => 'user_pass_conf',
			'unique_id' => 'user_pass_conf',
			'label' => __('Type password again', 'cjfm'),
			'description' => '',
			'required' => 'yes',
			'register' => 'yes',
			'invitation' => 'no',
			'profile' => 'yes',
			'enabled' => 'yes',
			'options' => 'NA',
			'sort_order' => '3',
		);

		$new_form_fields['user_avatar'] = array(
			'form_id' => $form_id,
			'field_type' => 'user_avatar',
			'unique_id' => 'user_avatar',
			'label' => __('Profile Picture', 'cjfm'),
			'description' => '',
			'required' => 'yes',
			'register' => 'no',
			'invitation' => 'no',
			'profile' => 'yes',
			'enabled' => 'yes',
			'options' => 'NA',
			'sort_order' => '4',
		);

		foreach ($new_form_fields as $key => $df) {
			//cjfm_insert($fields_table, $df);
		}
		$form_query = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = {$form_id}");
		$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = {$form_id} AND enabled = 'yes' AND {$form_type} = 'yes' ORDER BY sort_order ASC");
	}

	if(!empty($form_fields_query)){

		foreach ($form_fields_query as $key => $field) {

			$text_fields = array('user_login', 'text', 'first_name', 'last_name', 'display_name', 'user_url', 'aim', 'yim', 'jabber', 'cjfm_address1', 'cjfm_address2', 'cjfm_city', 'cjfm_state', 'cjfm_zipcode');
			$email_fields = array('user_email');
			$password_fields = array('user_pass', 'user_pass_conf');
			$file_fields = array('user_avatar');
			$textarea_fields = array('textarea', 'description');
			$country_fields = array('cjfm_country');
			$social_fields = array('facebook_url', 'twitter_url', 'google_plus_url', 'youtube_url', 'vimeo_url');
			$html_fields = array('heading', 'paragraph', 'custom_html');
			$hidden_fields = array('hidden');
			$upload_fields = array('file');

			$field_suffix = '';
			$field_prefix = '';


			if(in_array($field->field_type, $text_fields)){
				$field_type = 'text';
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $email_fields)){
				$field_type = 'email';
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $password_fields)){
				$field_type = ($cjfm_password_fields == 'enable') ? 'password' : 'none';
				$field_type = ($form_type == 'profile') ? 'password' : $field_type;
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $textarea_fields)){
				$field_type = 'textarea';
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $country_fields)){
				$field_type = 'select';
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $file_fields)){
				$field_type = 'upload';
				$field_default = '';
				$user_avatar = (is_user_logged_in() && isset($current_user_info['user_avatar']) && $current_user_info['user_avatar'] != '') ? $current_user_info['user_avatar'] : cjfm_gravatar_url($current_user_info['ID'], 125);
				$field_prefix = '<div class="cjfm-user-avatar"><img src="'.$user_avatar.'" width="100" /></div>';
			}elseif(in_array($field->field_type, $upload_fields)){
				$field_type = 'upload';
				$field_default = '';
				$file_url = (is_user_logged_in() && isset($current_user_info[$field->unique_id]) && $current_user_info[$field->unique_id] != '') ? $current_user_info[$field->unique_id] : '';
				if($file_url != ''){
					$field_suffix = '<a target="_blank" class="cjfm-download-url" href="'.$file_url.'">'.__('Download', 'cjfm').'</a>';
				}
			}elseif(in_array($field->field_type, $social_fields)){
				$field_type = 'text';
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $text_fields)){
				$field_type = 'date';
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}elseif(in_array($field->field_type, $html_fields)){
				$field_type = $field->field_type;
				if($field->field_type == 'heading'){
					$field_default = $field->label;
				}elseif($field->field_type == 'paragraph'){
					$field_default = $field->description;
				}elseif($field->field_type == 'custom_html'){
					$field_default = $field->description;
				}else{
					$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
					$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
				}
			}elseif(in_array($field->field_type, $hidden_fields)){
				$field_type = $field->field_type;
				$field_default = $field->description;
			}else{
				$field_type = $field->field_type;
				$field_default = (in_array($field->unique_id, array_keys($predefined_data))) ? $predefined_data[$field->unique_id] : '';
				$field_readonly = (in_array($field->unique_id, array_keys($predefined_data))) ? true : false;
			}

			if($field->options != 'NA'){
				$fopts = explode("\n", $field->options);
				$field_options = null;
				foreach ($fopts as $okey => $ovalue) {
					$field_options[trim($ovalue)] = trim($ovalue);
				}
			}elseif($field->field_type == 'cjfm_country'){
				$field_options = cjfm_countries_array();
			}else{
				$field_options = '';
			}

			if(cjfm_get_option('password_strength_meter') == 'no'){
				$password_class = '';
				$password_meter = '';
			}else{
				$password_class = ($field->field_type == 'user_pass' || $field->field_type == 'user_pass_conf') ? ' cjfm-pw ' : '';
				$password_meter = ($field->field_type == 'user_pass' || $field->field_type == 'user_pass_conf') ? '<span class="cjfm-pw-strength"></span>' : '';
			}



			if(is_user_logged_in()){

				$exclude_fields_default_value = array('user_pass', 'user_pass_conf', 'heading', 'paragraph', 'custom_html');

				if($field->unique_id == 'user_login'){
					$field_type = 'text-readonly';
				}

				// Create user shortcod mods..
				if(isset($predefined_data['allow_username_change']) && $predefined_data['allow_username_change'] == 'yes'){
					if($field->field_type == 'user_login'){
						$field_type = 'text';
						array_push($exclude_fields_default_value, 'user_login');
					}
					if($field->field_type == 'user_email'){
						array_push($exclude_fields_default_value, 'user_email');
					}
				}

				if(!in_array($field->unique_id, $exclude_fields_default_value) && !in_array($field->field_type, $exclude_fields_default_value)){
					$current_user_info = cjfm_user_info($current_user->ID);
					$field_default = (isset($current_user_info[$field->unique_id])) ? $current_user_info[$field->unique_id] : '';
				}
				if($field->unique_id == 'user_pass' || $field->unique_id == 'user_pass_conf'){
					$field_required = false;
					$required =  null;
				}else{
					$field_required = ($field->required == 'yes') ? true : false;
					$required =  ($field->required == 'yes') ? '<span class="required">'.$required_text.'</span>' : null;
				}

				// Create user shortcode mods..
				if(isset($predefined_data['allow_username_change']) && $predefined_data['allow_username_change'] == 'yes' && $field->unique_id == 'user_pass' || $field->unique_id == 'user_pass_conf'){
					$required = true;
					$required = '<span class="required">'.$required_text.'</span>';
				}

			}else{
				$field_required = ($field->required == 'yes') ? true : false;
				$required =  ($field->required == 'yes') ? '<span class="required">'.$required_text.'</span>' : null;
			}

			$field_has_errors = (in_array($field->unique_id, $has_errors)) ? ' has-error ' : '';

			// fix user avatar required
			if($field->unique_id == 'user_avatar'){
				$field_required = false;
				if(isset($_FILES['user_avatar']) && $_FILES['user_avatar']['name'] == ''){
					$field_has_errors = 'has-error';
				}
			}

			if($field->unique_id == 'user_avatar' && isset($predefined_data['user_avatar'])){
				$field_type = 'hidden';
				$field_default = $predefined_data['user_avatar'];
				$field_required = false;
				$field_suffix = '<div class="cjfm-user-avatar"><img src="'.$predefined_data['user_avatar'].'" /></div>';
			}

			$placeholder = cjfm_get_field_meta($field->id, 'placeholder_text');
			$field_icon = cjfm_get_field_meta($field->id, 'field_icon');

			if($field_icon != ''){
				$field_suffix .= '<i class="fa '.$field_icon.'"></i>';
			}

			$form_fields[$field->unique_id] = array(
			    'type' => $field_type,
			    'id' => $field->unique_id,
			    'label' => $field->label.' '.$required.$password_meter,
			    'info' => $field->description,
			    'suffix' => $field_suffix,
			    'prefix' => $field_prefix,
			    'readonly' => (isset($field_readonly)) ? $field_readonly : '',
			    'required' => $field_required,
			    'class' => $password_class.$field_has_errors,
			    'params' => array('class' => 'form-control form-type-'.$form_type.' '.$field->unique_id, 'placeholder' => $placeholder),
			    'default' => cjfm_post_default($field->unique_id, $field_default),
			    'options' => $field_options, // array in case of dropdown, checkbox and radio buttons
			);



			if(cjfm_get_option('user_avatar_type') != 'custom'){
				unset($form_fields['user_avatar']);
			}
		}

		$extended_fields_params = array(
			'required_text' => $required_text,
			'form_type' => $form_type,
		);

		$form_fields = apply_filters( 'extend_custom_fields', $form_fields, $extended_fields_params );

		$form_fields['spam_protection'] = cjfm_spam_protection_field($form_type, $required_text);

		$form_fields['user_role'] = array(
		    'type' => 'hidden',
		    'id' => 'cjfm_user_role',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'class' => '',
		    'default' => $form_role,
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		);

		$form_fields['form_id'] = array(
		    'type' => 'hidden',
		    'id' => 'cjfm_form_id',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'class' => '',
		    'default' => $form_id,
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		);

		if(isset($_GET['cjfm-social-profile']) && $_GET['cjfm-social-profile'] != '' && isset($_GET['data'])){
			$social_data = unserialize(base64_decode($_GET['data']));
			$form_fields['social_service_id'] = array(
			    'type' => 'hidden',
			    'id' => 'cjfm_'.$_GET['cjfm-social-profile'].'_id',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'class' => '',
			    'default' => $social_data['identifier'],
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			);
			$form_fields['social_service_profile'] = array(
			    'type' => 'textarea',
			    'id' => 'cjfm_'.$_GET['cjfm-social-profile'].'_profile',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'class' => '',
			    'params' => array('style' => 'display:none;'),
			    'default' => $_GET['data'],
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			);
		}

		$form_fields['redirect'] = array(
		    'type' => 'hidden',
		    'id' => 'redirect_url',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'class' => '',
		    'default' => (isset($args['redirect_url'])) ? $args['redirect_url'] : cjfm_current_url(),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		);

		if(isset($_GET['invitation_token'])){
			$form_fields['invited'] = array(
			    'type' => 'hidden',
			    'id' => 'cjfm_invited_on',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'class' => '',
			    'default' => date('Y-m-d H:i:s'),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			);
		}


		$form_fields['submit'] = array(
		    'type' => 'submit',
		    'id' => $args['button_name'],
		    'label' => $args['button_text'],
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'class' => $args['button_class'],
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		);

		return $form_fields;
	}else{
		return null;
	}
}





// Process invitation registration form
function cjfm_process_registration_form_invitations($form_id){
	global $wpdb, $current_user;

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';

	if ( empty($_POST) || !@wp_verify_nonce($_POST['cjfm_do_register_nonce'], 'cjfm_do_register') ){
	   $process_form = 'false';
	}else{
	   $process_form = 'true';
	}

	if($process_form == 'false'){
		$errors[] = __('You are not authorised to submit this form.', 'cjfm');
		return cjfm_show_message('error', implode('<br>', $errors));
	}

	$register_page = cjfm_get_option('page_register');
	$register_page_url = get_permalink(cjfm_get_option('page_register'));

	$errors = null;
	$field_has_errors = array();
	$success = null;

	$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = {$form_id} AND enabled = 'yes' AND invitation = 'yes' ORDER BY sort_order ASC");
	// check required fields
	if(!is_null($form_fields_query)){
		foreach ($form_fields_query as $key => $field) {
			if($field->required == 'yes'){
				if(!isset($_POST[$field->unique_id]) || $_POST[$field->unique_id] == ''){
					if($field->unique_id != 'user_avatar'){
						$errors['missing'] = __('Missing required fields.', 'cjfm');
						$field_has_errors[] = $field->unique_id;
						$required_fields[] = $field->unique_id;
					}
					if($field->unique_id == 'user_avatar'){
						if($_FILES['user_avatar']['name'] == ''){
							$errors['user_avatar'] = __('You must choose your profile picture.', 'cjfm');
						}
					}
				}
			}
		}
	}

	// check email
	if(!is_email($_POST['user_email'])){
		$errors['email'] = __('Email address invalid, please check and try again.', 'cjfm');
		$field_has_errors[] = 'user_email';
	}elseif(email_exists( $_POST['user_email'] )){
		$errors['email'] = __('Email address already registered.', 'cjfm');
		$field_has_errors[] = 'user_email';
	}

	if(is_null($errors)){

		// Check invitation request
		$invitation_check = $wpdb->get_row("SELECT * FROM $invitations_table WHERE user_email = '{$_POST['user_email']}'");
		if(!is_null($invitation_check) && $invitation_check->invited == 0){
			$location = cjfm_string(cjfm_current_url()).'invitation=exists&show=register';
			wp_redirect( $location );
			exit;
		}
		if(!is_null($invitation_check) && $invitation_check->invited == 1){

			$invitation_link = cjfm_string(cjfm_generate_url('page_register')).'invitation_token='.$invitation_check->invitation_key;

			$email_message = cjfm_parse_email('invitation_email_message', cjfm_user_info(get_option('admin_email')));
			$email_message = str_replace('%%invitation_link%%', $invitation_link, $email_message);

			$email_data = array(
				'to' => $invitation_check->user_email,
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => cjfm_get_option('invitation_email_subject'),
				'message' => $email_message,
			);
			cjfm_email($email_data);
			$location = cjfm_string(cjfm_current_url()).'invitation=approved&show=register';
			wp_redirect( $location );
			exit;
		}
		if(!is_null($invitation_check) && $invitation_check->invited == 2){
			$location = cjfm_string(cjfm_current_url()).'invitation=declined&show=register';
			wp_redirect( $location );
			exit;
		}

		// Handle user avatar uploads
		if(is_null($errors) && cjfm_get_option('user_avatar_type') == 'custom'){
			if(isset($_FILES) && $_FILES['user_avatar']['error'] == ''){
				$user_avatar_url = cjfm_file_upload('user_avatar', null, null, cjfm_get_option('user_avatar_filetypes'), 'guid', cjfm_get_option('user_avatar_filesize'));
				if(!is_array($user_avatar_url)){
					$usermeta['user_avatar'] = $user_avatar_url;
				}else{
					$errors = $user_avatar_url;
					$fields_has_errors[] = 'user_avatar';
				}
			}
		}

		$user_temp_data = $_POST;
		if(cjfm_get_option('user_avatar_type') == 'custom'){
			$user_temp_data['user_avatar'] = $usermeta['user_avatar'];
		}

		$invitation_key = base64_encode(serialize(array($_POST['user_email'], cjfm_unique_string())));
		$invitation_data = array(
			'user_email' => $_POST['user_email'],
			'invitation_key' => $invitation_key,
			'user_data' => serialize($user_temp_data),
			'dated' => date('Y-m-d H:i:s'),
		);
		cjfm_insert($invitations_table, $invitation_data);

		// New invitation notification to admin
		$admin_invitiation_notification_message = __('<p>Dear Admin,</p>', 'cjfm');
		$admin_invitiation_notification_message .= sprintf(__('<p>New invitation request on your website %s</p>', 'cjfm'), get_bloginfo('name'));
		$admin_invitiation_notification_message .= sprintf(__('<a href="%s">View Invitation</a>', 'cjfm'), cjfm_callback_url('cjfm_invitations'));
		$admin_invitation_email_data = array(
			'to' => cjfm_get_option('admin_email'),
			'from_name' => cjfm_get_option('from_name'),
			'from_email' => cjfm_get_option('from_email'),
			'subject' => sprintf(__('[%s] New invitation request', 'cjfm'), get_bloginfo('name')),
			'message' => $admin_invitiation_notification_message,
		);
		cjfm_email($admin_invitation_email_data);

		$location = cjfm_string(cjfm_current_url()).'invitation=sent';
		wp_redirect( $location );
		exit;

	}else{
		//return cjfm_show_message('error', implode('<br>', $errors));
		$return['errors'] = $errors;
		$return['field_has_errors'] = $field_has_errors;
		return $return;
	}

}

// Process registration form normal
function cjfm_process_registration_form_normal($form_id, $redirect){
	global $wpdb, $current_user;

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';
	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';

	$form_info = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = '{$form_id}'");

	$register_page = cjfm_get_option('page_register');
	$register_page_url = get_permalink(cjfm_get_option('page_register'));
	$must_verify_email = cjfm_get_option('verify_email_address');


	if ( empty($_POST) || !@wp_verify_nonce($_POST['cjfm_do_register_nonce'], 'cjfm_do_register') ){
	   $process_form = 'false';
	}else{
	   $process_form = 'true';
	}

	if($process_form == 'false'){
		$errors[] = __('You are not authorised to submit this form.', 'cjfm');
		return cjfm_show_message('error', implode('<br>', $errors));
	}

	$errors = null;
	$field_has_errors = array();
	$success = null;

	// Spam Protection
	$errors = cjfm_spam_protection_process($_POST, 'register');

	$html_fields = array('heading', 'paragraph', 'custom_html');

	if(cjfm_get_option('register_password_type') == 'disable'){
		$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = {$form_id} AND unique_id != 'user_pass' AND unique_id != 'user_pass_conf' AND enabled = 'yes' AND register = 'yes' ORDER BY sort_order ASC");
	}else{
		$form_fields_query = $wpdb->get_results("SELECT * FROM $fields_table WHERE form_id = {$form_id} AND enabled = 'yes' AND register = 'yes' ORDER BY sort_order ASC");
	}

	// check required fields
	$required_fields = array();
	if(!is_null($form_fields_query)){
		foreach ($form_fields_query as $key => $field) {
			if($field->required == 'yes'){
				if(!in_array($field->field_type, $html_fields)){
					if(!isset($_POST[$field->unique_id]) && @$_POST[$field->unique_id] == ''){
						if($field->unique_id != 'user_avatar'){
							$errors['missing'] = __('Missing required fields.', 'cjfm');
							$field_has_errors[] = $field->unique_id;
							$required_fields[] = $field->unique_id;
						}
						if($field->unique_id == 'user_avatar'){
							if($_FILES['user_avatar']['name'] == ''){
								$errors['user_avatar'] = __('You must choose your profile picture.', 'cjfm');
							}
						}
					}
				}
			}
		}
	}

	// auto generate username
	$_POST['user_login'] = (isset($_POST['user_login'])) ? $_POST['user_login'] : cjfm_create_unique_username($_POST['user_email']);

	// Username Checks
	if(!validate_username( $_POST['user_login'] )){
		$errors[] = __('Username field is invalid.', 'cjfm');
		$field_has_errors[] = 'user_login';
	}elseif(username_exists( $_POST['user_login'] )){
		$errors[] = sprintf(__('Username already registered, try another one. (%s is available.)', 'cjfm'), cjfm_create_unique_username($_POST['user_login']));
		$field_has_errors[] = 'user_login';
	}

	// Email Checks
	if(!is_email($_POST['user_email'])){
		$errors[] = __('Email address invalid, please check and try again.', 'cjfm');
		$field_has_errors[] = 'user_email';
	}elseif(email_exists( $_POST['user_email'] )){
		$errors[] = __('Email address already registered.', 'cjfm');
		$field_has_errors[] = 'user_email';
	}

	// Password Checks
	if(cjfm_get_option('register_password_type') != 'disable'){
		if(strlen($_POST['user_pass']) < cjfm_get_option('password_length')){
			$errors[] = sprintf(__('Password must be %d characters long.', 'cjfm'), cjfm_get_option('password_length'));
			$field_has_errors[] = 'user_pass';
		}elseif($_POST['user_pass'] != $_POST['user_pass_conf']){
			$errors[] = __('Password and Confirm password field does not match.', 'cjfm');
			$field_has_errors[] = 'user_pass';
			$field_has_errors[] = 'user_pass_conf';
		}
	}

	// Handle user avatar uploads
	if(is_null($errors) && cjfm_get_option('user_avatar_type') == 'custom'){
		if(isset($_FILES['user_avatar']) && $_FILES['user_avatar']['error'] == ''){
			$user_avatar_url = cjfm_file_upload('user_avatar', null, null, cjfm_get_option('user_avatar_filetypes'), 'guid', cjfm_get_option('user_avatar_filesize'));
			if(!is_array($user_avatar_url)){
				$usermeta['user_avatar'] = $user_avatar_url;
			}else{
				$errors = $user_avatar_url;
				$fields_has_errors[] = 'user_avatar';
			}
		}
	}

	if(is_null($errors)){
		$filter_errors = apply_filters('registration_errors', $_POST);
		if(isset($filter_errors['errors'])){
			$errors = $filter_errors['errors'];
			$error_fields = array_keys($errors);
			$field_has_errors = null;
			foreach ($error_fields as $key => $value) {
				$field_has_errors[] = $value;
			}
		}
	}

	if(!is_null($errors)){
		$return['errors'] = $errors;
		$return['field_has_errors'] = $field_has_errors;
		return $return;
	}else{
		$user_password = (isset($_POST['user_pass'])) ? $_POST['user_pass'] : wp_generate_password(cjfm_get_option('password_length'), false, false) ;
		// Prepare userdata for users table
		$user_data['user_login'] = $_POST['user_login'];
		$user_data['user_email'] = $_POST['user_email'];
		$user_data['user_pass'] = $user_password;
		$user_data['role'] = $form_info->default_user_role;
		$wordpress_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber');
		$user_data_fields = array('user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf');
		foreach ($wordpress_fields as $wkey => $wvalue) {
			$user_data[$wvalue] = @$$wvalue;
		}
		$user_data['nickname'] = @$display_name;

		// Prepare usermeta for usermeta table
		$usermeta['cjfm_user_salt'] = base64_encode($user_password);


		// Must verify email address process
		if($must_verify_email == 'enable' && cjfm_get_option('register_type') == 'normal'){

			$verify_email_activation_key = sha1(cjfm_unique_string());

			unset($_POST['cjfm_process_registration']);
			unset($_POST['cjfm_do_register_nonce']);

			$user_temp_data = $_POST;
			if(cjfm_get_option('user_avatar_type') == 'custom'){
				$user_temp_data['user_avatar'] = $usermeta['user_avatar'];

			}

			$user_signup_temp_data = array(
				'user_email' => $_POST['user_email'],
				'activation_key' => $verify_email_activation_key,
				'user_data' => serialize($user_temp_data),
				'dated' => date('Y-m-d H:i:s'),
			);
			cjfm_insert($user_signup_temp_table, $user_signup_temp_data);

			$verify_email_link = cjfm_string(cjfm_current_url('only')).'cjfm_verify='.$_POST['user_email'].'&key='.$verify_email_activation_key;
			$verify_email_message = cjfm_parse_verification_email($_POST, $verify_email_link);
			$verify_email_data = array(
				'to' => $_POST['user_email'],
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => cjfm_get_option('verify_email_subject'),
				'message' => $verify_email_message,
			);
			cjfm_email($verify_email_data);
			$location = cjfm_string(cjfm_current_url('only')).'confirmation-mail=sent&show=register';
			wp_redirect( $location );
			exit;
		}else{
			$new_user_id = wp_insert_user( $user_data );
		}



		foreach ($_POST as $key => $value) {
			if($key != 'cjfm_do_register_nonce' && $key != 'cjfm_process_registration' && $key != 'redirect_url' && !in_array($key, $user_data_fields)){
				$usermeta[$key] = $value;
				update_user_meta($new_user_id, $key, $value);
			}
		}

		foreach ($usermeta as $key => $value) {
			update_user_meta($new_user_id, $key, $value);
		}

		if(isset($_POST['cjfm_user_created_by'])){
			update_user_meta($new_user_id, 'cjfm_user_created_by', $_POST['cjfm_user_created_by']);
		}

		update_user_meta($new_user_id, 'cjfm_last_login', time());
		update_user_meta($new_user_id, 'cjfm_login_ip', cjfm_current_ip_address());

		$new_user_info = cjfm_user_info($new_user_id);

		$new_activation_key = sha1($new_user_info['user_login'].'-'.cjfm_unique_string());
		update_user_meta( $new_user_id, 'cjfm_reset_password_key', $new_activation_key);

		// Do WordPress user_register action
		do_action('user_register', $new_user_id);
		do_action('user_registered', $new_user_id);

		if(cjfm_get_option('register_type') == 'approvals'){
			update_user_meta($new_user_id, 'cjfm_account_approved', 0);
		}

		// Send new user email to admin
		cjfm_send_new_user_email_to_admin($new_user_id, $form_id);

		// Send registration email.
		$new_user_info = cjfm_user_info($new_user_id);
		if(cjfm_get_option('register_type') == 'approvals'){

			// Admin Email
			$admin_email_data = array(
				'to' => cjfm_get_option( 'admin_email' ),
				'from_name' => get_bloginfo( 'name' ),
				'from_email' => get_option( 'admin_email' ),
				'subject' => cjfm_get_option('awaiting_approval_subject_admin'),
				'message' => cjfm_parse_email('awaiting_approval_message_admin', $new_user_info),
			);
			cjfm_email($admin_email_data);

			$email_subject = cjfm_get_option('awaiting_approval_subject');
			$email_message = cjfm_parse_email('awaiting_approval_message', $new_user_info);

		}else{
			$email_subject = cjfm_get_option('welcome_email_subject');
			$email_message = cjfm_parse_email('welcome_email_message', $new_user_info);
		}

		$email_data = array(
			'to' => $_POST['user_email'],
			'from_name' => cjfm_get_option('from_name'),
			'from_email' => cjfm_get_option('from_email'),
			'subject' => $email_subject,
			'message' => $email_message,
		);
		cjfm_email($email_data);

		$wpdb->query("DELETE FROM $invitations_table WHERE user_email = '{$_POST['user_email']}'");

		// Login new user.
		$creds = array();
		$creds['user_login'] = $_POST['user_login'];
		$creds['user_password'] = $user_password;
		$creds['remember'] = true;
		$user = wp_signon( $creds, is_ssl() );
		if ( !is_wp_error($user) ){
			update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($_POST['user_pass']));
			update_user_meta($user->ID, 'cjfm_last_login', time());
			update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());

			$user_info = cjfm_user_info($user->ID);
			do_action( 'wp_login', $user_login );
			do_action('cjfm_login_done', $user_info);
			do_action('cjfm_registeration_done', $user_info);

			wp_redirect( $redirect );
			exit;
		}


	}
}

add_action('init', 'cjfm_process_email_verification');
function cjfm_process_email_verification($redirect = null, $form_id = null){
	global $wpdb, $current_user;

	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$invitations_table = $wpdb->prefix.'cjfm_invitations';
	$user_signup_temp_table = $wpdb->prefix.'cjfm_temp_users';

	if(isset($_GET['cjfm_verify']) && isset($_GET['key'])){

		$check_user_email =  $_GET['cjfm_verify'];
		$check_user_key =  $_GET['key'];
		$check_key = $wpdb->get_row("SELECT * FROM $user_signup_temp_table WHERE user_email = '{$check_user_email}' AND activation_key = '{$check_user_key}'");

		$temp_user_data = (is_serialized($check_key->user_data)) ? unserialize($check_key->user_data) : $check_key->user_data;

		$form_info = $wpdb->get_row("SELECT * FROM $forms_table WHERE id = '{$temp_user_data['cjfm_form_id']}'");

		$redirect = $temp_user_data['redirect_url'];

		$register_page = cjfm_get_option('page_register');
		$register_page_url = get_permalink(cjfm_get_option('page_register'));
		$must_verify_email = cjfm_get_option('verify_email_address');


		if(!empty($check_key)){

			$wordpress_fields = array('first_name', 'last_name', 'description', 'aim', 'yim', 'user_url', 'display_name', 'jabber');
			$user_data_fields = array('user_login', 'user_email', 'user_url', 'user_pass', 'user_pass_conf');

			$user_data = unserialize($check_key->user_data);

			if(!isset($user_data['user_pass'])){
				$password = wp_generate_password(10, false, false);
			}else{
				$password = $user_data['user_pass'];
			}

			$user_login = (isset($user_data['user_login'])) ? $user_data['user_login'] : cjfm_create_unique_username($user_data['user_email'], '@', 1);

			$display_name = (isset($user_data['display_name'])) ? $user_data['display_name'] : $user_login;

			$new_user_data = array(
				'user_login' => $user_login,
				'user_email' => $user_data['user_email'],
				'user_pass' => $password,
				'user_url' => @$user_data['user_url'],
				'display_name' => $display_name,
				'user_nicename' => $display_name,
				'role' => $user_data['cjfm_user_role'],
			);

			if(!username_exists($user_data['user_login'])){
				$new_user_id = wp_insert_user($new_user_data);
			}else{
				$new_user_id = cjfm_user_info($user_data['user_login'], 'ID');
			}


			foreach ($user_data as $key => $value) {
				if(in_array($key, $user_data_fields)){
					unset($user_data[$key]);
				}
			}

			if(!empty($user_data)){
				foreach ($user_data as $key => $value) {
					update_user_meta($new_user_id, $key, $value);
				}
			}

			update_user_meta($new_user_id, 'cjfm_user_salt', base64_encode($password));
			update_user_meta($new_user_id, 'cjfm_rp', base64_encode($password));
			update_user_meta($new_user_id, 'cjfm_last_login', time());
			update_user_meta($new_user_id, 'cjfm_login_ip', cjfm_current_ip_address());

			// Do WordPress user_register action
			do_action('user_register', $new_user_id);

			$new_user_info = cjfm_user_info($new_user_id);

			$email_subject = cjfm_get_option('welcome_email_subject');
			$email_message = cjfm_parse_email('welcome_email_message', $new_user_info);

			$email_data = array(
				'to' => $new_user_info['user_email'],
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => $email_subject,
				'message' => $email_message,
			);
			cjfm_email($email_data);

			delete_user_meta($new_user_id, 'cjfm_rp');

			cjfm_send_new_user_email_to_admin($new_user_id, $form_id);

			$wpdb->query("DELETE FROM $user_signup_temp_table WHERE user_email = '{$check_user_email}' AND activation_key = '{$check_user_key}'");

			// Login new user.
			$creds = array();
			$creds['user_login'] = $new_user_data['user_login'];
			$creds['user_password'] = $password;
			$creds['remember'] = true;
			$user = wp_signon( $creds, is_ssl() );
			if ( !is_wp_error($user) ){
				update_user_meta($user->ID, 'cjfm_user_salt', base64_encode($password));
				update_user_meta($user->ID, 'cjfm_last_login', time());
				update_user_meta($user->ID, 'cjfm_login_ip', cjfm_current_ip_address());

				$user_info = cjfm_user_info($user->ID);
				do_action('cjfm_registeration_done', $user_info);

				wp_redirect( $redirect, $status = 302 );
				exit;
			}

		}else{
			$temp_email_address = @$_GET['cjfm_verify'];
			$wpdb->query("DELETE FROM $user_signup_temp_table WHERE user_email = '{$temp_email_address}'");

			$location = cjfm_string($register_page_url).'confirmation-key=invalid';
			wp_redirect( $location );
			exit;
		}

	} // if

}


function cjfm_send_new_user_email_to_admin($new_user_id, $form_id){
	global $wpdb, $current_user;

	$cjfm_custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$custom_fields = $wpdb->get_results("SELECT * FROM $cjfm_custom_fields_table WHERE form_id = '{$form_id}' ORDER BY id ASC");

	$new_user_info = cjfm_user_info($new_user_id);

	$email_message = __('<p>Dear Admin,</p>', 'cjfm');
	$email_message .= sprintf(__('<p>New user registration on your website %s</p>', 'cjfm'), get_bloginfo('name'));
	$email_message .= sprintf(__('Username: %s<br>Email Address: %s<br>', 'cjfm'), $new_user_info['user_login'], $new_user_info['user_email']);

	$unset_fields = array(
		'user_login',
		'user_email',
		'user_pass',
		'user_pass_conf',
		'user_avatar',
	);

	$additional_fields = null;

	foreach ($custom_fields as $key => $value) {
		if(!in_array($value->unique_id, $unset_fields)){
			if(isset($new_user_info[$value->unique_id])){
				$additional_fields .= $value->label.': '.$new_user_info[$value->unique_id].'<br>';
			}
		}
	}
	if(!is_null($additional_fields)){
		$email_message .= __('<p><strong>Additional registration form fields:</strong></p>', 'cjfm');
		$email_message .= $additional_fields;
	}

	$email_data = array(
		'to' => cjfm_get_option('admin_email'),
		'from_name' => cjfm_get_option('from_name'),
		'from_email' => cjfm_get_option('from_email'),
		'subject' => sprintf(__('[%s] New User Registration', 'cjfm'), get_bloginfo('name')),
		'message' => $email_message,
	);

	cjfm_email($email_data);
}


function cjfm_get_field_meta($field_id, $meta_key){
	global $wpdb;
	$table_cjfm_custom_fields_meta = $wpdb->prefix.'cjfm_custom_fields_meta';
	$meta = $wpdb->get_row("SELECT * FROM $table_cjfm_custom_fields_meta WHERE field_id = '{$field_id}' AND meta_key = '{$meta_key}'");
	if(!is_null($meta)){
		return $meta->meta_value;
	}else{
		return null;
	}
}

function cjfm_update_field_meta($field_id, $meta_key, $meta_value = ''){
	global $wpdb;
	$table_cjfm_custom_fields_meta = $wpdb->prefix.'cjfm_custom_fields_meta';
	$meta = $wpdb->get_row("SELECT * FROM $table_cjfm_custom_fields_meta WHERE field_id = '{$field_id}' AND meta_key = '{$meta_key}'");
	if(!is_null($meta)){
		$field_meta_data = array(
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
		);
		cjfm_update($table_cjfm_custom_fields_meta, $field_meta_data, 'id', $meta->id);
	}else{
		$field_meta_data = array(
			'field_id' => $field_id,
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
		);
		cjfm_insert($table_cjfm_custom_fields_meta, $field_meta_data);
	}
}




function cjfm_form_custom($page){
	$apply_to_pages = cjfm_get_option('apply_form_styles_to');
	if(in_array($page, $apply_to_pages) && !in_array('none', $apply_to_pages)){
		return 'cjfm-form-custom';
	}else{
		return '';
	}
}


function cjfm_custom_avatar_url($user_id_or_email){
	$user_id = cjfm_user_info($user_id_or_email, 'ID');
	$user_meta_avatar = get_user_meta($user_id, 'user_avatar', true);
	if(cjfm_get_option('user_avatar_type') == 'custom'){
		if($user_meta_avatar != ''){
			return $user_meta_avatar;
		}else{
			return cjfm_wp_avatar_url($user_id);
		}
	}else{
		return cjfm_gravatar_url($user_id);
	}
}




function cjfm_force_strong_passwords($post_data){

	$errors = null;

	if(isset($post_data['user_pass']) && $post_data['user_pass'] != ''){
		$strong_password = cjfm_is_password_strong($post_data['user_pass']);
		if(!$strong_password){
			$errors['user_pass'] =  array(__('Password must have a special character, a number and one capital letter.', 'cjfm'));
		}
	}

	if(isset($post_data['new_password']) && $post_data['new_password'] != ''){
		$strong_password = cjfm_is_password_strong($post_data['new_password']);
		if(!$strong_password){
			$errors['user_pass'] =  __('Password must have a special character, a number and one capital letter.', 'cjfm');
		}
	}

	$return['errors'] = $errors;

	return $return;

}

if(cjfm_get_option('password_strong') == 'enable'){
	add_filter('edit_profile_errors', 'cjfm_force_strong_passwords', 1, 5);
	add_filter('registration_errors', 'cjfm_force_strong_passwords', 1, 5);
	add_filter('reset_password_errors', 'cjfm_force_strong_passwords', 1, 5);
}




