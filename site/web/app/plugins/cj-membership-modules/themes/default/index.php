<?php
	$logo_url = cjfm_get_option('auth_logo');
	$page_password_url = cjfm_string(cjfm_current_url('only')).'show=forgot-password';
	$page_login_url = cjfm_string(cjfm_current_url('only')).'show=login';
	$page_register_url = cjfm_string(cjfm_current_url('only')).'show=register';

	// redirect fixes
	if(!isset($_GET['show']) && isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'rp'){
		$cjfm_current_url = cjfm_current_url();
		$redirect = cjfm_string($cjfm_current_url).'show=forgot-password';
		wp_safe_redirect($redirect);
		exit;
	}

	if(!isset($_GET['show']) && isset($_GET['cjfm_verify']) && isset($_GET['key'])){
		$cjfm_current_url = cjfm_current_url();
		$redirect = cjfm_string($cjfm_current_url).'show=register';
		wp_safe_redirect($redirect);
		exit;
	}

	if(!isset($_GET['show']) || isset($_GET['show']) && $_GET['show'] == 'login'){
		$heading = __('Existing User? Sign In', 'cjfm');
		$content = cjfm_get_option('content_login');
		$content_links = '<a href="'.$page_password_url.'">'.__('Forgot password?', 'cjfm').'</a>';
		$content_links .= '<span class="bull">&bull;</span>';
		$content_links .= '<a href="'.$page_register_url.'">'.__('New User? Create account', 'cjfm').'</a>';

		$social_heading = '<h2 class="content-sub-title">'.__('Connect via: ', 'cjfm').'</h2>';
		// $social_heading = '';
		$social_content = '<div class="social-login">'.cjfm_do_shortcode(cjfm_get_option('content_social_shortcodes')).'</div>';
	}
	if(isset($_GET['show']) && $_GET['show'] == 'register'){
		$heading = __('New User? Create account', 'cjfm');
		$content = cjfm_get_option('content_register');
		$content_links = '<a href="'.$page_login_url.'">'.__('Existing User? Sign In', 'cjfm').'</a>';
		$content_links .= '<span class="bull">&bull;</span>';
		$content_links .= '<a href="'.$page_password_url.'">'.__('Forgot password?', 'cjfm').'</a>';

		$social_heading = '<h2 class="content-sub-title">'.__('Connect via: ', 'cjfm').'</h2>';
		// $social_heading = '';
		$social_content = '<div class="social-login">'.cjfm_do_shortcode(cjfm_get_option('content_social_shortcodes')).'</div>';
	}
	if(isset($_GET['show']) && $_GET['show'] == 'forgot-password'){
		$heading = __('Forgot password?', 'cjfm');
		$content = cjfm_get_option('content_password');
		$content_links = '<a href="'.$page_login_url.'">'.__('Existing User? Sign In', 'cjfm').'</a>';
		$content_links .= '<span class="bull">&bull;</span>';
		$content_links .= '<a href="'.$page_register_url.'">'.__('New User? Create account', 'cjfm').'</a>';

		$social_heading = '';
		$social_content = '';
	}

	$icon_position_top = str_replace('px', '', cjfm_get_option('auth_page_icon_position_top')).'px';
	$icon_position_right = str_replace('px', '', cjfm_get_option('auth_page_icon_position_right')).'px';

	$body_background = cjfm_get_option('auth_body_background');
	$body_color = cjfm_get_option('auth_body_color');
	$body_link_color = cjfm_get_option('auth_body_link_color');
	$border_radius = cjfm_get_option('auth_border_radius');
	$panel_background_color = cjfm_get_option('auth_panel_background');
	$panel_background_color = ($panel_background_color == '') ? 'transparent' : $panel_background_color;
	$panel_border_color = cjfm_get_option('auth_panel_border_color');
	$panel_text_color = cjfm_get_option('auth_panel_color');
	$panel_link_color = cjfm_get_option('auth_panel_link_color');
	$panel_button_color = cjfm_get_option('auth_panel_button_color');
	$panel_button_text_color = cjfm_get_option('auth_panel_button_text_color');
	$google_font_stylesheet = (!is_null(cjfm_google_fonts_string())) ? '<link href="http://fonts.googleapis.com/css?family='.cjfm_google_fonts_string().'" rel="stylesheet" type="text/css">' : '';
	$auth_page_font = cjfm_get_option('auth_body_font');
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo the_title(); ?></title>
	<?php echo $google_font_stylesheet; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo cjfm_item_path('item_url').'/assets/css/cjfm.css' ?>">
	<link rel="stylesheet" href="<?php echo cjfm_item_path('item_url').'/themes/default/css/style.css' ?>">
	<style type="text/css">
		body{
			background-color: <?php echo $body_background['color']; ?>;
			background-image: url('<?php echo $body_background['image']; ?>');
			background-size: <?php echo $body_background['bg_size']; ?>;
			background-position: <?php echo $body_background['bg_position'] ?>;
			background-attachment: <?php echo $body_background['bg_attachment'] ?>;
			background-repeat: <?php echo $body_background['bg_repeat'] ?>;
			color: <?php echo $body_color; ?>;
			font-family: <?php echo str_replace('+', ' ', $auth_page_font['family']); ?>;
			font-weight: <?php echo $auth_page_font['weight'] ?>;
			font-size: <?php echo $auth_page_font['size'] ?>;
			font-style: <?php echo $auth_page_font['style'] ?>;
			line-height: <?php echo $auth_page_font['line-height'] ?>px;
		}
		body a{
			color: <?php echo $body_link_color; ?>
		}
		.content-panel{
			background: <?php echo $panel_background_color; ?>;
			border: 1px solid <?php echo $panel_border_color; ?>;
			color: <?php echo $panel_text_color;  ?>;
			font-family: <?php echo str_replace('+', ' ', $auth_page_font['family']); ?>;
			font-weight: <?php echo $auth_page_font['weight'] ?>;
			font-size: <?php echo $auth_page_font['size'] ?>;
			font-style: <?php echo $auth_page_font['style'] ?>;
		}
		.content-panel .content-title{
			border-bottom: 1px solid <?php echo $panel_border_color; ?>;
		}
		.content-panel .content-sub-title{
			border-top: 1px solid <?php echo $panel_border_color; ?>;
		}
		.social-login{
			border-top: 1px solid <?php echo $panel_border_color; ?>;
		}
		.content-panel a, .content-panel a:hover{
			color: <?php echo $panel_link_color;  ?>;
		}
		.content-panel .content-title,
		.content-panel .content-sub-title{
			background: <?php echo cjfm_color_brightness($panel_background_color, -7); ?>;
		}
		.content-panel button[type="submit"],
		.content-panel input[type="submit"]{
			background: <?php echo $panel_button_color; ?>;
			border-color: <?php echo cjfm_color_brightness($panel_button_color, -40); ?>;
			color: <?php echo $panel_button_text_color;  ?>;
		}
		.content-panel button[type="submit"]:hover,
		.content-panel input[type="submit"]:hover{
			background: <?php echo cjfm_color_brightness($panel_button_color, -20); ?>;
			border-color: <?php echo cjfm_color_brightness($panel_button_color, -40); ?>;
			color: <?php echo $panel_button_text_color;  ?>;
		}
		.control-group .cjfm-relative i.fa{
			top: <?php echo $icon_position_top; ?>;
			right: <?php echo $icon_position_right; ?>;
		}
		.content-panel, .btn, .cjfm-social-btn, button,
		input[type="submit"],
		input[type="text"],
		input[type="email"],
		input[type="number"],
		input[type="password"],
		textarea{
			-webkit-border-radius: <?php echo $border_radius; ?>;
			-moz-border-radius: <?php echo $border_radius; ?>;
			border-radius: <?php echo $border_radius; ?>;
			font-family: <?php echo str_replace('+', ' ', $auth_page_font['family']); ?>;
			font-weight: <?php echo $auth_page_font['weight'] ?>;
			font-size: <?php echo $auth_page_font['size'] ?>;
		}
		.control-group i.fa{
			top: 45px;
			right: 13px;
		}
		/* Custom CSS Code */
		<?php echo cjfm_get_option('auth_custom_css'); ?>

	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?php echo cjfm_item_path('item_url').'/themes/default/js/theme.js' ?>"></script>
	<?php if(cjfm_get_option('plugin_ajax') == 'yes'): ?>
	<script src="<?php echo cjfm_item_path('item_url').'/assets/js/cjfm-ajax.js' ?>"></script>
	<?php endif; ?>

