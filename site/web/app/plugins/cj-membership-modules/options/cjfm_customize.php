<?php
global $cjfm_item_options;

$colors_array = array(
	'danger' => __('red', 'cjfm'),
	'success' => __('green', 'cjfm'),
	'warning' => __('orange', 'cjfm'),
	'info' => __('blue', 'cjfm'),
	'inverse' => __('black', 'cjfm'),
	'none' => __('gray', 'cjfm'),
);

$size_array = array(
	'mini' => __('mini', 'cjfm'),
	'small' => __('small', 'cjfm'),
	'default' => __('medium', 'cjfm'),
	'large' => __('large', 'cjfm'),
);

$forms_array = array(
	'none' => __('None', 'cjfm'),
	'login' => __('Login Form', 'cjfm'),
	'register' => __('Registration Form', 'cjfm'),
	'reset_password' => __('Reset Password Form', 'cjfm'),
	'edit_profile_form' => __('Edit Profile Form', 'cjfm'),
	'invitation' => __('Invitation Form', 'cjfm'),
);

$cjfm_item_options['cjfm_customize'] = array(
	array(
		'type' => 'heading',
		'id' => 'customize_button_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Customize Form Styles', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'background',
		'id' => 'form_bg',
		'label' => __('Form background', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'form_max_width',
		'label' => __('Form Max Width', 'cjfm'),
		'info' => __('Specify form maximum width in percentage or pixels.', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '100%',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'form_padding',
		'label' => __('Form padding', 'cjfm'),
		'info' => __('Specify form padding in this order: Top, Right, Bottom, Left', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '0px 0px 0px 0px',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'form_text_color',
		'label' => __('Form text color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'form_link_color',
		'label' => __('Form link color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'form_link_hover_color',
		'label' => __('Form link hover color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'checkbox',
		'id' => 'apply_form_styles_to',
		'label' => __('Apply above styles to:', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('none'),
		'options' => $forms_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'icon_position_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Icon position', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'icon_position_top',
		'label' => __('Icon Position Top', 'cjfm'),
		'info' => '',
		'suffix' => 'px',
		'prefix' => '',
		'default' => '10',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'icon_position_right',
		'label' => __('Icon Position Right', 'cjfm'),
		'info' => '',
		'suffix' => 'px',
		'prefix' => '',
		'default' => '10',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'form_button_bg_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Submit Button Styles', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'form_button_bg',
		'label' => __('Submit Button Background Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#5cb85c',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'form_button_text_color',
		'label' => __('Submit Button Text Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#ffffff',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'select',
		'id' => 'button_size',
		'label' => __('Submit Button Size', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'default',
		'options' => $size_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'customize_code_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom CSS or Javascript', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'customize_code_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Custom code will be inserted in the footer. Make sure your theme supports wp_footer() function.', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-css',
		'id' => 'custom_css',
		'label' => __('Custom CSS Code', 'cjfm'),
		'info' => __('<p>Write your custom css code here.</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '<style type="text/css">
	/* add custom css code */
</style>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'code-js',
		'id' => 'custom_js',
		'label' => __('Custom JavaScript Code', 'cjfm'),
		'info' => __('<p>Write your custom javascript code here.</p>', 'cjfm'),
		'suffix' => '',
		'prefix' => '',
		'default' => '<script type="text/javascript">
	/* add custom javascript code */
</script>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => '',
		'label' => __('Save Settings', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);
?>