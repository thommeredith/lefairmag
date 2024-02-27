<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $cjfm_item_vars, $wpdb;

/*** Do not change anything in this file unless you know what you are doing ***/

# Item info
####################################################################################################
$cjfm_item_vars['item_info'] = array(
	'item_type' => 'plugin', // plugin or theme
	'item_id' => '6F9TIXR506', // Unique ID of the item
	'item_name' => 'Frontend Membership Modules',
	'item_version' => cjfm_version,
	'text_domain' => 'cjfm',
	'options_table' => $wpdb->prefix.'cjfm_options',
	'addon_tables' => 'cjfm_custom_forms,cjfm_custom_fields,cjfm_invitations,cjfm_temp_users',
	'page_title' => 'Membership Modules',
	'menu_title' => 'Membership Modules',
	'page_slug' => 'cjfm',

	'license_url' => 'http://cssjockey.com/terms-of-use',
	'api_url' => 'http://api.cssjockey.com',

	'recover_password_url' => 'http://cssjockey.com/dashboard/forgot-password/',
	'item_url' => 'http://goo.gl/pPQkpu',
	'envato_url' => 'http://goo.gl/pPQkpu',
	'premium_membership_url' => 'http://cssjockey.com/premium-membership/',
	'quick_start_guide_url' => 'http://docs.cssjockey.com/cjfm/quick-start-guide/',
	'documentation_url' => 'http://docs.cssjockey.com/cjfm',
	'support_forum_url' => 'http://support.cssjockey.com',
	'feature_request_url' => 'http://support.cssjockey.com',
	'report_bugs_url' => 'http://support.cssjockey.com',
);


$options_table = $cjfm_item_vars['item_info']['options_table'];

$table_check = $wpdb->get_row("DESCRIBE $options_table");

$approve_account_warning = __('Approve Accounts <span class="badge badge-warning">%d</span>', 'cjfm');
$approve_account_default = __('Approve Accounts <span class="badge badge-default">%d</span>', 'cjfm');

if(!empty($table_check)){
	$registration_type = $wpdb->get_row("SELECT * FROM $options_table WHERE option_name = 'register_type'");

	if(!empty($registration_type)){
		if($registration_type->option_value == 'approvals'){
			$approvals = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'cjfm_account_approved' AND meta_value = '0'");
			if(!empty($approvals)){
				$approval_menu['cjfm_approve_accounts'] = '<span class="orange">'.sprintf($approve_account_warning, count($approvals)).'</span>';
			}else{
				$approval_menu['cjfm_approve_accounts'] = '<span class="orange">'.sprintf($approve_account_default, count($approvals)).'</span>';
			}
		}
	}
}

# Dropdown items
####################################################################################################
$cjfm_item_vars['dropdown'] = array(
	'configuration' => array(
		'cjfm_maintenance_settings' => __('Maintenance Mode', 'cjfm'),
		'cjfm_configuration' => __('Basic Configuration', 'cjfm'),
		'cjfm_page_setup' => __('Page Setup', 'cjfm'),
		'cjfm_restrict_content' => __('Restricted Content', 'cjfm'),
		'cjfm_spam_protection' => __('Spam Protection', 'cjfm'),
		'cjfm_social_login' => __('Social Login Setup', 'cjfm'),
		'cjfm_modalbox_forms' => __('Modalbox Forms', 'cjfm'),
		'cjfm_auth_page' => __('Custom Auth Page', 'cjfm'),
		'cjfm_user_role_redirect' => __('Role Based Redirects', 'cjfm'),
		'cjfm_role_based_menus' => __('Role Based Menus', 'cjfm'),
		'cjfm_customize' => __('Custom CSS or Javascript', 'cjfm'),
	),
	'cjfm_customize_forms' => __('Customize Forms', 'cjfm'),
	'customize_email_messages' => array(
		'cjfm_customize_emails_config' => __('Email Configuration', 'cjfm'),
		'cjfm_customize_emails_variables' => __('Dynamic Variables', 'cjfm'),
		'cjfm_customize_emails_registration' => __('Registration Emails', 'cjfm'),
		'cjfm_customize_emails_password' => __('Reset Password Emails', 'cjfm'),
	),
	'cjfm_csv_import' => __('Import/Export Users', 'cjfm'),
);

