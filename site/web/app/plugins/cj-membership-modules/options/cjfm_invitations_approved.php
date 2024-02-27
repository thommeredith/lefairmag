<?php
$users = get_users(array(
	'meta_key' => 'cjfm_invited_on',
	'meta_value' => '',
	'meta_compare' => '<>',
	'orderby' => 'meta_value',
	'order' => 'DESC',
));
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2"><?php _e('Invited Users', 'cjfm') ?></th>
		</tr>
		<tr>
			<td width="20%"><?php _e('Invited On', 'cjfm') ?></td>
			<td><?php _e('User Info', 'cjfm') ?></td>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($users)): ?>
		<?php foreach ($users as $key => $user): $user_info = cjfm_user_info($user->ID); ?>
			<tr>
				<td>
					<p class="margin-0">
						<?php echo cjfm_wp_date($user_info['cjfm_invited_on'], true); ?>
					</p>
				</td>
				<td>
					<p class="margin-0">
					<b><?php echo $user_info['display_name']; ?></b> (<?php echo $user_info['user_login']; ?>)<br><?php echo $user_info['user_email']; ?>
					<br>
					<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID); ?>"><?php _e('Edit Profile', 'cjfm') ?></a>
					</p>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="2" class="red italic"><?php _e('You have not invited any users yet.', 'cjfm') ?></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>