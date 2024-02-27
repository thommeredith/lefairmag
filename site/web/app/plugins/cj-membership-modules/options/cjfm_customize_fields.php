<?php
global $wpdb;

	$cjfm_fields_table = $wpdb->prefix.'cjfm_custom_fields';
	$cjfm_fields_meta_table = $wpdb->prefix.'cjfm_custom_fields_meta';
	$cjfm_forms_table = $wpdb->prefix.'cjfm_custom_forms';

	if(!isset($_GET['form_id']) | $_GET['form_id'] == ''){
		wp_safe_redirect(cjfm_callback_url('cjfm_customize_forms'));
		exit;
	}else{
		$form_id = $_GET['form_id'];
		$form_info = $wpdb->get_row("SELECT * FROM $cjfm_forms_table WHERE id = '{$form_id}'");
	}

	require_once('cjfm_customize_fields_helper.php');

	$yes_no_array = array(
		'yes' => __('Yes', 'cjfm'),
		'no' => __('No', 'cjfm'),
	);

	$fields_meta_keys = array('placeholder_text', 'field_icon');

	$field_type_array['standard_fields'] = array(
		'text' => __('Textbox (Single Line)', 'cjfm'),
		'textarea' => __('Textarea (Multiple Lines)', 'cjfm'),
		'select' => __('Dropdown List', 'cjfm'),
		'multiselect' => __('Dropdown List (multiple)', 'cjfm'),
		'radio' => __('Radio Buttons', 'cjfm'),
		'checkbox' => __('Checkboxes', 'cjfm'),
		'date' => __('Date & Time', 'cjfm'),
		'hidden' => __('Hidden text field with default value', 'cjfm'),
		'file' => __('File Upload', 'cjfm'),
	);
	$field_type_array['WordPress_fields'] = array(
		'first_name' => __('First Name', 'cjfm'),
		'last_name' => __('Last Name', 'cjfm'),
		'display_name' => __('Display Name', 'cjfm'),
		'user_url' => __('Website URL', 'cjfm'),
		'aim' => __('AIM', 'cjfm'),
		'yim' => __('Yahoo IM', 'cjfm'),
		'jabber' => __('Jabber / Google talk', 'cjfm'),
		'description' => __('Biographical Info', 'cjfm'),
	);
	$field_type_array['address_fields'] = array(
		'cjfm_address1' => __('Address Line 1', 'cjfm'),
		'cjfm_address2' => __('Address Line 2', 'cjfm'),
		'cjfm_city' => __('City', 'cjfm'),
		'cjfm_state' => __('State', 'cjfm'),
		'cjfm_zipcode' => __('Zip code', 'cjfm'),
		'cjfm_country' => __('Country', 'cjfm'),
	);
	$field_type_array['social_media_fields'] = array(
		'facebook_url' => __('Facebook Profile URL', 'cjfm'),
		'twitter_url' => __('Twitter Profile URL', 'cjfm'),
		'google_plus_url' => __('Google+ Profile URL', 'cjfm'),
		'youtube_url' => __('Youtube URL', 'cjfm'),
		'vimeo_url' => __('Vimeo URL', 'cjfm'),
	);
	$field_type_array['content_fields'] = array(
		'heading' => __('Heading', 'cjfm'),
		'paragraph' => __('Paragraph Text', 'cjfm'),
		'custom_html' => __('Custom HTML', 'cjfm'),
	);


	// Remove Field
	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'remove_field' && $_GET['id'] != ''){
		$wpdb->query("DELETE FROM $cjfm_fields_table WHERE id = '{$_GET['id']}'");
		$wpdb->query("DELETE FROM $cjfm_fields_meta_table WHERE field_id = '{$_GET['id']}'");
		wp_safe_redirect( cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'form_id='.$_GET['form_id'] );
		exit;
	}

?>

<?php if(!isset($_GET['cjfm_action'])): ?>

<?php
	if(isset($_POST['update_sort_order'])){
		unset($_POST['update_sort_order']);
		foreach ($_POST['field'] as $key => $value) {
			$update_data['required'] = (isset($value['required'])) ? 'yes' : 'no';
			$update_data['profile'] = (isset($value['profile'])) ? 'yes' : 'no';
			$update_data['register'] = (isset($value['register'])) ? 'yes' : 'no';
			$update_data['invitation'] = (isset($value['invitation'])) ? 'yes' : 'no';
			$update_data['enabled'] = (isset($value['enabled'])) ? 'yes' : 'no';
			$update_data['sort_order'] = (isset($value['sort_order'])) ? $value['sort_order'] : 0;
			cjfm_update($cjfm_fields_table, $update_data, 'id', $key);
		}
		echo cjfm_show_message('success', __('Fields updated successfully.', 'cjfm'));
	}
