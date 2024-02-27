<?php

if ( ! class_exists("lang")) {

    add_action('init', array('lang', 'init'), 11 );

    class lang {

        public static $lang = array();

        public static $lang_dir = '';

        public static $lang_load = '';

        public static $lang_name = '';

        public static $debug = array();

        // private static $url = 'http://plugins.svn.wordpress.org/content-links/assets/lang/';

        public static function init()
        {
            $lang_wp = self::getLanguageSystem();
            self::$lang_dir = CLS_BASE_DIR . '/assets/languages/'; 
            if (isset($lang_wp)) {
                if (file_exists(self::$lang_dir . $lang_wp . '.php')) {
                    require_once self::$lang_dir . $lang_wp . '.php';
                    self::$lang[$lang_wp] = $languag;
                    self::$lang_name = $lang_wp; 
                    return true;
                } 
            }
            return false;
        }
        public static function get($key, $echo = true, $replace_from = false, $replace_to = false) 
        {
            $result = '';
            if(!empty($key)) {
                if ( isset(self::$lang[self::$lang_name][$key]) ) {
                    $result = self::$lang[self::$lang_name][$key];
                } else {
                    $result = $key; 
                }
            }    
            if ($replace_from && $replace_to) {
                $result = self::replace($result, $replace_from, $replace_to); // str_replace($replace_from, $replace_to, $result);
            }

            if ($echo) {
                echo $result;
                return true;
            } else {
                return $result;
            }
        }
        public static function getLanguageSystem()
        {
            $lang_wp = get_option('WPLANG', 'en');
            $lang_wp = strtolower($lang_wp);
            $lang_wp = explode("_", $lang_wp);
            if (isset($lang_wp[0])) {
                self::$lang_load = $lang_wp[0];
                return $lang_wp[0]; 
            }
            return 'en';
        }
        public static function downloadLang()
        {
            if (!empty(self::$lang_load)) {
                $time = get_option(WPADM_LANG_ . 'time-update');
                if ( (isset($time['check_time']) && $time['check_time'] <= time()) || !isset($time['check_time']) || !file_exists(self::$lang_dir . self::$lang_load . '.php') ) {
                    if (!function_exists('wp_safe_remote_get')) {
                        include_once ABSPATH . WPINC . '/http.php';
                    }
                    $load = wp_safe_remote_get( self::$url . self::$lang_load . '.php' );
                    if (isset($load['response']['code']) && $load['response']['code'] == '200') {
                        @preg_match("/Date create - ([0-9\.]+)/", $load['body'], $date);
                        if (!isset($time['date']) || $time['date'] != $date[1] || !file_exists(self::$lang_dir . self::$lang_load . '.php')) {
                            if (isset($date[1])) {
                                self::updateDate($date[1]);
                            } else {
                                self::updateDate(date('d.m.Y'));
                            }
                            file_put_contents(self::$lang_dir . self::$lang_load . '.php', $load['body']);
                        }
                    }
                }
            }
        }
        
        private static function replace($text = '', $search = false, $replace = false)
        {
            if (!empty($text) && $search && $replace) {
                return str_replace($search, $replace, $text);
            }
            return $text; 
        }
        
        private static function updateDate($date_update)
        {
            $date = get_option(WPADM_LANG_ . 'time-update');   
            $time_update = array('date' => $date_update,  'check_time' => time() + 604800);
            if ($date) {
                update_option(WPADM_LANG_ . 'time-update', $time_update);
            } else {
                add_option(WPADM_LANG_ . 'time-update', $time_update );
            }
        }
    }
}