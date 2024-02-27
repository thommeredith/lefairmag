<?php

add_action('wp_enqueue_scripts', 'porto_child_css', 1001);
define('porto_child_dir', get_stylesheet_directory());                  // template directory
define('porto_child_uri', get_stylesheet_directory_uri());              // template directory uri
// Load CSS

function porto_child_css() {
    // porto child theme styles
    wp_deregister_style('styles-child');
    wp_register_style('styles-child', porto_child_uri . '/style.css');
    wp_register_style('my-css-child', porto_child_uri . '/css/my_css_web.css');
    wp_enqueue_style('styles-child');
    wp_enqueue_style('my-css-child');
	    wp_register_style('main-css', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('main-css');

    if (is_rtl()) {
        wp_deregister_style('styles-child-rtl');
        wp_register_style('styles-child-rtl', porto_child_uri . '/style_rtl.css');
        wp_enqueue_style('styles-child-rtl');
    }

    // bootstrap styles
    wp_deregister_style('porto-bootstrap');
    if (is_rtl()) {
        $css_file = porto_child_dir . '/css/bootstrap_rtl_' . porto_get_blog_id() . '.css';
        if (file_exists($css_file)) {
            wp_register_style('porto-bootstrap', porto_child_uri . '/css/bootstrap_rtl_' . porto_get_blog_id() . '.css?ver=' . porto_version);
        } else {
            wp_register_style('porto-bootstrap', porto_child_uri . '/css/bootstrap_rtl.css?ver=' . porto_version);
        }
    } else {

        $css_file = porto_child_dir . '/css/bootstrap_' . porto_get_blog_id() . '.css';
        if (file_exists($css_file)) {
            wp_register_style('porto-bootstrap', porto_child_uri . '/css/bootstrap_' . porto_get_blog_id() . '.css?ver=' . porto_version);
        } else {
            wp_register_style('porto-bootstrap', porto_child_uri . '/css/bootstrap.css?ver=' . porto_version);
        }
    }
    wp_enqueue_style('porto-bootstrap');
}

/* * * Remove Query String from Static Resources ** */

function remove_cssjs_ver($src) {
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}

add_filter('style_loader_src', 'remove_cssjs_ver', 10, 2);
add_filter('script_loader_src', 'remove_cssjs_ver', 10, 2);



// Defer Javascripts
// Defer jQuery Parsing using the HTML5 defer property
if (!(is_admin() )) {

    // Move all JS from header to footer
//    remove_action('wp_head', 'wp_print_scripts');
//    remove_action('wp_head', 'wp_print_head_scripts', 9);
//    remove_action('wp_head', 'wp_enqueue_scripts', 1);
//    add_action('wp_footer', 'wp_print_scripts', 5);
//    add_action('wp_footer', 'wp_enqueue_scripts', 5);
//    add_action('wp_footer', 'wp_print_head_scripts', 5);

    function defer_parsing_of_js($url) {
        if (FALSE === strpos($url, '.js'))
            return $url;
        if (strpos($url, 'jquery.js') || strpos($url, 'jquery-migrate.min.js'))
            return $url;
        // return "$url' defer ";
        return "$url' defer onload='";
    }

   // add_filter('clean_url', 'defer_parsing_of_js', 11, 1);
}

function _remove_query_strings_1( $src ){	
	$rqs = explode( '?ver', $src );
        return $rqs[0];
}
		if ( is_admin() ) {
// Remove query strings from static resources disabled in admin
}

		else {
add_filter( 'script_loader_src', '_remove_query_strings_1', 15, 1 );
add_filter( 'style_loader_src', '_remove_query_strings_1', 15, 1 );
}

function _remove_query_strings_2( $src ){
	$rqs = explode( '&ver', $src );
        return $rqs[0];
}
		if ( is_admin() ) {
// Remove query strings from static resources disabled in admin
}

		else {
add_filter( 'script_loader_src', '_remove_query_strings_2', 15, 1 );
add_filter( 'style_loader_src', '_remove_query_strings_2', 15, 1 );
}

//add_action( 'template_redirect', 'bhww_ssl_template_redirect', 1 );

// Redirect WWW to non-WWW
//add_filter( 'before_rocket_htaccess_rules', '__fix_wprocket_non_www_redirection' );
function __fix_wprocket_non_www_redirection( $marker ) {
    $redirection = '# Redirect www to non-www' . PHP_EOL;
    $redirection .= 'RewriteEngine On' . PHP_EOL;
    $redirection .= 'RewriteCond %{HTTP_HOST} ^www.lefairmag\.com [NC]' . PHP_EOL;
    $redirection .= 'RewriteRule ^(.*)$ http://lefairmag.com/$1 [L,R=301]' . PHP_EOL . PHP_EOL;
    $marker = $redirection . $marker;
    return $marker;
}
// Redirect HTTPS to HTTP
function bhww_ssl_template_redirect() {

    if ( is_ssl() && ! is_admin() ) {
    
        if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
        
            wp_redirect( preg_replace( '|^https://|', 'http://', $_SERVER['REQUEST_URI'] ), 301 );
            exit();
            
        } else {
        
            wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
            exit();
            
        }
        
    }
    
}
