<?php
//die('//Inspecting header 1');
global $porto_settings, $porto_layout;
?>
<header id="header" class="header-separate header-1 <?php echo $porto_settings['search-size'] ?> sticky-menu-header">
    <?php if ($porto_settings['show-header-top']) : ?>
        <div class="header-top">
            <div class="container">
                <div class="header-left">
                    <?php
                    // show currency and view switcher
                    $currency_switcher = porto_currency_switcher();
                    $view_switcher = porto_view_switcher();

                    if ($currency_switcher || $view_switcher)
                        echo '<div class="switcher-wrap">';

                    echo $currency_switcher;

                    if ($currency_switcher && $view_switcher)
                        echo '<span class="gap switcher-gap">|</span>';

                    echo $view_switcher;

                    if ($currency_switcher || $view_switcher)
                        echo '</div>';

                    $top_nav = porto_top_navigation();

                    if ($porto_settings['welcome-msg'] && $top_nav)
                        echo '<span class="gap">|</span>';

                    //echo $top_nav;
                    ?>


                </div>
                <div class="header-right">
                    <?php
                    // show welcome message and top navigation
                    // show social links
                    echo porto_header_socials();
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="header-main">
        <div class="container">
            <div class="header-left">
                <?php
                // show logo
                $logo = porto_logo();
                echo $logo;
                ?>
            </div>
            <div class="header-center">
                <?php
                // show search form
                //echo porto_search_form();
                // show mobile toggle
                ?>

            </div>
            <div class="header-right">
                <div class="shortcode"><?php //echo do_shortcode('[userpro template=login]');  ?></div>

                <div class="cntrbx">
<?php if (is_user_logged_in()) { ?>
                        <div class="header-contact">
                            <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php _e('My Account', 'woothemes'); ?>"><?php _e('My Account', 'woothemes'); ?></a> | 
                            <a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
                            
                        </div>
<?php } else {
    ?>
                        <div class="header-contact">
                        <?php $contact_info = $porto_settings['header-contact-info'];

if ($contact_info)
   echo '<div class="header-contact">' . do_shortcode($contact_info) . '</div>';
    ?>
                        <?php /*?><a href="/my-account/"><i class="fa fa-sign-in"></i> Log In</a> <span class="gap"> </span><a href="/register/"><i class="fa fa-key"></i> Register</a><?php */?>
                        </div>
                        <!--<li><a href="<?php //echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php //_e('Login / Register','woothemes'); ?>"><?php //_e('Login / Register','woothemes'); ?></a></li>-->

<?php } ?>
                </div>




                <div class="show-menu-search search-popup">
<?php
// show search form
echo porto_search_form();
// show mobile toggle
?>
                    <a class="mobile-toggle"><i class="fa fa-reorder"></i></a>
                </div>

<?php
$minicart = porto_minicart();
?>
                <div class="<?php if ($minicart) echo 'header-minicart' . str_replace('minicart', '', $porto_settings['minicart-type']) ?>">

<?php
// show contact info and mini cart
$contact_info = $porto_settings['header-contact-info'];

//if ($contact_info)
   // echo '<div class="header-contact">' . do_shortcode($contact_info) . '</div>';

echo $minicart;
?>


                </div>


<?php
get_template_part('header/header_tooltip');
?>

            </div>
        </div>
    </div>

<?php
// check main menu
$main_menu = porto_main_menu();
if ($main_menu) :
    ?>
        <div class="main-menu-wrap<?php echo ($porto_settings['menu-type'] ? ' ' . $porto_settings['menu-type'] : '') ?>">
            <div id="main-menu" class="container <?php echo $porto_settings['menu-align'] ?><?php echo $porto_settings['show-sticky-menu-custom-content'] ? '' : ' hide-sticky-content' ?>">
        <?php if ($porto_settings['show-sticky-logo']) : ?>
                    <div class="menu-left">
                    <?php
                    // show logo
                    $logo = porto_logo(true);
                    echo $logo;
                    ?>
                    </div>
                    <?php endif; ?>
                <div class="menu-center">
                <?php
                // show main menu
                echo $main_menu;
                ?>
                </div>
                    <?php if ($porto_settings['show-sticky-searchform'] || $porto_settings['show-sticky-minicart']) : ?>
                    <div class="menu-right">
                    <?php
                    // show search form
                    echo porto_search_form();

                    // show mini cart
                    echo porto_minicart();
                    ?>
                    </div>
                    <?php endif; ?>
            </div>
        </div>
            <?php endif; ?>
            
            <style>
            	 div.topbackimage {
					position: relative;
				    left: -84.5px;
				    box-sizing: border-box;
				    width: 1349px;
				}
            </style>
</header>