<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="row" id="customer_login">

	<div class="col-sm-4">

<?php else : ?>

<div class="row">

    <div class="col-sm-4 col-sm-offset-4">

<?php endif; ?>

        <div class="align-left">
            <div class="box-content">
                
                <div class="wpb_wrapper">
			<h3 class="mk-fancy-title responsive-align-center avantgarde-title center-align " id="fancy-title-12"><span class="fancy-title-span pointed"><span style="font-size: 10pt;"><?php _e( 'LEFAIR', 'woocommerce' ); ?></span></span></h3>
<h1 style="text-align: center;" class="main-font"><?php _e( 'Login', 'woocommerce' ); ?></h1>

		</div>
				<div class="cjfm-form">
                <form method="post" class="login">

                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <div class="form-row form-row-wide control-group  textbox">
                    	
                        <label for="username"><?php _e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <span class="cjfm-relative">
                        <input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                        <i class="fa fa-user"></i>
                        </span>
                    </div>
                    <div class="form-row form-row-wide control-group  textbox">
                        <label for="password" class="clearfix"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span>
                            
                        </label>
                        <span class="cjfm-relative">
                        <input class="input-text" type="password" name="password" id="password" />
                        <i class="fa fa-lock"></i>
                        </span>
                    </div>
                    
                    

                    <?php do_action( 'woocommerce_login_form' ); ?>
                    
                    
                     <div class="form-row clearfix control-group submit-button">
                        
                        
                        <!--<input type="submit" class="submit cjfm-btn cjfm-btn-default" name="login" value="<?php //esc_attr_e( 'Login', 'woocommerce' ); ?>" />-->
                        
                        <button class="submit cjfm-btn cjfm-btn-default " name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>" type="submit">Login</button>
                        
                        
                       
                    </div>

                    <p class="form-row clearfix">
                        <?php wp_nonce_field( 'woocommerce-login' ); ?>
                        
                        
                        
                        
                        <label for="rememberme" class="inline pt-left">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
                        </label>
                        <span class="lost_password pt-right">
                                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
                            </span>
                        
                    </p>

                    <?php do_action( 'woocommerce_login_form_end' ); ?>

                </form>
                
                </div>
            </div>
        </div>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	</div>

	<div class="col-sm-4">

        <div class="featured-box align-left">
            <div class="box-content">

                <h2><?php _e( 'Register', 'woocommerce' ); ?></h2>

                <form method="post" class="register">

                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="form-row form-row-wide">
                            <label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                        </p>

                    <?php endif; ?>

                    <p class="form-row form-row-wide">
                        <label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
                    </p>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="form-row form-row-wide">
                            <label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="password" class="input-text" name="password" id="reg_password" />
                        </p>

                    <?php endif; ?>

                    <!-- Spam Trap -->
                    <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

                    <?php do_action( 'woocommerce_register_form' ); ?>
                    <?php do_action( 'register_form' ); ?>

                    <p class="form-row clearfix">
                        <?php wp_nonce_field( 'woocommerce-register' ); ?>
                        <input type="submit" class="button btn-lg pt-right" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" />
                    </p>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>
            </div>
        </div>

	</div>

</div>

<?php else : ?>

    </div>

</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
