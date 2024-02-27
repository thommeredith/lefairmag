<?php
global $wpdb, $wp_roles;
$forms_table = $wpdb->prefix.'cjfm_custom_forms';
$fields_table = $wpdb->prefix.'cjfm_custom_fields';
$cjfm_forms = $wpdb->get_results("SELECT * FROM $forms_table ORDER BY id, can_remove DESC");

if(isset($_POST['update_form'])){
	$update_data = array(
		'form_name' => $_POST['form_name'],
		'default_user_role' => $_POST['default_user_role'],
		'can_remove' => $_POST['can_remove'],
	);
	cjfm_update($forms_table, $update_data, 'id', $_POST['id']);
	$location = cjfm_callback_url('cjfm_customize_forms');
	wp_safe_redirect( $location );
	exit;
}
if(isset($_GET['delete-form']) && $_GET['delete-form'] != ''){
	$wpdb->query("DELETE FROM $forms_table WHERE id = {$_GET['delete-form']}");
	$wpdb->query("DELETE FROM $fields_table WHERE form_id = {$_GET['delete-form']}");
	$location = cjfm_callback_url('cjfm_customize_forms');
	wp_safe_redirect( $location );
	exit;
}
if(isset($_GET['form']) && $_GET['form'] == 'add-new'){
	$new_form_data = array(
		'form_name' => cjfm_unique_string(),
		'default_user_role' => 'subscriber',
		'can_remove' => 1,
	);
	$form_id = cjfm_insert($forms_table, $new_form_data);

	cjfm_insert_default_form_fields($form_id);

	$location = cjfm_callback_url('cjfm_customize_forms');
	wp_safe_redirect( $location );
	exit;
}
?>
<?php
	// Import Export
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'imexfields'):

	$custom_forms_raw_query = $wpdb->get_results("SELECT * FROM $forms_table");
	$custom_fields_raw_query = $wpdb->get_results("SELECT * FROM $fields_table");

	$export_data = array(
		'forms_table' => $custom_forms_raw_query,
		'fields_table' => $custom_fields_raw_query,
	);
	$custom_fields_raw_data = urlencode(serialize($export_data));

	if(isset($_POST['do_import_fields'])){
		$import_data_raw = $_POST['import_fields_data'];
		$import_data = unserialize(urldecode($import_data_raw));

		if(!empty($import_data)){

			// Populate forms table
			$wpdb->query("TRUNCATE TABLE $forms_table");
			foreach ($import_data['forms_table'] as $key => $value) {
				$custom_form_data = array(
					"id" => $value->id,
					"form_name" => $value->form_name,
					"default_user_role" => $value->default_user_role,
					"can_remove" => $value->can_remove,
				);
				cjfm_insert($forms_table, $custom_form_data);
			}

			// Populate fields table
			$wpdb->query("TRUNCATE TABLE $fields_table");
			foreach ($import_data['fields_table'] as $key => $value) {
				$custom_field_data = array(
					"id" => $value->id,
					"form_id" => $value->form_id,
					"field_type" => $value->field_type,
					"unique_id" => $value->unique_id,
					"label" => $value->label,
					"description" => $value->description,
					"required" => $value->required,
					"profile" => $value->profile,
					"register" => $value->register,
					"invitation" => $value->invitation,
					"enabled" => $value->enabled,
					"options" => $value->options,
					"sort_order" => $value->sort_order,
				);
				cjfm_insert($fields_table, $custom_field_data);
			}

			$location = cjfm_callback_url('cjfm_customize_forms');
			wp_safe_redirect( $location );
			exit;
		}
	}

	$form_options['import_export_fields'] = array(
		array(
		    'type' => 'sub-heading',
		    'id' => '',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => __('Import/Export Fields Data', 'cjfm'),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'textarea',
		    'id' => 'export_fields_data',
		    'label' => __('Export Fields Data', 'cjfm'),
		    'info' => __('Copy this content and save it as is in a text file.<br>Make sure you do not make any changes to this string otherwise the import will fail.' , 'cjfm'),
		    'suffix' => '',
		    'prefix' => '',
		    'default' => $custom_fields_raw_data,
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'textarea',
		    'id' => 'import_fields_data',
		    'label' => __('Import Fields Data', 'cjfm'),
		    'info' => __('Paste previous saved custom fields data and hit Import Data button.', 'cjfm'),
		    'suffix' => '',
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'submit',
		    'id' => 'do_import_fields',
		    'label' => __('Import Data', 'cjfm'),
		    'info' => '',
		    'suffix' => '<a href="'.cjfm_callback_url('cjfm_customize_fields').'" class="button-secondary margin-10-left">'.__('Go Back', 'cjfm').'</a>',
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	);

	echo '<form action="" method="post" enctype="multipart/form-data">';
	cjfm_admin_form_raw($form_options['import_export_fields']);
	echo '</form>';

	endif;
?>

<?php if(!isset($_GET['cjfm_action'])):  ?>
<table width="100%" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th colspan="4">
				<span class="pull-right">
					<a href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_forms')).'cjfm_action=imexfields'; ?>" class="btn btn-sm btn-info margin-2-top margin-10-left"><?php _e('Import Export', 'cjfm') ?></a>
					<a class="btn btn-sm btn-success margin-2-top margin-10-left" href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_forms')).'form=add-new' ?>"><?php _e('Create New Form', 'cjfm') ?></a>
				</span>
				<h2 class="main-heading">
					<?php _e('Custom Registration & Profile Forms', 'cjfm'); ?>
				</h2>
			</th>
		</tr>
		<tr>
			<td width="10%"><?php _e('Form ID', 'cjfm'); ?></td>
			<td width=""><?php _e('Form Name', 'cjfm'); ?></td>
			<td width=""><?php _e('Default User Role', 'cjfm'); ?></td>
			<td width="20%"><?php _e('Actions', 'cjfm'); ?></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cjfm_forms as $key => $form): ?>
		<tr>
			<form action="<?php echo cjfm_callback_url('cjfm_customize_forms'); ?>" method="post">
			<td><input name="id" type="text" readonly value="<?php echo $form->id; ?>" style="width:100%;"></td>
			<td>
				<input name="form_name" type="text" value="<?php echo $form->form_name; ?>" style="width:100%;">
				<input name="can_remove" type="hidden" value="<?php echo $form->can_remove; ?>" style="width:100%;">
			</td>
			<td>
				<select name="default_user_role" style="width:100%;">
					<?php foreach ($wp_roles->role_names as $key => $value): ?>
					<?php if($form->default_user_role == $key): ?>
						<option selected value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php else: ?>
						<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
				<button name="update_form" type="submit" class="btn btn-info btn-sm margin-5-right"><?php _e('Update', 'cjfm'); ?></button>
				<a href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'form_id='.$form->id; ?>" class="btn btn-success btn-sm margin-5-right"><?php _e('Customize Fields', 'cjfm'); ?></a>
				<?php if($form->can_remove == 1): ?>
				<a href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_forms')).'delete-form='.$form->id; ?>" data-confirm="<?php _e("Are you sure? All fields associated with this form will also be removed.", 'cjfm'); ?>" class="cj-confirm btn btn-danger btn-sm margin-5-right"><?php _e('Delete', 'cjfm'); ?></a>
				<?php endif; ?>
			</td>
			</form>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>