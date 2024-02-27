<?php
    if (!class_exists('cl_cron')) {

        add_action('cl_cron_task', array('cl_cron', 'init') );

        //add_action('save_post', array('cl_cron', 'savePost') );

        add_action('wp_ajax_processing', array('cl_cron', 'processing') );
        add_action('wp_ajax_cl_log', array('cl_cron', 'log') ); 
        add_action('wp_ajax_cl_init', array('cl_cron', 'init') );
        //add_action('post_submitbox_start', array('cl_cron', 'button') );

        class cl_cron {

            public static $table_prefix = 'lgp_';
            public static $table = 'crons';
            public static $table_log = 'log';
            public static $error = '';

            private static $limit_operation = 30;

            static function button()
            {
                global $post_type;

                if (isset($_GET['post'])) {
                    $is = self::getTasks( array( 'post_id' => $_GET['post'] ) );   
                    if (!empty($is)) {
                        wp_enqueue_script( 'links-arctimodal-js', plugins_url( "/assets/js/jquery.arcticmodal-0.3.min.js",  dirname( __FILE__ ) ) );
                        wp_enqueue_style( 'links-arctimodal-css', plugins_url( "/assets/js/jquery.arcticmodal-0.3.css", dirname( __FILE__ )) );
                        wp_enqueue_style( 'links-post-css', plugins_url( "/assets/css/styles.css", dirname( __FILE__ )) );
                        wp_enqueue_script( 'links-post-js', plugins_url( "/assets/js/scripts.js",  dirname( __FILE__ ) ) );
                    ?>
                    <div id="post-form-modal" class="post-form-modal" style="display: none;">
                        <div class="title-post-form-modal"><?php lang::get('Apply to linking')?></div>
                        <div class="content-post-form-modal">
                            <?php 
                                lang::get('This changed ' . $post_type . ' was found in tasks for linking between others posts and articles.');
                                lang::get('If you want to proceed the linking of this ' . $post_type . ' with others posts and articles please click the button "Proceed" below, or "Cancel", to close this window and go back.')
                        ?></div>
                        <div class="buttons-post-form-modal">
                            <a class="button button-primary button-large" href="<?php echo admin_url('admin.php?page=link-settings&autotask')?>"><?php lang::get('Proceed')?></a>
                            <a class="button button-link close" href="javascript:void(0)" onclick="jQuery('#post-form-modal').arcticmodal('close')"><?php lang::get('Cancel')?></a>
                        </div>
                    </div>
                    <div style="width: 100%; margin-bottom:10px;">
                        <input class="button cl-button-post" onclick="showModal('post-form-modal')" style="width: 100%" type="button" value="<?php echo lang::get('Link this ', false) . $post_type  . lang::get(' with other...', false);?>">
                    </div>
                    <div class="clear"></div>
                    <?php
                    }
                }
            }

            static private function getSetting()
            {
                $setting_cron  = get_option(self::$table_prefix . 'cl_cron');  
                return $setting_cron;
            }

            static private function setSetting($data)
            {
                update_option(self::$table_prefix . 'cl_cron', $data);
            }

            static function is_wp_cron()
            {
                if (defined('DISABLE_WP_CRON')) {
                    if (DISABLE_WP_CRON === true || DISABLE_WP_CRON == 'true') {
                        return false;
                    }
                }
                return true;
            }

            static function init_params_default()
            {
                //self::clearLog();
                $setting_cron = self::getSetting();

                if ( isset( $setting_cron['stop'] ) ) {
                    unset($setting_cron['stop']);
                }
                if ( isset( $setting_cron['operation'] ) ) {
                    unset($setting_cron['operation']);
                }
                self::setSetting($setting_cron);

                set_transient('cl_running', 0, 60 * 5);
            }

            private static function getTasks($w = array())
            {
                global $wpdb;
                $time = ini_get('max_execution_time');
                $limit = '';
                if( $time != 0 ) {
                    $limit = ' LIMIT ' . ( round($time / 10) * self::$limit_operation ); // 10 second - ~30 operation
                }
                $where = '';
                if (isset($w['post_id'])) {
                    $where .= ' AND post_id_from = ' . (int)$w['post_id'];
                }
                return $wpdb->get_results("SELECT * FROM `" . $wpdb->base_prefix . self::$table_prefix . self::$table . "` WHERE is_work = 1 " . $where . $limit, ARRAY_A);
            }

            public static function init()
            {
                if ( self::is_stop() ) {     // check stop command
                    self::run(60);
                    if ( self::checkLock() ) {
                        $tasks = self::getTasks();
                        $settings = self::getSetting();
                        if (!isset($settings['operation'])){
                            $settings['operation'] = self::isset_task(0, true);
                            self::setSetting( $settings );
                        }
                        if ( !empty( $tasks ) ) {
                            $n = count( $tasks );
                            global $wpdb;
                            cl_core::deleteSuperCache();
                            for($i = 0; $i < $n; $i++) {
                                cl_core::savePost($tasks[$i]['post_id_from']);

                                $wpdb->update($wpdb->base_prefix . self::$table_prefix . self::$table, array('is_work' => 0), array('id' => $tasks[$i]['id']));
                                //}
                            }
                            $tasks = self::getTasks();
                            if (empty($tasks)) {

                                self::stop();
                            }
                        } else {
                            self::stop();
                        }
                    }
                }   
            }

            public static function checkLock()
            {
                // false - cron is running
                // true - cron not running
                $running_cron = get_transient('cl_running'); 
                if ($running_cron && $running_cron == 1) {
                    $time = microtime( true );
                    $locked = get_transient('doing_cron');

                    if ( $locked > $time + 10 * 60 ) { // 10 minutes
                        $locked = 0;
                    }
                    if ((defined('WP_CRON_LOCK_TIMEOUT') && $locked + WP_CRON_LOCK_TIMEOUT > $time) || (!defined('WP_CRON_LOCK_TIMEOUT') && $locked + 60 > $time)) {
                        return false;
                    }
                    if (function_exists('_get_cron_array')) {
                        $crons = _get_cron_array();
                    } else {
                        $crons = get_option('cron');
                    }
                    if (!is_array($crons)) { 
                        return false;
                    }

                    $values = array_values( $crons );
                    if (isset($values['cl_cron_task'])) {
                        $keys = array_keys( $crons );
                        if ( isset($keys[0]) && $keys[0] > $time ) {
                            return false;
                        }
                    }
                }
                $time = ini_get('max_execution_time');
                if ($time == 0) {
                    set_transient( 'cl_running', 1, 60 * 60 * 24 ); // 24 hour
                } else {
                    set_transient( 'cl_running', 1, $time + 60 );
                }
                return true;
            }



            static function createCronTable()
            {

                global $wpdb;
                $result = $wpdb->get_row( "SHOW TABLES LIKE '" . $wpdb->base_prefix . self::$table_prefix . self::$table . "'" );

                if( is_null( $result ) ) {

                    $charset_collate = 'DEFAULT CHARACTER SET=utf8';
                    if ( ! empty( $wpdb->charset ) ) {$charset_collate = 'DEFAULT CHARACTER SET='.$wpdb->charset;}
                    if ( ! empty( $wpdb->collate ) ) {$charset_collate .= ' COLLATE='.$wpdb->collate;}

                    $sql = '
                    CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table  . '` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `post_id_from` bigint(20) NOT NULL DEFAULT 0,
                    `type_from` varchar(10) NOT NULL DEFAULT "",
                    `post_id_to` bigint(20) NOT NULL DEFAULT 0,
                    `type_to` varchar(10) NOT NULL DEFAULT "",
                    `links` text NOT NULL DEFAULT "", 
                    `is_work` tinyint(1) NOT NULL DEFAULT 1,
                    PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM ' . $charset_collate . ' AUTO_INCREMENT=1
                    ;';
                    $res = $wpdb->query( $sql );

                    $sql = '
                    CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_log  . '` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `time` int(11) NOT NULL DEFAULT 0, 
                    `log` text not null default "",
                    `show` TINYINT(1) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM ' . $charset_collate . ' AUTO_INCREMENT=1
                    ;';
                    $res = $wpdb->query( $sql );

                }
            }

            static function is_running()
            {
                $running = get_transient('cl_running');
                if ($running && $running == 1) {
                    return true;
                }
                return false;
            }

            static function is_stop() 
            {
                $setting_cron  = self::getSetting();
                if ( isset( $setting_cron['stop'] ) && $setting_cron['stop'] ) {
                    return false;
                }
                return true;
            }

            static function run($time = false)
            {
                if ($time) {
                    $time = $time + time(); 
                } else {
                    $time = time();
                }
                wp_schedule_single_event($time, 'cl_cron_task', array() );
            }

            static function stop()
            {
                $setting_cron  = get_option(self::$table_prefix . 'cl_cron');
                $setting_cron['stop'] = true;
                update_option(self::$table_prefix . 'cl_cron', $setting_cron);
                wp_clear_scheduled_hook( 'cl_cron_task', array() );
            }

            static function clear()
            {
                global $wpdb;
                $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix . self::$table . '`';
                $wpdb->query($sql);
                self::clearLog();
            }

            static function savePost( $post_id )
            {
                //self::setCron();
                $post = get_post($post_id, ARRAY_A);
                if ( isset($post['post_category']) ) {
                    if ( is_array($post['post_category']) ) {
                        $n = count($post['post_category']);
                        for($i = 0; $i < $n; $i++) {
                            $cat = cl_core::getLiningByCat($post['post_category'][$i]);
                            if (!empty($cat)) {
                                self::setCron($post['post_category'][$i], $cat[0]['linking_text'], $cat[0]['type'], $cat[0]['to_idn'], $cat[0]['to_type']);
                            } else {
                                $cat = cl_core::getLiningByCat($post_id);
                                if (!empty($cat)) {
                                    self::setCron($cat[0]['idn'], $cat[0]['linking_text'], $cat[0]['type'], $cat[0]['to_idn'], $cat[0]['to_type']);
                                }
                            }
                        }
                    }
                }
            }

            static function processing()
            {
                if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'cl_cron' ) ) {
                    if (isset($_POST['command'])) {
                        switch($_POST['command']) {
                            case 'start' :
                                self::init_params_default();
                                self::run(60);
                                break;
                            case 'stop';
                                self::stop();
                                break;
                            case 'clear':
                                self::clear(); 
                                break;
                        }
                    }

                    exit;
                }
            }

            static function clearLog()
            {
                global $wpdb;
                $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix . self::$table_log . '`';
                $wpdb->query($sql);
            }

            static function setLog($msg)
            {
                if( !empty($msg) ) {
                    global $wpdb;
                    $sql = $wpdb->prepare( "INSERT `" . $wpdb->base_prefix . self::$table_prefix . self::$table_log  . "` (`time`, `log`) VALUES (%d, %s)", time(), $msg );
                    $wpdb->query($sql);
                }

            }
            static function log()
            {
                global $wpdb;
                $log = array();
                if (isset($_POST['log_show'])) {
                    $show = 1;
                } else {
                    $show = 0;
                }
                $res = $wpdb->get_results('SELECT * FROM `' . $wpdb->base_prefix . self::$table_prefix . self::$table_log  . '` WHERE `show` = ' . $show, ARRAY_A);
                if ($res && ( $n = count ($res) ) > 0  ) {
                    $date_format = get_option('date_format');
                    $time_format = get_option('time_format');
                    for($i = 0; $i < $n; $i++) {
                        $log[] = date($time_format . " " . $date_format, $res[$i]['time']) . "&nbsp;\t&nbsp;&nbsp;&nbsp;&nbsp;" . $res[$i]['log'];
                        $wpdb->update($wpdb->base_prefix . self::$table_prefix . self::$table_log, array('show' => 1), array('id' => $res[$i]['id']), array(), array('%d') );
                    }
                }

                $ret = array( 'log' => $log );
                $setting_cron  = self::getSetting();
                if (isset( $setting_cron['stop'] ) ) {
                    $ret['stop'] = true;
                }
                $operation = self::getStatusOpreration();
                if ($operation) {
                    $ret['operation'] = $operation;
                }

                echo json_encode( $ret );
                exit;
            }

            static function getStatusOpreration()
            {
                $settings = self::getSetting();
                if ( isset($settings['operation']) ) {
                    $c = self::isset_task(2, true);
                    return array( 'tasks' => array( 'all' => $settings['operation'], 'procent' => round( ( ( $c / $settings['operation'] ) * 100 ) ), 'count' => $c ) );
                }
                return false;
            }

            static function setCron($idn, $words)
            {
                global $wpdb;

                $links = $idn;

                $posts = cl_core::getPosts($idn);

                self::createCronTable();

                cl_core::deleteSuperCache();

                if (!empty($posts)) {
                    $table = $wpdb->base_prefix . self::$table_prefix . self::$table; 
                    if (!empty($links)) {
                        if (is_array($links)) {
                            $links = implode(',', $links);
                        }
                    }
                    self::$error = '';

                    cl_core::setLiningByCat($idn, implode(',', $words) );

                    foreach($posts as $post) {

                        $sql = 'SELECT * FROM ' . $table . ' WHERE post_id_from = %d AND is_work = 1 AND type_from = "cat" AND post_id_to = -1 AND type_to = "cat" ' ;
                        $sql = $wpdb->prepare($sql, $post->ID, $type );
                        $post_cron = $wpdb->get_row($sql, ARRAY_A);
                        if ( $post_cron === null ) {
                            $sql = 'INSERT INTO `' . $table . '` (`post_id_from`, `type_from`, `post_id_to`, `type_to`, `links`) VALUES( %d, %s, %d, %s, %s)';
                            $sql = $wpdb->prepare($sql, $post->ID, 'cat', -1, 'cat', '' );
                            $res = $wpdb->query($sql);
                            if ($res === false) {
                                self::$error = $wpdb->last_error;
                                break;
                            }
                        }
                    }
                }
                //
            }

            public static function isset_task($work = 1, $cnt = false)
            {
                global $wpdb;
                self::createCronTable();
                $where = '1';
                if ($work == 1) {
                    $where .= ' AND is_work = 1';
                } 
                if ( $work == 2 ) {
                    $where .= ' AND is_work = 0';
                }
                $res = $wpdb->get_row("SELECT count(*) as cnt FROM `" . $wpdb->base_prefix . self::$table_prefix . self::$table . "` WHERE " . $where, ARRAY_A);
                if ($cnt) {
                    return $res['cnt'];
                } 
                return (isset($res['cnt']) && $res['cnt'] > 0);
            }
        }
    }
?>
