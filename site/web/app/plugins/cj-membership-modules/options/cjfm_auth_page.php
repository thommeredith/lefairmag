<?php
global $wpdb, $cjfm_item_options;

/*
if(isset($_GET['restore']) && $_GET['restore'] == 'cjfm_auth_page'){
	$cjfm_options_table = $wpdb->prefix.'cjfm_options';

	$exclude_reset = array('page_custom_auth', 'auth_page_theme', 'auth_logo');

	foreach ($cjfm_item_options['cjfm_auth_page'] as $key => $value) {
		if(!in_array($value['id'], $exclude_reset)){
			$wpdb->query("DELETE FROM $cjfm_options_table WHERE option_name = '{$value['id']}'");
		}
	}
	$redirect = cjfm_callback_url('cjfm_auth_page');
	ob_start();
	header("Location: $redirect");
	exit;
}
*/

$auth_page_themes = array(
	'default' => __('Default Theme', 'cjfm'),
);

$auth_body_background_array = array(
	'color' => '#f7f7f7',
	'image' => null,
	'bg_repeat' => 'no-repeat',
	'bg_size' => 'cover',
	'bg_attachment' => 'inherit',
	'bg_position' => 'top center',
);


$cjfm_item_options['cjfm_auth_page'] = array(
	array(
		'type' => 'heading',
		'id' => 'auth_page_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom Auth Page', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'auth_page_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Select a page where you would like to display login, register and reset password forms.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_custom_auth',
		'label' => __('Select Auth Page', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'auth_page_theme',
		'label' => __('Select Theme', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'default',
		'options' => $auth_page_themes, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'file',
		'id' => 'auth_logo',
		'label' => __('Upload Logo Image', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'body-style-heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Page Style', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'background',
		'id' => 'auth_body_background',
		'label' => __('Body Background', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $auth_body_background_array,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'font',
		'id' => 'auth_body_font',
		'label' => __('Font Styles', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('family' => 'Open+Sans', 'weight' => '400', 'size' => '14px'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_body_color',
		'label' => __('Body Text Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#222222',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_body_link_color',
		'label' => __('Body Link Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#3cb8db',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_panel_background',
		'label' => __('Panel Background', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#ffffff',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_panel_border_color',
		'label' => __('Panel Border Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#ededed',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_panel_color',
		'label' => __('Panel Text Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#222222',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_panel_link_color',
		'label' => __('Panel Link Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#3cb8db',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_panel_button_color',
		'label' => __('Panel Button Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#5cb85c',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'auth_panel_button_text_color',
		'label' => __('Panel Button Text Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#FFFFFF',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'auth_border_radius',
		'label' => __('Border Radius', 'cjfm'),
		'info' => __('Specify border radius for auth page elements.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '0px 0px 0px 0px',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'auth_page_icon_position_top',
		'label' => __('Icon Position Top', 'cjfm'),
		'info' => '',
		'suffix' => 'px',
		'prefix' => '',
		'default' => '15',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'auth_page_icon_position_right',
		'label' => __('Icon Position Right', 'cjfm'),
		'info' => '',
		'suffix' => 'px',
		'prefix' => '',
		'default' => '15',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-css',
		'id' => 'auth_custom_css',
		'label' => __('Custom CSS Code', 'cjfm'),
		'info' => __('Specify valid CSS code to customize selected theme.<br><span class="red bold">Note:</span> Do not add &lt;style&gt; tags as the code will be wrapped within the style tags.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'page-content-heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Auth Page Content', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'page-content-info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<b>NOTE:</b> Other shortcodes will not work on this page as the theme pages doesnot include wp_head() or wp_footer() functions.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'content_login',
		'label' => __('Login Form Shortcode', 'cjfm'),
		'info' => '<p>'.__('You can add any content above or below the login shortcode.', 'cjfm').'</p>'.'<p><code>[cjfm_form_login redirect_url="" button_text=""]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf('[cjfm_form_login form_id="1" redirect_url="%s" button_text="Sign In"]', site_url()),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'content_register',
		'label' => __('Register Form Shortcode', 'cjfm'),
		'info' => '<p>'.__('You can add any content above or below the register shortcode.', 'cjfm').'</p>'.'<p><code>[cjfm_form_register redirect_url="" button_text=""]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf('[cjfm_form_register form_id="1" redirect_url="%s" button_text="Create account"]', site_url()),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'content_password',
		'label' => __('Reset password Form Shortcode', 'cjfm'),
		'info' => '<p>'.__('You can add any content above or below the reset password shortcode.', 'cjfm').'</p>'.'<p><code>[cjfm_form_reset_password]</code></p>',
		'suffix' => '',
		'prefix' => '',
		'default' => '[cjfm_form_reset_password]',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'content_social_shortcodes',
		'label' => __('Social connect shortcodes', 'cjfm'),
		'info' => __('<p>Insert social media shortcodes here</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf('[cjfm_social_login service="all" form_id="1" redirect_url="%s" button_text="" button_class=""]', site_url()),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => '',
		'label' => __('Save Settings', 'cjfm'),
		'info' => '',
		'suffix' => __('<a href="'.cjfm_string(cjfm_callback_url('cjfm_auth_page')).'restore=cjfm_auth_page" class="button-secondary margin-10-left">Restore Defaults</a>', 'cjfm'),
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

