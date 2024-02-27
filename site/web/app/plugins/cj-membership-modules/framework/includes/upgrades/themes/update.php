<?php
require 'theme-update-checker.php';
$item_info = cjfm_item_info();
$cjfm_eon = sha1('cjfm_verify_epc'.site_url());
$cjfm_eov = get_option($cjfm_eon);
$cjfm_upgrade_url = 'http://api.cssjockey.com/?cj_action=upgrades&item_id='.$item_info['item_id'].'&item_type='.$item_info['item_type'].'&purchase_code='.$cjfm_eov.'&slug='.basename(cjfm_item_path('item_dir')).'&site_url='.site_url();
$theme_slug = basename(get_template_directory_uri());
$theme_update_checker = new ThemeUpdateChecker(
	$theme_slug,
	$cjfm_upgrade_url //URL of the metadata file.
);