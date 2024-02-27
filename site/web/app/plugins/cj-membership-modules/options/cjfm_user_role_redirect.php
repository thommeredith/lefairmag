<?php
global $wpdb, $cjfm_item_options, $wp_roles;

$role_based_redirects = cjfm_get_option('role_based_redirects');
if(isset($_POST['update_role_based_redirect'])){
	cjfm_update_option('role_based_redirects', $_POST['custom_redirect']);
	$location = cjfm_string(cjfm_callback_url('cjfm_user_role_redirect')).'msg=saved';
	wp_safe_redirect($location);
	exit;
}

$form_fields[] = array(
	'type' => 'heading',
	'id' => '',
	'label' => '',
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => __('Role based redirect', 'cjfm'),
	'options' => '', // array in case of dropdown, checkbox and radio buttons
);

foreach ($wp_roles->role_names as $key => $role) {
	$form_fields[] = array(
		'type' => 'text',
		'id' => 'custom_redirect['.$key.']',
		'label' => $role,
		'info' => sprintf(__('Specify full valid Url to redirect users with %s role.', 'cjfm'), $role),
		'suffix' => '',
		'prefix' => '',
		'default' => (isset($role_based_redirects[$key])) ? $role_based_redirects[$key] : '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}
$form_fields[] = array(
	'type' => 'submit',
	'id' => 'update_role_based_redirect',
	'label' => __('Save Settings', 'cjfm'),
	'info' => '',
	'suffix' => '',
	'prefix' => '',
	'default' => '',
	'options' => '', // array in case of dropdown, checkbox and radio buttons
);


echo '<form action="" method="post">';
echo (isset($_GET['msg']) && $_GET['msg'] == 'saved') ? cjfm_show_message('success', __('Custom redirects saved successfully.', 'cjfm')) : '';
echo cjfm_admin_form_raw($form_fields);
echo '</form>';