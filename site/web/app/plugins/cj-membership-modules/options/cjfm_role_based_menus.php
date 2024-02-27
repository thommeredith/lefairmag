<?php
global $wp_roles;
$menu_locations = get_registered_nav_menus();
$saved_menus = cjfm_navigation_menus();
$roles = $wp_roles->role_names;
$roles['non-user'] = __('Visitor', 'cjfm');

$cjfm_role_menus = cjfm_get_option('cjfm_role_menus');

if(isset($_POST['cjfm_save_menus'])){
	cjfm_update_option('cjfm_role_menus', $_POST['data']);
	$location = cjfm_string(cjfm_callback_url()).'msg=saved';
	wp_safe_redirect( $location );
	exit;
}
if(isset($_GET['msg']) && $_GET['msg'] == 'saved'){
	echo cjfm_show_message('success', __('Menu options saved successfully.', 'cjfm'));
}

?><form action="" method="post">
<table width="100%" cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th colspan="3"><h2 class="main-heading"><?php _e('Role Based Menus', 'cjfm') ?></h2></th>
	</tr>
	<tr>
		<td width="15%"><?php _e('User Role', 'cjfm') ?></td>
		<td width="25%"><?php _e('Menu Location', 'cjfm') ?></td>
		<td width=""><?php _e('Show Menu', 'cjfm') ?></td>
	</tr>
</thead>
<tbody>
	<?php
		$count = 0;
		foreach ($roles as $rkey => $rvalue):
		$count++;
	?>
	<tr>
		<td>
			<?php echo $rvalue; ?>
			<input name="data[<?php echo $rkey; ?>][user_role]" type="hidden" value="<?php echo $rkey; ?>" />
		</td>
		<td>
			<select name="data[<?php echo $rkey; ?>][menu_location]">
				<?php
				if(!empty($menu_locations)){
					echo '<option value="">'.__('Select Menu Location', 'cjfm').'</option>';
					foreach ($menu_locations as $mkey => $mvalue) {
						if(is_array($cjfm_role_menus) && $cjfm_role_menus[$rkey]['menu_location'] == $mkey){
							echo '<option selected value="'.$mkey.'">'.$mvalue.'</option>';
						}else{
							echo '<option value="'.$mkey.'">'.$mvalue.'</option>';
						}

					}
				}else{
					echo '<option value="">'.__('No menu locations found', 'cjfm').'</option>';
				}
				?>
			</select>
		</td>
		<td>
			<select name="data[<?php echo $rkey; ?>][menu]">
				<?php
				if(!empty($saved_menus)){
					echo '<option value="">'.__('Select Menu', 'cjfm').'</option>';
					foreach ($saved_menus as $skey => $svalue) {
						if(is_array($cjfm_role_menus) && $cjfm_role_menus[$rkey]['menu'] == $skey){
							echo '<option selected value="'.$skey.'">'.$svalue.'</option>';
						}else{
							echo '<option value="'.$skey.'">'.$svalue.'</option>';
						}

					}
				}else{
					echo '<option value="">'.__('No menus found', 'cjfm').'</option>';
				}
				?>
			</select>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="3">
			<button name="cjfm_save_menus" type="submit" class="button-primary"><?php _e('Save Settings', 'cjfm') ?></button>
		</td>
	</tr>
</tbody>
</table>
</form>