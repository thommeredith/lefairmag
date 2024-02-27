<?php
global $post;
function cjfm_user_avatar( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array(
		'return' => null,
		'user_id' => $current_user->ID,
		'type' => 'url',
		'size' => '125',
		'class' => 'cjfm-login-form',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$type_array = array(
		'url' => __('Url', 'cjfm'),
		'image' => __('Image', 'cjfm'),
	);

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode return user avatar based on plugin configuration.', 'cjfm'),
		'default_content' => __('This content will be displayed to the user if type is set to message.', 'cjfm'),
		'user_id' => array(__('User ID', 'cjfm'), 'text', null, __('Leave this blank to get current loggedin user.', 'cjfm')),
		'type' => array(__('Logout Type <i style="color:red;">(required)</i>:', 'cjfm'), 'dropdown', $type_array, __('Select logout type', 'cjfm')),
		'size' => array(__('Image Size', 'cjfm'), 'text', 125, __('You can specify custom size for avatar image.', 'cjfm')),
		'class' => array(__('Image CSS Class', 'cjfm'), 'text', null, __('You can specify custom CSS class for avatar image.', 'cjfm')),
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}


	$display[] = '';
	$output = '';
	$user_avatar = get_avatar( $user_id, $size );
	if($type == 'url'){
		$output = cjfm_get_image_url($user_avatar);
	}else{
		$output = $user_avatar;
	}
	$display[] = $output;
	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_user_avatar', 'cjfm_user_avatar' );
