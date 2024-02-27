<?php
require_once ('class-plugin-update.php');
$item_info = cjfm_item_info();
$cjfm_eon = sha1('cjfm_verify_epc'.site_url());
$cjfm_eov = get_option($cjfm_eon);
$cjfm_upgrade_url = 'http://api.cssjockey.com/?cj_action=upgrades&item_id='.$item_info['item_id'].'&item_type='.$item_info['item_type'].'&purchase_code='.$cjfm_eov.'&slug='.basename(cjfm_item_path('item_dir')).'&site_url='.site_url();
$cjfm_plugin_upgrades = new PluginUpdateChecker(
	$cjfm_upgrade_url,
	cjfm_item_path('item_dir').'/index.php',
	basename(cjfm_item_path('item_dir'))
);