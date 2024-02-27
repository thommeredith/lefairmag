<?php
global $wpdb;
//delete_option('cjfm_db_version');
$db_version = get_option('cjfm_db_version');

$item_version = cjfm_item_info('item_version');
$item_version_clean = str_replace('.', '_', cjfm_item_info('item_version'));

if($db_version == '' || $db_version < $item_version){
	if(function_exists('cjfm_db_upgrade_1_4_9')){
		cjfm_db_upgrade_1_4_9();
	}
}

function cjfm_db_upgrade_1_4_9(){
	global $wpdb;

	$item_version = cjfm_item_info('item_version');
	$item_version_clean = str_replace('.', '_', cjfm_item_info('item_version'));

	$forms_table = $wpdb->prefix.'cjfm_custom_forms';
	$fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$fields_temp_table = $wpdb->prefix.'cjfm_custom_fields_temp';
	$cjfm_charset_collate = $wpdb->get_charset_collate();

	$default_form_data = array(
		'form_name' => 'Default',
		'default_user_role' => get_option('default_role'),
		'can_remove' => 0,
	);
	cjfm_insert($forms_table, $default_form_data);

	// Create temp table
	$temp_table_sql = "CREATE TABLE $fields_temp_table LIKE $fields_table;";
	$wpdb->query($temp_table_sql);

	// Copy old data
	$copy_table_sql = "INSERT $fields_temp_table SELECT * FROM $fields_table;";
	$wpdb->query($copy_table_sql);

	// Drop old table
	$drop_old_table_sql = "DROP TABLE $fields_table;";
	$wpdb->query($drop_old_table_sql);

	// Create new table with updated structure
	$new_fields_table_sql = "
		CREATE TABLE IF NOT EXISTS `{$fields_table}` (
	        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	        `form_id` INT(20) NOT NULL,
	        `field_type` varchar(200) NOT NULL DEFAULT '',
	        `unique_id` varchar(200) NOT NULL DEFAULT '',
	        `label` varchar(100) NOT NULL DEFAULT '',
	        `description` text NOT NULL DEFAULT '',
	        `required` varchar(10) NOT NULL DEFAULT '',
	        `profile` varchar(10) NOT NULL DEFAULT '',
	        `register` varchar(10) NOT NULL DEFAULT '',
	        `invitation` varchar(10) NOT NULL DEFAULT '',
	        `enabled` varchar(10) NOT NULL DEFAULT '',
	        `options` text NOT NULL DEFAULT '',
	        `sort_order` int(11) NOT NULL,
	        PRIMARY KEY (`id`)
	    ) $cjfm_charset_collate;
	";
	$wpdb->query($new_fields_table_sql);

	// Restore old data
	$fields_data = $wpdb->get_results("SELECT * FROM $fields_temp_table ORDER BY id");
	$default_form = $wpdb->get_row("SELECT id FROM $forms_table WHERE form_name = 'Default'");
	foreach ($fields_data as $key => $value) {
		$field_data = array(
			'id' => $value->id,
			'form_id' => $default_form->id,
			'field_type' => $value->field_type,
			'unique_id' => $value->unique_id,
			'label' => $value->label,
			'description' => $value->description,
			'required' => $value->required,
			'profile' => $value->profile,
			'register' => $value->register,
			'invitation' => $value->invitation,
			'enabled' => $value->enabled,
			'options' => $value->options,
			'sort_order' => $value->sort_order,
		);
		cjfm_insert($fields_table, $field_data);
	}

	// Drop temp table
	$drop_old_table_sql = "DROP TABLE $fields_temp_table;";
	$wpdb->query($drop_old_table_sql);
	update_option('cjfm_db_version', $item_version);
}