$cjfm_item_vars['localize_variables'] = array(
	__('Configuration', 'cjfm'),
	__('Customize Email Messages', 'cjfm'),
);

if(!empty($registration_type)){
	if($registration_type->option_value == 'approvals'){
		$cjfm_item_vars['dropdown'] = @array_merge($cjfm_item_vars['dropdown'], $approval_menu);
	}
	if($registration_type->option_value == 'invitations'){
		$invitations_table = $wpdb->prefix.'cjfm_invitations';
		$invitation_requests = $wpdb->get_results("SELECT * FROM $invitations_table WHERE invited = '0'");
		$invitation_requests_declined = $wpdb->get_results("SELECT * FROM $invitations_table WHERE invited = '2'");
		$invitations_menu['Invitations_('.count($invitation_requests).')'] = array(
			//'cjfm_invitations_setup' => __('Basic Configuration', 'cjfm'),
			'cjfm_invitations' => sprintf(__('Awaiting Approvals (%d)', 'cjfm'), count($invitation_requests)),
			'cjfm_invitations_declined' => sprintf(__('Declined Invitations (%d)', 'cjfm'), count($invitation_requests_declined)),
			'cjfm_invitations_approved' => __('Invitations Approved', 'cjfm'),
		);
		$cjfm_item_vars['dropdown'] = array_merge($cjfm_item_vars['dropdown'], $invitations_menu);
	}
}

# Option Files
####################################################################################################
$cjfm_item_vars['option_files'] = array(
	'plugin_addon_options',
	'cjfm_configuration',
	'cjfm_maintenance_settings',
	'cjfm_page_setup',
	'cjfm_customize',
	'cjfm_spam_protection',
	'cjfm_restrict_content',
	'cjfm_social_login',
	'cjfm_modalbox_forms',
	'cjfm_auth_page',
	'cjfm_customize_emails_config',
	'cjfm_customize_emails_registration',
	'cjfm_customize_emails_password',
	'cjfm_customize_emails_variables',
);

# Load Modules
####################################################################################################
$cjfm_item_vars['modules'] = array(
	'functions/global',
	'shortcodes/global',
	'widgets/global',
	'helpers/global',

	'functions/item-assistant',
	'functions/db_upgrade',

	'shortcodes/cjfm_login_form',
	'shortcodes/cjfm_register_form',
	'shortcodes/cjfm_logout',
	'shortcodes/cjfm_reset_password_form',
	'shortcodes/cjfm_user_profile',
	'shortcodes/cjfm_user_avatar',
	'shortcodes/cjfm_page_links',
	'shortcodes/cjfm_user_content',
	'shortcodes/cjfm_user_meta',
	'shortcodes/cjfm_social_login',
	'shortcodes/cjfm_delete_account',

	'widgets/cjfm_custom_message',

	'functions/cjfm_export_users_data',
	'functions/auth_page_setup',
	'functions/woocommerce',
	'functions/role_based_redirects',
	'functions/role_based_menus',
	'functions/ajax',
);


# Load Extras
####################################################################################################
$cjfm_item_vars['load_extras'] = array();


# Sidebar Vars
####################################################################################################
$cjfm_item_vars['sidebar_vars'] = array(
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="title">',
	'after_title' => '</h3>',
);


