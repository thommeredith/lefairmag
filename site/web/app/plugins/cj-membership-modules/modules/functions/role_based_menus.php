<?php

function cjfm_replace_user_menus( $args = '' ) {
	global $wpdb, $current_user;
	$user_role = cjfm_user_role($current_user->ID);
	$cjfm_role_menus = cjfm_get_option('cjfm_role_menus');
	if(isset($cjfm_role_menus[$user_role]) && $cjfm_role_menus[$user_role]['menu_location'] != ''){
		if($args['theme_location'] == $cjfm_role_menus[$user_role]['menu_location']){
			$args['menu'] = $cjfm_role_menus[$user_role]['menu'];
		}
	}
	return $args;
}
add_filter( 'wp_nav_menu_args', 'cjfm_replace_user_menus' );