?>
<form action="" method="post">
<table class="enable-search alternate" cellspacing="0" cellpadding="0" width="100%">
	<thead>
		<tr class="">
			<th colspan="12">
				<h2 class="sub-heading">
					<a href="<?php echo cjfm_callback_url('cjfm_customize_forms'); ?>" class="btn btn-small btn-default margin-2-top  margin-10-left alignright"><?php _e('Back', 'cjfm') ?></a>
					<a href="<?php echo cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'form_id='.$_GET['form_id'].'&cjfm_action=add_new_field'; ?>" class="btn btn-small btn-success margin-2-top alignright"><?php _e('Add New Field', 'cjfm') ?></a>
					<input name="update_sort_order" type="submit" class="btn btn-info btn-small alignright margin-2-top margin-10-right" value="<?php _e('Bulk Update', 'cjfm') ?>" />
					<?php echo sprintf(__('%s ~ Registration &amp; Profile Fields', 'cjfm'), $form_info->form_name) ?>
				</h2>
			</th>
		</tr>
		<tr>
			<td><?php _e('Field Type', 'cjfm') ;  ?></td>
			<td><?php _e('Unique ID', 'cjfm') ;  ?></td>
			<td><?php _e('Label', 'cjfm') ;  ?></td>
			<td width="25%"><?php _e('Description', 'cjfm') ;  ?></td>
			<td class="textcenter"><?php _e('Required', 'cjfm') ;  ?></td>
			<td class="textcenter"><?php _e('Profile', 'cjfm') ;  ?></td>
			<td class="textcenter"><?php _e('Register', 'cjfm') ;  ?></td>
			<td class="textcenter"><?php _e('Invitation', 'cjfm') ;  ?></td>
			<td class="textcenter"><?php _e('Enabled', 'cjfm') ;  ?></td>
			<td class="textcenter"><?php _e('Order', 'cjfm') ;  ?></td>
			<td width="15%"><?php _e('Options', 'cjfm') ;  ?></td>
			<td class="textcenter" width="15%"><?php _e('Actions', 'cjfm') ;  ?></td>
		</tr>
	</thead>
	<tbody>

		<?php

			$custom_fields = $wpdb->get_results("SELECT * FROM $cjfm_fields_table WHERE form_id = '{$form_id}' ORDER BY sort_order ASC");

			if(!empty($custom_fields)){
				foreach ($custom_fields as $key => $field) {

					$edit_link = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=edit_field&form_id='.$form_id.'&id='.$field->id;
					$remove_link = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=remove_field&form_id='.$form_id.'&id='.$field->id;
					$default_fields = array('user_login', 'user_pass', 'user_pass_conf', 'user_email', 'user_avatar');


					if($field->field_type == 'custom_html'){
						$field_description = '--';
					}elseif($field->field_type == 'heading'){
						$field_description = '--';
					}elseif($field->field_type == 'paragraph'){
						$field_description = $field->description;
					}else{
						$field_description = $field->description;
					}

					$check_required = ($field->required == 'yes') ? 'checked' : '';
					$check_profile = ($field->profile == 'yes') ? 'checked' : '';
					$check_register = ($field->register == 'yes') ? 'checked' : '';
					$check_invitation = ($field->invitation == 'yes') ? 'checked' : '';
					$check_enabled = ($field->enabled == 'yes') ? 'checked' : '';


					$display[] = '<tr class="">';
					$display[] = '<td>'.$field->field_type.'</td>';
					$display[] = '<td>'.$field->unique_id.'</td>';
					$display[] = '<td width="15%">'.$field->label.'</td>';
					$display[] = '<td width="25%">'.$field_description.'</td>';
					$display[] = '<td class="textcenter capitalize"> <input name="field['.$field->id.'][required]" type="checkbox" '.$check_required.' value="'.$field->required.'" /> </td>';
					$display[] = '<td class="textcenter capitalize"> <input name="field['.$field->id.'][profile]" type="checkbox" '.$check_profile.' value="'.$field->profile.'" /> </td>';
					$display[] = '<td class="textcenter capitalize"> <input name="field['.$field->id.'][register]" type="checkbox" '.$check_register.' value="'.$field->register.'" /> </td>';
					$display[] = '<td class="textcenter capitalize"> <input name="field['.$field->id.'][invitation]" type="checkbox" '.$check_invitation.' value="'.$field->invitation.'" /> </td>';
					$display[] = '<td class="textcenter capitalize"> <input name="field['.$field->id.'][enabled]" type="checkbox" '.$check_enabled.' value="'.$field->enabled.'" /> </td>';
					$display[] = '<td class="textcenter capitalize">
									<input name="field['.$field->id.'][sort_order]" style="width:50px; text-align:center;" type="text" value="'.$field->sort_order.'" />
								  </td>';
					$display[] = '<td width="10%">'.nl2br($field->options).'</td>';

					$default_field_exists = $wpdb->get_row("SELECT count(unique_id) as field_count FROM $cjfm_fields_table WHERE form_id = '{$form_id}' AND unique_id = '{$field->unique_id}'");

					if(in_array($field->unique_id, $default_fields) && $default_field_exists->field_count == 1){
						$action_links = '<td class="textcenter" width="10%">
										<a tabindex="-1" href="'.$edit_link.'">'.__('Update', 'cjfm').'</a>
									  </td>';
					}else{
						$action_links = '<td class="textcenter" width="10%">
										<a tabindex="-1" href="'.$edit_link.'">'.__('Update', 'cjfm').'</a>
										 &nbsp;|&nbsp;
										<a tabindex="-1" href="'.$remove_link.'" class="cj-confirm red" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'">'.__('Remove', 'cjfm').'</a>
									  </td>';
					}
					$display[] = $action_links;
					$display[] = '</tr>';
				}
			}else{
				cjfm_insert_default_form_fields($_GET['form_id']);
				wp_redirect(cjfm_current_url());
				exit;
			}
			echo implode('', $display);
		?>

	</tbody>
