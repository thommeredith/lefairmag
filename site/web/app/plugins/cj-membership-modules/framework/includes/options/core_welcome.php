<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
$help_support_value[] = '<a href="'.cjfm_item_info('quick_start_guide_url').'" target="_blank">'.__('Quick Start Guide', 'cjfm').'</a>';
$help_support_value[] = '<a href="'.cjfm_item_info('documentation_url').'" target="_blank">'.__('Documentation', 'cjfm').'</a>';
$help_support_value[] = '<a href="'.cjfm_item_info('support_forum_url').'" target="_blank">'.__('Support Fourm', 'cjfm').'</a>';

if(isset($_REQUEST['cjmsg']) && $_REQUEST['cjmsg'] == 'pc-no-match'){
	echo cjfm_show_message('error', sprintf(__('Could not reset your license for this installation. <br><a target="_blank" href="%s">Click here</a> to create a support ticket.', 'cjfm'), cjfm_item_info('support_forum_url')));
}

$cjfm_purchase_code = '';

$eon = sha1('cjfm_verify_epc'.site_url());
$eov = get_option($eon);
if($eov != ''){
	$cjfm_purchase_code = array(
		'type' => 'info',
		'id' => 'cjfm_purchase_code',
		'label' => __('Purchase code', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<span class="btn btn-success btn-sm" style=" margin-right: 10px;"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;'.__('Verified & Active', 'cjfm').'</span>'.
					  sprintf('<a href="%s" class="btn btn-danger btn-sm cj-confirm" data-confirm="%s">Reset License</a>', cjfm_string(cjfm_callback_url('core_welcome')).'cjfm_action=reset-license', __("Are you sure?\nThis will reset your license for this installation.\nYou may use this license again on any installation.", 'cjfm')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}else{
	$cjfm_purchase_code = array(
		'type' => 'info',
		'id' => 'cjfm_purchase_code',
		'label' => __('Purchase code', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<span class="label label-danger" style="padding:5px 10px; font-size:14px; font-weight:normal;"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;'.__('Not Verified', 'cjfm').'</span>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

if(cjfm_is_local()){
	$cjfm_purchase_code = array(
		'type' => 'info',
		'id' => 'cjfm_purchase_code',
		'label' => __('Purchase code', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<span class="label label-default" style="padding:5px 10px; font-size:14px; font-weight:normal;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;'.__('Local Server', 'cjfm').'</span><span class="margin-10-left italic red">'.__('You will be asked to validate your purchase code to use this product on live server.', 'cjfm').'</span>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

if(cjfm_item_info('item_id') == 'NA'){
	$cjfm_purchase_code = null;
	$contribute = null;
}else{
	$contribute = array(
		'type' => 'info',
		'id' => 'contribute',
		'label' => __('Contribute', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<p>You can contribute to support further development and new features for this product.</p> <p><a target="_blank" class="btn btn-danger" href="%s">Contribute</a></p>', 'cjfm'), 'http://cssjockey.com/contribute'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

$localization_string = sprintf(__('You can download <a class="bold" target="_blank" href="%s">Loco Translate WordPress Plugin (FREE)</a> to easily create language files within your WordPress dashboard without using Poedit software.', 'cjfm'), 'https://wordpress.org/plugins/loco-translate/');

$cjfm_form_options['welcome'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'welcome_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s Information', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_type',
		'label' => __('Product Type', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('WordPress %s', 'cjfm'), ucwords(cjfm_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_name',
		'label' => __('Product Name', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_item_info('item_name'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_id',
		'label' => __('Product ID', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_item_info('item_id'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'version',
		'label' => __('Installed Version', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjfm_item_info('item_version'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	$cjfm_purchase_code,
	array(
		'type' => 'info',
		'id' => 'license',
		'label' => __('License & Terms of use', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<a target="_blank" href="%s">Click here</a> to view license and terms of use.', 'cjfm'), cjfm_item_info('license_url')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'help_support',
		'label' => __('Help & Support', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode(' &nbsp; &bull; &nbsp; ', $help_support_value),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'translate',
		'label' => __('Localization', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $localization_string,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	$contribute,
);
cjfm_admin_form_raw($cjfm_form_options['welcome']);


do_action('cjfm_after_welcome_panel');
