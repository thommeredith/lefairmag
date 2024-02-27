<?php
function cjfm_user_meta( $atts, $content) {
	global $wpdb, $current_user, $post, $wp_query;
	$defaults = array(
		'return' => null,
		'meta_key' => 'user_login',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );


	$user_info = cjfm_user_info($current_user->ID);

	unset($user_info['cjfm_user_salt']);
	unset($user_info['cjfm_rp']);
	unset($user_info['user_pass']);

	/*if(!empty($user_info)){
		foreach ($user_info as $ukey => $uvalue) {
			$meta_key_array[$ukey] = $ukey;
		}
	}else{
		$meta_key_array['none'] = __('Not Applicable', 'cjfm');
	}*/

	$meta_keys = $wpdb->get_results("SELECT DISTINCT(meta_key) FROM $wpdb->usermeta ORDER BY meta_key ASC");
	foreach ($meta_keys as $key => $mkey) {
		$meta_key_array[$mkey->meta_key] = $mkey->meta_key;
	}

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will display the current user data based on the specified key.', 'cjfm'),
		'meta_key' => array(__('User Meta Key', 'cjfm'), 'dropdown', $meta_key_array, __('Select user data key to return its value.', 'cjfm')),
	);
	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	$output = (isset($user_info[$meta_key])) ? $user_info[$meta_key] : '';

	$date_fields = array(
		'cjfm_last_login',
		'cjadexpire_date'
	);

	if($output != '' && in_array($meta_key, $date_fields)){
		$output = cjfm_wp_date($output, true);
	}

	if(is_user_logged_in()){
		$display[] = $output;
	}else{
		$display[] = '';
	}

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

	// do shortcode actions here
}
add_shortcode( 'cjfm_user_meta', 'cjfm_user_meta' );