<?php
if (!class_exists('cl_api')) {
    class cl_api {   

        private static $url = 'https://secure.wpadm.com/api/';
        public static $url_secure = 'https://secure.wpadm.com';

        public static function send($postdata = array())
        {
            if (!function_exists('wp_remote_post')) {
                include_once ABSPATH . WPINC . '/http.php';
            }
            $args['body'] = $postdata;
            $response = wp_remote_post( self::$url, $args );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return array('error' => lang::get('Something went wrong:', false) . $error_message);
            } else {
                return json_decode($response['body'], true);
            }
        }
    }
}
