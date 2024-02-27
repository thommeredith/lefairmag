<?php
global $wpdb, $cjfm_item_options;
$yes_no_array = array('yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));
$enable_disable_array = array('enable' => __('Enable', 'cjfm'), 'disable' => __('Disable', 'cjfm'));

$social_providers = array('Facebook', 'Twitter', 'Google', 'Yahoo', 'Live', 'LinkedIn', 'Foursquare', 'OpenID', 'Github', 'LastFM', 'Vimeo', 'Viadeo', 'Identica', 'Tumblr', 'Goodreads', 'QQ', 'Sina', 'Murmur', 'Pixnet', 'Plurk', 'Skyrock', 'Geni', 'FamilySearch', 'MyHeritage', 'px500', 'Vkontakte', 'Mail.ru', 'Yandex', 'Odnoklassniki', 'Instagram', 'TwitchTV', 'Steam');

$cjfm_item_options['cjfm_social_login']['main_heading'] = array(
	'type' => 'heading',
	'id' => 'social_login_heading',
	'label' => '',
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => __('Social Login Setup', 'cjfm'),
	'options' => '', // array in case of dropdown, checkbox and radio buttons
);
$cjfm_item_options['cjfm_social_login']['config-info'] = array(
	'type' => 'info',
	'id' => 'config-info',
	'label' => __('Information', 'cjfm'),
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => sprintf(__('<p>This plugin uses <b>HybridAuth</b> for social login features.</p> <p><a href="%s" target="_blank">Click here</a> to view provider setup instructions and other documentation.</p>', 'cjfm'), 'http://hybridauth.sourceforge.net/userguide.html').
				 __('Specify AppID/AppKey and AppSecret to enable social service and include the shortcode via our Shortcode Generator.', 'cjfm'),
	'options' => '', // array in case of dropdown, checkbox and radio buttons
);
$cjfm_item_options['cjfm_social_login']['callback_url_info'] = array(
	'type' => 'info',
	'id' => 'callback_url_info',
	'label' => __('<b>Callback Url</b>', 'cjfm'),
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => '<p>'.cjfm_item_path('modules_url').'/shortcodes/hybridauth/hybridauth/?hauth.done=<b>Google</b>'.'</p>'.
				__('<p>You must replace <b>Google</b> with the service you are using when using the callback url.</p>', 'cjfm').
				__('<p>This may be required by some services to generate oAuth keys.</p>', 'cjfm'),
	'options' => '', // array in case of dropdown, checkbox and radio buttons
);
$cjfm_item_options['cjfm_social_login']['social_must_register'] = array(
	'type' => 'dropdown',
	'id' => 'social_must_register',
	'label' => __('User must fill registration form', 'cjfm'),
	'info' => __('If Yes, users will be redirected to default registration page and assigned custom form fields specified in the shortcode to complete their registration.<br>
				  If No, account will be created automatically and assigned default form fields under user profile.', 'cjfm'),
	'suffix' => '',
	'prefix' => '',
	'default' => 'no',
	'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
);

/*$cjfm_item_options['cjfm_social_login']['social_profiles_info'] = array(
	'type' => 'info-full',
	'id' => 'social_profiles_info',
	'label' => '',
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => '<div class="red"><b>'.__('Setup Social Connect Profiles', 'cjfm').'</b></div>',
	'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
);*/


foreach ($social_providers as $key => $sp) {
	$cjfm_item_options['cjfm_social_login'][$sp.'_heading'] = array(
		'type' => 'sub-heading',
		'id' => $sp.'_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s Configuration', 'cjfm'), $sp),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
	$cjfm_item_options['cjfm_social_login'][$sp.'_appID'] = array(
		'type' => 'text',
		'id' => $sp.'_appID',
		'label' => sprintf(__('%s AppID / AppKey', 'cjfm'), $sp),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
	$cjfm_item_options['cjfm_social_login'][$sp.'_appSecret'] = array(
		'type' => 'text',
		'id' => $sp.'_appSecret',
		'label' => sprintf(__('%s AppSecret', 'cjfm'), $sp),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
	$cjfm_item_options['cjfm_social_login'][$sp.'_text'] = array(
		'type' => 'text',
		'id' => $sp.'_text',
		'label' => sprintf(__('%s Button Text', 'cjfm'), $sp),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Connect via %s', 'cjfm'), $sp),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

$cjfm_item_options['cjfm_social_login']['submit_button'] = array(
	'type' => 'submit',
	'id' => '',
	'label' => __('Save Settings', 'cjfm'),
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => '',
	'options' => '', // array in case of dropdown, checkbox and radio buttons
);