</head>
<body class="auth-page default">
	<div id="logo" class="wrap">
		<?php if(is_array($logo_url)): ?>
			<a href="<?php echo site_url(); ?>">
				<img src="<?php echo $logo_url[0]; ?>">
			</a>
		<?php else: ?>
				<h2 class="logo-text">
					<a href="<?php echo site_url(); ?>">
						<?php echo get_bloginfo('name'); ?>
					</a>
				</h2>
				<div class="tag-line">
					<?php echo get_bloginfo('description'); ?>
				</div>
		<?php endif; ?>
	</div>
	<div class="wrap">
		<div class="content-panel">
			<h2 class="content-title"><?php echo $heading; ?></h2>
			<div class="content-body">
				<?php echo cjfm_do_shortcode($content); ?>
			</div>

			<?php if(cjfm_get_option('content_social_shortcodes') != ''): ?>
			<?php echo $social_heading; ?>
			<?php echo $social_content; ?>
			<?php endif; ?>
		</div>
	</div>
	<div id="content-links" class="wrap">
		<?php echo $content_links; ?>
	</div>
	<?php
		echo '<span id="cjfm-ajax-url" style="display:none;">'.admin_url( 'admin-ajax.php' ).'</span>';
	?>
	<div style="display: none;">
		<span class="weak_string"><?php echo _e('Weak', 'cjfm'); ?></span>
		<span class="medium_string"><?php echo _e('Medium', 'cjfm'); ?></span>
		<span class="strong_string"><?php echo _e('Strong', 'cjfm'); ?></span>
	</div>

	<?php
		if(isset($_GET['cjfm_action']) && $_GET['cjfm_action'] == 'awm'){
			$msg = __("Your account is being reviewed. Please check back later.", 'cjfm');
			echo '<script type="text/javascript">alert("'.$msg.'");</script>';
		}
	?>

</body>
</html>
<?php die(); ?>