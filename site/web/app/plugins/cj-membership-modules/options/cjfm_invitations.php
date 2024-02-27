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
		cjfm_update($invitations_table, $invite_data, 'id', $inviteinfo->id);
		wp_safe_redirect( cjfm_callback_url('cjfm_invitations'), $status = 302 );
		exit;

	}

	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'invite_declined' && isset($_GET['user_email'])){


		if(isset($_POST['do_decline_request'])){

			$user_email = urldecode($_GET['user_email']);

			$inviteinfo = $wpdb->get_row("SELECT * FROM $invitations_table WHERE user_email = '{$user_email}'");

			$invite_data = array(
				'invited' => 2
			);
			cjfm_update($invitations_table, $invite_data, 'id', $inviteinfo->id);

			$email_data = array(
				'to' => $user_email,
				'from_name' => cjfm_get_option('from_name'),
				'from_email' => cjfm_get_option('from_email'),
				'subject' => $_POST['email_subject'],
				'message' => $_POST['email_message'],
			);
			cjfm_email($email_data);

			wp_safe_redirect( cjfm_callback_url('cjfm_invitations'), $status = 302 );
			exit;

		}


		$form_options['decline_account'] = array(
			array(
				'type' => 'heading',
				'id' => 'declne_invite_heading',
				'label' => '',
				'info' => '',
				'suffix' => '',
				'prefix' => '',
				'default' => __('Decline Invitation Request', 'cjfm'),
				'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
				'type' => 'text-readonly',
				'id' => 'user_email',
				'label' => __('Email Address', 'cjfm'),
				'info' => '',
				'suffix' => '',
				'prefix' => '',
				'default' => urldecode($_GET['user_email']),
				'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
				'type' => 'text',
				'id' => 'email_subject',
				'label' => __('Email Subject', 'cjfm'),
				'info' => '',
				'suffix' => '',
				'prefix' => '',
				'default' => sprintf(__('Your %s invitation request is declined.', 'cjfm'), get_bloginfo( 'name' )),
				'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
				'type' => 'wysiwyg',
				'id' => 'email_message',
				'label' => __('Email Message', 'cjfm'),
				'info' => __('Dynamic variables will <b class="bold red">not</b> work in this email message.', 'cjfm'),
				'suffix' => '',
				'prefix' => '',
				'default' => $cjfm_email_message['invitation-declined-message'],
				'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
			array(
				'type' => 'submit',
				'id' => 'do_decline_request',
				'label' => __('Decline Invitation Request', 'cjfm'),
				'info' => '',
				'suffix' => '<a href="'.cjfm_callback_url('cjfm_invitations').'" class="button-secondary margin-5-left">'.__('Cancel', 'cjfm').'</a>',
				'prefix' => '',
				'default' => '',
				'options' => '', // array in case of dropdown, checkbox and radio buttons
			),
		);
		echo '<form action="" method="post" class="margin-30-bottom">';
		cjfm_admin_form_raw($form_options['decline_account']);
		echo '</form>';

	}

	if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'remove-invite' && $_GET['user_email'] != ''){
		$wpdb->query("DELETE FROM $invitations_table WHERE user_email = '{$_GET['user_email']}'");
		$location = cjfm_callback_url('cjfm_invitations');
		wp_safe_redirect($location);
		exit;
	}


?>
<table class="enable-search alternate" cellspacing="0" cellpadding="0" width="100%">
	<tbody>
		<tr class="searchable">
			<th colspan="2">
				<h2 class="sub-heading"><?php _e('Invitation Requests ~ Awaiting Approval', 'cjfm') ?></h2>
			</th>
		</tr>

		<tr>
			<th width="20%"><?php _e('Email Address', 'cjfm') ?></th>
			<th width=""><?php _e('Custom Fields', 'cjfm') ?></th>
		</tr>

		<?php
			$invitations = $wpdb->get_results("SELECT * FROM $invitations_table WHERE invited = '0' ORDER BY dated ASC");
			if(!empty($invitations)){
				foreach ($invitations as $key => $invite) {

					$user_data_fields = '';
					foreach (unserialize($invite->user_data) as $dkey => $dvalue) {
						if($dkey != 'cjfm_do_register_nonce' && $dkey != 'cjfm_process_registration'){
							$user_data_fields .= '<b>'.$dkey.'</b> &nbsp;&raquo;&nbsp; '.$dvalue.'<br>';
						}
					}

					$approve_link = cjfm_string(cjfm_callback_url('cjfm_invitations')).'cjfm_action=invite_approved&user_email='.urlencode($invite->user_email);
					$decline_link = cjfm_string(cjfm_callback_url('cjfm_invitations')).'cjfm_action=invite_declined&user_email='.urlencode($invite->user_email);
					$remove_link = cjfm_string(cjfm_callback_url('cjfm_invitations')).'cjfm_action=remove-invite&user_email='.urlencode($invite->user_email);

					$action_links = '<a class="margin-5-right btn btn-success btn-mini" href="'.$approve_link.'">'.__('Approve', 'cjfm').'</a>';
					$action_links .= '<a class="margin-5-right btn btn-warning btn-mini cj-confirm" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'" href="'.$decline_link.'">'.__('Decline', 'cjfm').'</a>';
					$action_links .= '<a class="margin-5-right btn btn-danger btn-mini cj-confirm" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'" href="'.$remove_link.'">'.__('Remove', 'cjfm').'</a>';

					$display[] = '<tr>';
					$display[] = '<td><b>'.$invite->user_email.'</b><br />'.date('M d, Y', strtotime($invite->dated)).'<p>'.$action_links.'</p></td>';
					$display[] = '<td>'.$user_data_fields.'</td>';
					$display[] = '</tr>';
				}
			}else{
				$display[] = '<tr>';
				$display[] = '<td colspan="2" class="red italic">'.__('No new invitations found.', 'cjfm').'</td>';
				$display[] = '</tr>';
			}

			echo implode('', $display);

		?>

	</tbody>
</table>