</table>
</form>
<?php endif; ?>
<?php if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'add_new_field'): ?>

	<?php
		if(isset($_POST['select_field_type'])){
			$field_type = $_POST['field_type'];

			if($field_type != 'Select Field Type'){
				$location = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=add_new_field&form_id='.$_GET['form_id'].'&field_type='.$field_type;
				wp_safe_redirect( $location, $status = 302 );
				exit;
			}else{
				echo cjfm_show_message('error', __('Please select a field type', 'cjfm'));
			}
		}

		$form_options['add_new_form_fields'] = array(
			array(
			    'type' => 'sub-heading',
			    'id' => '',
			    'label' => '',
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => __('Add New Field', 'cjfm'),
			    'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'groupselect',
			    'id' => 'field_type',
			    'label' => __('Field Type', 'cjfm'),
			    'info' => '',
			    'suffix' => '',
			    'prefix' => '',
			    'default' => (isset($_GET['field_type'])) ? $_GET['field_type'] : '',
			    'options' => $field_type_array, // array in case of dropdown, checkbox and radio buttons
			),
			array(
			    'type' => 'submit',
			    'id' => 'select_field_type',
			    'label' => __('Continue', 'cjfm'),
			    'info' => '',
			    'suffix' => '<a href="'.cjfm_callback_url('cjfm_customize_fields').'" class="button-secondary margin-10-left">'.__('Cancel', 'cjfm').'</a>',
			    'prefix' => '',
			    'default' => '',
			    'options' => $field_type_array, // array in case of dropdown, checkbox and radio buttons
			),
		);

		if(!isset($_GET['field_type'])){
			echo '<form action="" method="post" enctype="multipart/form-data">';
			echo cjfm_admin_form_raw($form_options['add_new_form_fields']);
			echo '</form>';

		}
	?>


	<?php
		if(isset($_GET['field_type'])):
			$field_type = $_GET['field_type'];

			foreach ($field_type_array as $key => $value) {
				foreach ($value as $key1 => $value1) {
					if($field_type == $key1){
						$field_type_heading = $value1;
					}
				}
			}

			if(isset($_POST['add_new_field'])){

				$errors = null;

				$unique_id_check = $wpdb->get_row("SELECT * FROM $cjfm_fields_table WHERE unique_id = '{$_POST['unique_id']}' AND form_id = '{$_GET['form_id']}'");

				if($_POST['unique_id'] == '' || $_POST['label'] == ''){
					$errors[] = __('Missing required fields', 'cjfm');
				}

				if(!empty($unique_id_check)){
					$errors[] = __('Unique name already exists', 'cjfm');
				}

				if(!preg_match('/^[a-zA-Z0-9_-]*$/', $_POST['unique_id'])){
					$errors[] = __('Invalid unique name', 'cjfm');
				}

				if(!preg_match('/^[0-9]*$/', $_POST['sort_order'])){
					$errors[] = __('Invalid sort order, must be numeric', 'cjfm');
				}

				$options_field = array('radio', 'checkbox', 'select', 'multiselect');
				if(in_array($_POST['field_type'], $options_field) && $_POST['options'] == 'NA' || $_POST['options'] == ''){
					$errors[] = __('Specify options, each option per line.', 'cjfm');
				}

				if(!is_null($errors)){
					echo '<div class="margin-30-top">'.cjfm_show_message('error', implode('<br />', $errors)).'</div>';
				}else{
					$field_data = array(
						'unique_id' => $_POST['unique_id'],
						'form_id' => $_POST['form_id'],
						'field_type' => $_POST['field_type'],
						'label' => stripcslashes($_POST['label']),
						'description' => stripcslashes($_POST['description']),
						'required' => $_POST['required'],
						'profile' => $_POST['profile'],
						'register' => $_POST['register'],
						'invitation' => $_POST['invitation'],
						'enabled' => $_POST['enabled'],
						'sort_order' => $_POST['sort_order'],
						'options' => $_POST['options'],
					);
					$field_id = cjfm_insert($cjfm_fields_table, $field_data);

					foreach ($fields_meta_keys as $mkey => $mvalue) {
						cjfm_update_field_meta($field_id, $mvalue, $_POST[$mvalue]);
					}

					wp_safe_redirect(cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'form_id='.$_GET['form_id']);
					exit;

				}
			}

			$fields = cjfm_custom_fields_helper($field_type, $edit_field = null);

			echo '<form action="" method="post" enctype="multipart/form-data">';
			cjfm_admin_form_raw($fields);
			echo '</form>';

		endif;

	?>

