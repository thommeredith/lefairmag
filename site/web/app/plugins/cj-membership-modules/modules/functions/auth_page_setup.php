<?php
function cjfm_auth_page_setup(){
	global $wpdb, $post, $current_user;
	$auth_page = cjfm_get_option('page_custom_auth');
	if($auth_page != '' && $auth_page != '0' && is_page($auth_page)){
		include(sprintf('%s/themes/%s/index.php', cjfm_item_path('item_dir'), cjfm_get_option('auth_page_theme')));
	}
}
add_action('get_header', 'cjfm_auth_page_setup');