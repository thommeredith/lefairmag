<?php
	global $wpdb, $cjfm_email_message, $current_user;
	$invitations_table = $wpdb->prefix.'cjfm_invitations';

	require_once(sprintf('%s/functions/email_messages.php', cjfm_item_path('modules_dir')));


	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'invite_approved' && isset($_GET['user_email'])){
		$user_email = urldecode($_GET['user_email']);
		$inviteinfo = $wpdb->get_row("SELECT * FROM $invitations_table WHERE user_email = '{$user_email}'");
		$invitation_link = cjfm_string(cjfm_generate_url('page_register')).'invitation_token='.$inviteinfo->invitation_key;

		$email_message = cjfm_parse_email('invitation_email_message', cjfm_user_info($current_user->ID));
		$email_message = str_replace('%%invitation_link%%', $invitation_link, $email_message);

		$email_data = array(
			'to' => $user_email,
			'from_name' => cjfm_get_option('from_name'),
			'from_email' => cjfm_get_option('from_email'),
			'subject' => cjfm_get_option('invitation_email_subject'),
			'message' => $email_message,
		);
		cjfm_email($email_data);

		$invite_data = array(
			'invited' => 1
		);
		cjfm_update($invitations_table, $invite_data, 'user_email', $user_email);

		wp_safe_redirect( cjfm_callback_url('cjfm_invitations') );
		exit;

	}

	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'invite_remove' && isset($_GET['user_email'])){
		$wpdb->query("DELETE FROM $invitations_table WHERE user_email = '{$_GET['user_email']}'");
		wp_safe_redirect( cjfm_callback_url('cjfm_invitations_declined') );
		exit;
	}


?>
<table class="enable-search alternate" cellspacing="0" cellpadding="0" width="100%">
	<tbody>
		<tr class="searchable">
			<th colspan="3">
				<h2 class="sub-heading"><?php _e('Invitation Requests ~ Declined', 'cjfm') ?></h2>
			</th>
		</tr>

		<tr>
			<th width="15%"><?php _e('Email Address', 'cjfm') ?></th>
			<th width=""><?php _e('Custom Fields', 'cjfm') ?></th>
			<th width="10%"><?php _e('Actions', 'cjfm') ?></th>
		</tr>

		<?php
			$invitations = $wpdb->get_results("SELECT * FROM $invitations_table WHERE invited = '2' ORDER BY dated ASC");
			if(!empty($invitations)){
				foreach ($invitations as $key => $invite) {

					$user_data_fields = '';
					foreach (unserialize($invite->user_data) as $dkey => $dvalue) {
						if($dkey != 'cjfm_do_register_nonce' && $dkey != 'cjfm_process_registration'){
							$user_data_fields .= '<b>'.$dkey.'</b> &nbsp;&raquo;&nbsp; '.$dvalue.'<br>';
						}
					}

					$approve_link = cjfm_string(cjfm_callback_url('cjfm_invitations_declined')).'cjfm_action=invite_approved&user_email='.urlencode($invite->user_email);
					$decline_link = cjfm_string(cjfm_callback_url('cjfm_invitations_declined')).'cjfm_action=invite_remove&user_email='.urlencode($invite->user_email);
					$action_links = '<a href="'.$approve_link.'">'.__('Approve', 'cjfm').'</a> &nbsp;|&nbsp; <a class="red cj-confirm" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'" href="'.$decline_link.'">'.__('Remove', 'cjfm').'</a>';

					$display[] = '<tr>';
					$display[] = '<td><b>'.$invite->user_email.'</b><br />'.date('M d, Y', strtotime($invite->dated)).'</td>';
					$display[] = '<td>'.$user_data_fields.'</td>';
					$display[] = '<td>'.$action_links.'</td>';
					$display[] = '</tr>';
				}
			}else{
				$display[] = '<tr>';
				$display[] = '<td colspan="3" class="red italic">'.__('No new invitations found.', 'cjfm').'</td>';
				$display[] = '</tr>';
			}

			echo implode('', $display);

		?>

	</tbody>
</table>
