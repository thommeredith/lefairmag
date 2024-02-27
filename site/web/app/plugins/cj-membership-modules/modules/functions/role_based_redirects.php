<?php
function cjfm_custom_redirect_hook($user_info){
	$user_role = cjfm_user_role($user_info['ID']);
	$role_based_redirects = cjfm_get_option('role_based_redirects');
	if(isset($role_based_redirects[$user_role]) && $role_based_redirects[$user_role] != ''){
		$location = $role_based_redirects[$user_role];
		wp_redirect( $location );
		exit;
	}
}
add_action('cjfm_login_done', 'cjfm_custom_redirect_hook', 10, 1);