<?php endif; ?>



<?php if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'edit_field'): ?>

	<?php

		$edit_field = $wpdb->get_row("SELECT * FROM $cjfm_fields_table WHERE id = '{$_GET['id']}'");

		$remove_link = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=remove_field&id='.$edit_field->id;

		$module_url = cjfm_callback_url('cjfm_customize_fields');


		if(isset($_POST['update_field'])){
			$errors = null;

			$unique_id_check = $wpdb->get_row("SELECT * FROM $cjfm_fields_table WHERE unique_id = '{$_POST['unique_id']}' AND form_id = '{$_GET['form_id']}'");

			if($_POST['unique_id'] == '' || $_POST['label'] == ''){
				$errors[] = __('Missing required fields', 'cjfm');
			}

			if(!empty($unique_id_check) && $_POST['unique_id'] != $unique_id_check->unique_id){
				$errors[] = __('Unique name already exists', 'cjfm');
			}

			if(!preg_match('/^[a-zA-Z0-9_-]*$/', $_POST['unique_id'])){
				$errors[] = __('Invalid unique name', 'cjfm');
			}

			if(!preg_match('/^[0-9]*$/', $_POST['sort_order'])){
				$errors[] = __('Invalid sort order, must be numeric', 'cjfm');
			}

			$options_field = array('radio', 'checkbox', 'select', 'multiselect');
			if(in_array($_POST['field_type'], $options_field) && $_POST['options'] == 'NA' || $_POST['options'] == ''){
				$errors[] = __('Specify options, each option per line.', 'cjfm');
			}

			if(!is_null($errors)){
				echo '<div class="margin-30-top">'.cjfm_show_message('error', implode('<br />', $errors)).'</div>';
			}else{
				$field_data = array(
					'unique_id' => $_POST['unique_id'],
					'form_id' => $_POST['form_id'],
					'field_type' => $_POST['field_type'],
					'label' => stripcslashes($_POST['label']),
					'description' => stripcslashes($_POST['description']),
					'required' => $_POST['required'],
					'profile' => $_POST['profile'],
					'register' => $_POST['register'],
					'invitation' => $_POST['invitation'],
					'enabled' => $_POST['enabled'],
					'sort_order' => $_POST['sort_order'],
					'options' => $_POST['options'],
				);
				cjfm_update($cjfm_fields_table, $field_data, 'id', $_GET['id']);

				foreach ($fields_meta_keys as $mkey => $mvalue) {
					cjfm_update_field_meta($_GET['id'], $mvalue, $_POST[$mvalue]);
				}

				$location = cjfm_string(cjfm_callback_url('cjfm_customize_fields')).'cjfm_action=edit_field&form_id='.$_GET['form_id'].'&id='.$_GET['id'].'&cjfm_msg=field-saved';
				wp_safe_redirect( $location, $status = 302 );
				exit;

			}
		}

		if(isset($_GET['cjfm_msg']) && $_GET['cjfm_msg'] == 'field-saved'){
			echo cjfm_show_message('success', __('Field updated successfully.', 'cjfm'));
		}

		$edit_field = $wpdb->get_row("SELECT * FROM $cjfm_fields_table WHERE id = '{$_GET['id']}'");

		$fields = cjfm_custom_fields_helper($edit_field->field_type, $edit_field);

		echo '<form action="" method="post" enctype="multipart/form-data">';
		cjfm_admin_form_raw($fields);
		echo '</form>';

	?>

<?php endif; ?>