# Theme Nav Menus
####################################################################################################
//$cjfm_item_vars['nav_menus'] = array();
$cjfm_item_vars['nav_menus'] = array(
	'cjfm_visitors_menu' => 'Visitors Only Menu (Membership Modules) <a href="http://docs.cssjockey.com/cjfm/configuring-nav-menus/" target="_blank">Documentation</a>',
	'cjfm_users_menu' => 'Users Only Menu (Membership Modules) <a href="http://docs.cssjockey.com/cjfm/configuring-nav-menus/" target="_blank">Documentation</a>',
);


# Database Tables
####################################################################################################

$cjfm_charset_collate = $wpdb->get_charset_collate();

$options_table = $cjfm_item_vars['item_info']['options_table'];
$cjfm_item_vars['db_tables']['sql'] = "
	CREATE TABLE IF NOT EXISTS `{$options_table}` (
        `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `option_name` varchar(64) NOT NULL DEFAULT '',
        `option_value` longtext NOT NULL,
        PRIMARY KEY (`option_id`),
        UNIQUE KEY `option_name` (`option_name`)
    ) $cjfm_charset_collate;
";

$forms_table = $wpdb->prefix.'cjfm_custom_forms';
$cjfm_item_vars['db_tables']['custom_forms'] = "
	CREATE TABLE IF NOT EXISTS `{$forms_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `form_name` varchar(100) NOT NULL DEFAULT '',
        `default_user_role` varchar(200) NOT NULL DEFAULT '',
        `can_remove` INT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY `form_name` (`form_name`)
    ) $cjfm_charset_collate;
";

$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
$cjfm_item_vars['db_tables']['custom_fields'] = "
	CREATE TABLE IF NOT EXISTS `{$custom_fields_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `form_id` INT(20) NOT NULL,
        `field_type` varchar(200) NOT NULL DEFAULT '',
        `unique_id` varchar(100) NOT NULL DEFAULT '',
        `label` varchar(100) NOT NULL DEFAULT '',
        `description` text NOT NULL DEFAULT '',
        `required` varchar(10) NOT NULL DEFAULT '',
        `profile` varchar(10) NOT NULL DEFAULT '',
        `register` varchar(10) NOT NULL DEFAULT '',
        `invitation` varchar(10) NOT NULL DEFAULT '',
        `enabled` varchar(10) NOT NULL DEFAULT '',
        `options` text NOT NULL DEFAULT '',
        `sort_order` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_id` (`unique_id`)
    ) $cjfm_charset_collate;
";

$custom_fields_meta_table = $wpdb->prefix.'cjfm_custom_fields_meta';
$cjfm_item_vars['db_tables']['custom_fields_meta'] = "
	CREATE TABLE IF NOT EXISTS `{$custom_fields_meta_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `field_id` INT(20) NOT NULL,
        `meta_key` varchar(200) NOT NULL DEFAULT '',
        `meta_value` text NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
    ) $cjfm_charset_collate;
";

$invitations_table = $wpdb->prefix.'cjfm_invitations';
$cjfm_item_vars['db_tables']['invitations'] = "
	CREATE TABLE IF NOT EXISTS `{$invitations_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_email` varchar(100) NOT NULL,
        `invitation_key` text NOT NULL,
        `user_data` longtext NOT NULL,
        `dated` datetime NOT NULL,
        `invited` INT NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_email` (`user_email`)
    ) $cjfm_charset_collate;
";

$temp_users_table = $wpdb->prefix.'cjfm_temp_users';
$cjfm_item_vars['db_tables']['temp_users'] = "
	CREATE TABLE IF NOT EXISTS `{$temp_users_table}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_email` varchar(100) NOT NULL,
        `activation_key` text NOT NULL,
        `user_data` longtext NOT NULL,
        `dated` datetime NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_email` (`user_email`)
    ) $cjfm_charset_collate;
";


# Recommended or Required Plugins
####################################################################################################
$cjfm_item_vars['install_plugins'] = array();

# Custom Post Types
####################################################################################################
$cjfm_item_vars['custom_post_types'] = array();

# Custom Taxonomies
####################################################################################################
$cjfm_item_vars['custom_taxonomies'] = array();
