<?php
/*
Plugin Name: Content links
Description: Content links plugin create linking between content texts of your website
Version: 1.2.2
Text Domain: content-links 
Domain Path: /languages
*/

define('CLS_BASE_DIR', dirname(__FILE__) . '/');

include CLS_BASE_DIR . 'assets/core.php';
include CLS_BASE_DIR . 'assets/api.php';
// session start
if (@session_id() == '') {
    @session_start();
}
cl_core::deleteFreeVersion();
cl_core::initialize();

register_activation_hook( __FILE__, array('cl_core', 'install' ));
register_deactivation_hook( __FILE__, array('cl_core', 'deactivate' ));
register_uninstall_hook( __FILE__, array( 'cl_core', 'uninstall' ) );