<?php
function cjfm_export_user_data(){
	global $wpdb;
	if(isset($_GET['callback']) && $_GET['callback'] == 'cjfm_csv_import'){

		$custom_fields_table = $wpdb->prefix.'cjfm_custom_fields';
		$users_columns = $wpdb->get_row("SELECT * FROM $wpdb->users", ARRAY_A);

		$users_table_columns = array('user_login', 'ID', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_status', 'display_name', 'spam', 'deleted');

		$columns_array[] = 'Sr. No.';
		foreach ($users_columns as $ukey => $uvalue) {
			if(in_array($ukey, $users_table_columns)){
				$columns_array[] = $ukey;
				$new_users_columns_array[] = $ukey;
			}
		}

		$new_users_columns_array[] = 'user_pass';


		if(isset($_GET['include_meta_keys'])){
			$include_meta_keys = explode(',', $_GET['include_meta_keys']);
			foreach ($include_meta_keys as $mkey => $mvalue) {
				$columns_array[] = $mvalue;
				$custom_user_fields[] = $mvalue;
				$new_users_columns_array[] = $mvalue;
			}
		}


		$users_rows = $wpdb->get_results("SELECT * FROM $wpdb->users ORDER BY ID ASC", ARRAY_A);
		foreach ($users_rows as $ukey => $uvalue) {
			foreach ($uvalue as $key => $value) {
				if(in_array($key, $users_table_columns)){
					$rows_array[$uvalue['ID']]['count'] = $ukey + 1;
					if($key == 'user_registered'){
						$rows_array[$uvalue['ID']][$key] = date('Y-m-d H:i:s', strtotime($value));
					}else{
						$rows_array[$uvalue['ID']][$key] = $value;
					}
				}
			}
			if(isset($_GET['include_meta_keys'])){
				$include_meta_keys = explode(',', $_GET['include_meta_keys']);
				foreach ($include_meta_keys as $mkey => $mvalue) {
					$user_info = cjfm_user_info($uvalue['ID']);
					if(isset($user_info[$mvalue])){
						if(is_serialized($user_info[$mvalue])){

							$format_array = unserialize($user_info[$mvalue]);
							foreach ($format_array as $key => $value) {
								$format_array_values[] = $mvalue.'=>'.$value;
							}
							$rows_array[$uvalue['ID']][$mvalue] = implode(',', $format_array_values);
						}else{
							$rows_array[$uvalue['ID']][$mvalue] = $user_info[$mvalue];
						}
					}else{
						$rows_array[$uvalue['ID']][$mvalue] = 'NA';
					}
				}
			}
		}

		if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'export_users'){
			$file_name = sanitize_title( get_bloginfo( 'name' ).'-users-'.date("Y-m-d") );
			cjfm_create_csv($file_name, $columns_array, $rows_array);
		}

		if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'download_format'){
			$file_name = 'cjfm-users-import-format';

			foreach ($users_rows as $ukey => $uvalue) {
				foreach ($uvalue as $key => $value) {
					if(in_array($key, $users_table_columns)){
						$rows_array[$uvalue['ID']]['count'] = $ukey + 1;
						if($key == 'user_registered'){
							$rows_array[$uvalue['ID']][$key] = date('Y-m-d H:i:s', strtotime($value));
						}else{
							$rows_array[$uvalue['ID']][$key] = $value;
						}
					}
				}
			}

			cjfm_create_csv($file_name, $columns_array, $rows_array);
		}

		if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'download_format_new_users'){
			$file_name = 'cjfm-new-users-import-format';
			foreach ($new_users_columns_array as $key => $value) {
				if($value != 'ID' && $value != 'spam' && $value != 'deleted' && $value != 'user_status'){
					$new_users_array[] = $value;
				}
			}

			cjfm_create_csv($file_name, $new_users_array, array());
		}

	}
}

add_action('admin_init', 'cjfm_export_user_data');











