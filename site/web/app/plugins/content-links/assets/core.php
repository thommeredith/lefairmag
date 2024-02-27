<?php
    if (!class_exists('cl_core')) {
        class cl_core {
            private static $mail_support = 'support@wpadm.com';
            private static $url_home_sync = 'secure.wpadm.com';
            private static $table_prefix = 'lgp_';
            private static $table_name_linking = 'linking';
            private static $table_name_post = 'posts';
            private static $default_setting = array('notice' => array('stars5' => true, 'pro' => true ));
            private static $default_count_link = 3;
            private static $links_in_one_category = 1;
            private static $links_to_pages = 0;
            private static $links_to_htags = 1;
            private static $single_article = 0;
            private static $black_words = array( 
            'or', 'he', 'after', 'as', 'at', 'by', 'in', 'on', 'of', 'off', 'per', 'pro', 
            'to', 'able', 'about', 'again', 'all', 'almost',
            'already', 'also', 'although', 'and', 'another', 'any', 'are', 'around',
            'based', 'because', 'been', 'before', 'being', 'between', 'both', 'bring',
            'but', 'came', 'can', 'com', 'come', 'comes', 'could', 'did', 'does',
            'doing', 'done', 'each', 'eight', 'else', 'etc', 'even', 'every', 'five',
            'for', 'four', 'from', 'get', 'gets', 'getting', 'going', 'got', 'had',
            'has', 'have', 'her', 'here', 'him', 'himself', 'his', 'how', 'however',
            'href', 'http', 'including', 'into', 'its', 'ing', 'just', 'know', 'like',
            'looks', 'mailto', 'make', 'making', 'many', 'may', 'means', 'might',
            'more', 'more', 'most', 'move', 'much', 'must', 'need', 'needs', 'never',
            'nice', 'nine', 'not', 'now', 'often', 'one', 'only', 'org', 'other',
            'our', 'out', 'over', 'own', 'piece', 'rather', 'really', 'said', 'same',
            'say', 'says', 'see', 'seven', 'several', 'she', 'should', 'since',
            'single', 'six', 'some', 'something', 'still', 'stuff', 'such', 'take',
            'ten', 'than', 'that', 'the', 'their', 'them', 'them', 'then', 'there',
            'there', 'these', 'they', 'thing', 'things', 'this', 'those',
            'three', 'through', 'too', 'took', 'two', 'under', 'use', 'used', 'using',
            'usual', 'very', 'via', 'want', 'was', 'way', 'well', 'were', 'what',
            'when', 'where', 'whether', 'which', 'while', 'whilst', 'who', 'why',
            'will', 'with', 'within', 'would', 'yes', 'yet', 'you', 'your');

            static function debug($msg)
            {
                file_put_contents(CLS_BASE_DIR . 'debug.log', "$msg\n", FILE_APPEND);
            }    

            static function deleteFreeVersion()
            {
                $dir_content_links_pro = WP_PLUGIN_DIR . '/content-links-pro'; 
                if ( is_dir($dir_content_links_pro) ) {
                    $dir_content_links = WP_PLUGIN_DIR . '/content-links';
                    $plugins = get_option('active_plugins');
                    $plugin_name = 'content-links/content-links.php';
                    if ( in_array($plugin_name, $plugins) ) {
                        $n = count($plugins);
                        for ($i = 0; $i < $n; $i++) {
                            if ($plugins[$i] == $plugin_name) {
                                unset($plugins[$i]);
                            }
                        }
                        update_option('active_plugins', array_values($plugins));
                    }

                    if ( self::remove($dir_content_links) ) {
                        if ( is_dir($dir_content_links) ) {
                            echo 'Please deactivate plugin "Content links" or delete folder "' . $dir_content_links . '"';
                            exit;
                        }
                    }
                }
            }

            static function nonceLife()
            {
                return 1.5 * HOUR_IN_SECONDS; // work for cron task
            }

            static function initialize()
            {
                if (file_exists(CLS_BASE_DIR . 'assets/languages.php')) {
                    include CLS_BASE_DIR . 'assets/languages.php';
                }
                if (file_exists(CLS_BASE_DIR . 'assets/cron.php')) {
                    include CLS_BASE_DIR . 'assets/cron.php';
                }

                add_filter( 'nonce_life', array(__CLASS__, 'nonceLife') );

                add_action('init', array(__CLASS__, 'init') );

                add_action('admin_notices', array(__CLASS__, 'notices') );

                add_action('admin_menu', array(__CLASS__, 'to_admin_menu') );
                add_action('admin_print_scripts', array( __CLASS__ , 'include_admins_script' ) );
                // works from category page
                add_action('category_add_form_fields', array( __CLASS__ , 'field_to_add_category' ) );
                add_action('category_edit_form_fields', array( __CLASS__ , 'field_to_add_category' ) );

                add_action('create_category', array(__CLASS__, 'add_linking_text') );
                add_action('edit_category', array(__CLASS__, 'add_linking_text') );
                add_action('delete_category', array(__CLASS__, 'delLiningByCat') );

                add_action('save_post', array(__CLASS__, 'savePost') );

                add_action('the_content', array(__CLASS__, 'getPost') );
                add_action('fl_builder_before_render_modules', array(__CLASS__, 'fl_builder_post') );


                add_action('admin_post_cl_hide_notice', array( __CLASS__, 'hide_notice') );

                add_action('wp_ajax_cl_support', array( __CLASS__, 'support') );

                add_action('admin_post_get_auto_link_anchor', array(__CLASS__, 'get_auto_link_anchor') );

            }

            static function fl_builder_post($nodes)
            {

                foreach($nodes as $node => $setting) {
                    if ($setting->settings->type == 'rich-text') {
                        global $post;
                        $post_linking = self::getPostLinking($post->ID);
                        if ($post_linking) {
                            $setting->settings->text = $post_linking['content'];
                        }
                    }
                }
                return $nodes;
            }

            static function init()
            {  

            }
            static function getIp()
            {
                $user_ip = '';
                if ( getenv('REMOTE_ADDR') ){
                    $user_ip = getenv('REMOTE_ADDR');
                }elseif ( getenv('HTTP_FORWARDED_FOR') ){
                    $user_ip = getenv('HTTP_FORWARDED_FOR');
                }elseif ( getenv('HTTP_X_FORWARDED_FOR') ){
                    $user_ip = getenv('HTTP_X_FORWARDED_FOR');
                }elseif ( getenv('HTTP_X_COMING_FROM') ){
                    $user_ip = getenv('HTTP_X_COMING_FROM');
                }elseif ( getenv('HTTP_VIA') ){
                    $user_ip = getenv('HTTP_VIA');
                }elseif ( getenv('HTTP_XROXY_CONNECTION') ){
                    $user_ip = getenv('HTTP_XROXY_CONNECTION');
                }elseif ( getenv('HTTP_CLIENT_IP') ){
                    $user_ip = getenv('HTTP_CLIENT_IP');
                }

                $user_ip = trim($user_ip);
                if ( empty($user_ip) ){
                    return '';
                }
                if ( !preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $user_ip) ){
                    return '';
                }
                return $user_ip;
            }

            static function support()
            {
                $msg = '';
                $error = false;
                if ( wp_verify_nonce( $_REQUEST['nonce_support'], 'nonce_support' ) ) {
                    if (isset($_POST['message'])) {
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                        $slug = 'content-links/content-links.php';
                        $plugins = get_plugins();
                        $info = $plugins[$slug];

                        $plugin_current_version = $info['Version'];

                        $ticket = date('ymdHis') . rand(1000, 9999);
                        $subject = "Support [sug:$ticket]: Content-links plugin";
                        $message = "Client email: " . get_option('admin_email') . "\n";
                        $message .= "Client site: " . home_url() . "\n";
                        $message .= "Plugin: Content links " . $plugin_current_version . "\n";
                        $message .= "Client suggestion: " . sanitize_text_field( $_POST['message'] ) . "\n\n";
                        $message .= "Client ip: " . self::getIp() . "\n";

                        $browser = @$_SERVER['HTTP_USER_AGENT'];
                        $message .= "Client useragent: " . $browser . "\n";
                        $header[] = "Reply-To: " . get_option('admin_email') . "\r\n";
                        if (wp_mail(self::$mail_support, $subject, $message, $header)) {
                            $msg = lang::get("Thanks for your suggestion!<br /><br />Within next plugin updates we will try to satisfy your request.", false);
                        } else {
                            $msg = lang::get("At your website the mail functionality is not available.<br /><br /> Your request was not sent.", false);
                            $error = true;
                        }
                    }
                    //header('Location: ' . admin_url('admin.php?page=link-settings'));
                    
                } else {
                    $msg = lang::get("Please reload web page and try again.", false);
                    $error = true;
                }
                echo json_encode(array('msg' => $msg, 'error' => $error));
                exit;
            }

            static function notices()
            {
                $setting = get_option(self::$table_prefix . "setting", self::$default_setting );
                if (isset($setting['notice'])) {
                    if ( (isset($setting['notice']['time']) && $setting['notice']['time'] <= time() ) || (!isset($setting['notice']['time'])) ) {
                        if (isset($setting['notice']['pro']) && $setting['notice']['pro']) {
                            if (!isset($_GET['page']) || ( isset($_GET['page']) && $_GET['page'] != 'link-settings' ) ) {      
                            ?>
                            <div class="clear"></div>
                            <div class="update-nag" style="position: relative; width: 95%;">
                                <div style="line-height: 20px;">
                                    <?php lang::get('Professional version of "SEO Post Content Links" plugin now available!<br />'); ?> 
                                    <a href="<?php echo admin_url('admin.php?page=link-settings')?>"><?php lang::get('Make SEO like Pro and get more SEO features:'); ?></a>
                                    <br />
                                    <ul class="pro-list" style="margin-bottom: 0;">
                                        <li>
                                            <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                            <span>
                                                <?php lang::get('Link anchors corresponds to the target text'); ?>
                                            </span>
                                            <div class="clear"></div>
                                        </li>
                                        <li>
                                            <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                            <span>
                                                <?php lang::get('Link anchors style customization'); ?>
                                            </span>
                                            <div class="clear"></div>
                                        </li>
                                        <li>
                                            <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                            <span>
                                                <?php lang::get('Prevention of links in custom HTML tags &lt;span&gt;,&lt;li&gt;,&lt;ul&gt;,&lt;strong&gt;... etc.'); ?>
                                            </span>
                                            <div class="clear"></div>
                                        </li>
                                        <li>
                                            <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                            <span>
                                                <?php lang::get('Priority support for PRO version'); ?>
                                            </span>
                                            <div class="clear"></div>
                                        </li>
                                        <li>
                                            <img src="<?php echo plugins_url('/assets/img/ok.png', dirname(__FILE__));?>" alt="" title="" />
                                            <span>
                                                <?php lang::get('One year free updates'); ?>
                                            </span>
                                            <div class="clear"></div>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                                <div style="position: absolute; top:5px; right: 5px; font-size: 12px">[<a href="<?php echo admin_url( 'admin-post.php?action=cl_hide_notice&type=pro' );?>" ><?php lang::get('hide message')?></a>]</div>
                            </div>
                            <?php 
                            }
                        } else {
                            if (isset($setting['notice']['stars5']) && $setting['notice']['stars5']) {
                            ?> 
                            <div class="clear"></div>
                            <div class="updated" style="position: relative;">
                                <p style="font-size: 15px;">
                                    <?php lang::get('Please support us, '); ?> 
                                    <a href="https://wordpress.org/support/view/plugin-reviews/content-links?filter=5" target="_blank" style="text-decoration: underline;"><?php lang::get('leave 5 stars review'); ?></a> 
                                    <?php _e(' for "SEO Post Content Links" plugin!', 'content-links');?>
                                </p>
                                <p style="font-size: 15px;">
                                    <?php lang::get('It helps us to develop this plugin for you. Thank you!'); ?> 
                                </p>
                                <div style="padding: 2px 0; margin:0.5em 0;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="https://wordpress.org/support/view/plugin-reviews/content-links?filter=5" class="button button-primary" ><?php lang::get('Leave review')?></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo admin_url( 'admin-post.php?action=cl_hide_notice&type=stars5' );?>" class="button" ><?php lang::get('hide message')?></a>
                                </div>
                                <!--<div style="position: absolute; top:5px; right: 5px;">[<a href="<?php echo admin_url( 'admin-post.php?action=cl_hide_notice&type=stars5' );?>" ><?php lang::get('hide message')?></a>]</div> -->
                            </div>
                            <?php 
                        }
                    }
                }
            }
        }

        static function hide_notice()
        {
            if (isset($_GET['type'])) {
                $setting = get_option(self::$table_prefix . "setting", self::$default_setting );
                if (isset($setting['notice'][$_GET['type']])) {
                    $setting['notice'][$_GET['type']] = false;
                }
                update_option(self::$table_prefix . "setting", $setting);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        static function optimaze_table()
        {
            global $wpdb;
            $sql = 'OPTIMIZE TABLE `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '`, `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post . '` ';
            return $wpdb->query( $sql ); 
        }

        static function install()
        {
            global $wpdb;

            // Get the correct character collate
            $charset_collate = 'DEFAULT CHARACTER SET=utf8';
            if ( ! empty( $wpdb->charset ) ) {$charset_collate = 'DEFAULT CHARACTER SET='.$wpdb->charset;}
            if ( ! empty( $wpdb->collate ) ) {$charset_collate .= ' COLLATE='.$wpdb->collate;}

            $sql = 'SHOW TABLES LIKE "' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '" ';
            $var = $wpdb->get_var($sql, 0, 0);
            if (!$var ) {
                $sql = 'SHOW COLUMNS FROM `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '`;';
                $res = $wpdb->get_var($sql, 0, 1);
                if ($res != 'cat_id') {
                    $sql = 'ALTER TABLE `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '` CHANGE `idn` `cat_id` INT(11) NOT NULL DEFAULT \'0\';';
                    $res = $wpdb->query( $sql );
                    $sql = 'ALTER TABLE `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '` DROP INDEX `idn`, ADD UNIQUE `cat_id` (`cat_id`)COMMENT \'\';';
                    $res = $wpdb->query( $sql );
                }
            }

            $sql = '
            CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cat_id` int(11) NOT NULL DEFAULT 0,
            `linking_text` text NOT NULL DEFAULT "",
            PRIMARY KEY (`id`),
            UNIQUE KEY `cat_id` (`cat_id`)
            ) ENGINE=MyISAM ' . $charset_collate . ' AUTO_INCREMENT=1
            ;';
            $res = $wpdb->query( $sql );

            $sql = '
            CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post . '` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL DEFAULT 0,
            `update_time` datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
            `content` text NOT NULL DEFAULT "",
            PRIMARY KEY (`id`),
            UNIQUE KEY `post_id` (`post_id`)
            ) ENGINE=MyISAM ' . $charset_collate . ' AUTO_INCREMENT=1
            ;';
            $res = $wpdb->query( $sql );

            $setting = self::$default_setting;
            $setting['notice']['time'] = time() + 172800;
            update_option(self::$table_prefix . "setting", $setting );
        }

        static function deactivate()
        {
            global $wpdb;

            /*$sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix. self::$table_name_linking . '`';
            $wpdb->query($sql);

            $sql = 'TRUNCATE TABLE `' . $wpdb->base_prefix . self::$table_prefix. self::$table_name_post . '`';
            $wpdb->query($sql);   
            delete_option(self::$table_prefix . "setting");   */ 
        }
        static function uninstall()
        {
            global $wpdb;

            //remove table
            /* $sql = 'DROP TABLE IF EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking .'`';
            $wpdb->query($sql);
            $sql = 'DROP TABLE IF EXISTS `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post .'`';
            $wpdb->query($sql);    */

        }
        public static function getPost($content) 
        {
            global $post;

            $single_article = get_option(self::$table_prefix . "single_article",  self::$single_article);
            if ( ( $single_article == 0 ) || ( $single_article == 1 && is_single() ) ) {
                if (isset($post->ID)) {
                    $post_linking = self::getPostLinking($post->ID);
                    if ($post_linking) {
                        $content = wpautop( $post_linking['content'] );
                        $pattern = get_shortcode_regex();
                        preg_match("/$pattern/s", $content, $matches);  // isset shotcode in content
                        if (isset( $matches[0] ) && !empty( $matches[0] ) ) {
                            $shortcode = self::double_shortcodes( $matches );
                            $content = do_shortcode( $content );    // action chotcode
                        }
                    }
                }
            }
            return $content;
        }

        // replace to shotcode
        static public function double_shortcodes( $m )
        {
            if ( $m[1] == '[' && $m[6] == ']' ) {
                return '[' . $m[0] . ']';
            }

            return $m[0];
        }
        static function getPostLinking($post_id) 
        {
            global $wpdb;
            $sql = 'SELECT * FROM `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_post  . '` WHERE `post_id` = ' . $post_id;
            $res = $wpdb->get_results($sql, ARRAY_A);
            if (!empty($res)) {
                return $res[0];
            }
            return false;
        }
        static function getLiningByCat($cat_id) 
        {
            global $wpdb;
            $sql = 'SELECT * FROM `' . $wpdb->base_prefix . self::$table_prefix . self::$table_name_linking  . '` WHERE `cat_id` = ' . $cat_id;
            $res = $wpdb->get_results($sql, ARRAY_A);
            return $res;

        }
        static function setLiningByCat($cat_id, $linking) 
        {
            return self::setsToTable(self::$table_name_linking, array('cat_id' => $cat_id, 'linking_text' => $linking ), array( 'linking_text' => $linking ) );
        }
        static function setPostLinking($post_id, $content)
        {
            $time = current_time( 'mysql' );
            return self::setsToTable(self::$table_name_post, array('post_id' => $post_id, 'content' => $content, 'update_time' => $time ), array( 'content' => $content, 'update_time' => $time ) );
        }
        static function setsToTable($table, $values = array(), $onDuplicate = array() )
        {
            global $wpdb;
            if (!empty($values)) {
                $str = '';
                foreach($values as $key => $value) {
                    $str .= self::_value($key, $value);
                }
                $str = substr($str, 0, strlen($str) - 1);

                $onDuplicateStr = '';
                if (count($onDuplicate) > 0)  {
                    $onDuplicateStr = 'ON DUPLICATE KEY UPDATE ';
                    foreach($onDuplicate as $k => $v) {
                        $onDuplicateStr .= self::_value($k, $v);
                    }
                    $onDuplicateStr = substr($onDuplicateStr, 0, strlen($onDuplicateStr) - 1); 
                    $values = array_merge( array_values( $values ), array_values( $onDuplicate ) );
                }
                $sql_text = 'INSERT INTO `' . $wpdb->base_prefix . self::$table_prefix . $table . '` SET
                ' . $str . '
                ' . $onDuplicateStr;
                $sql =  self::prepare(
                $sql_text ,
                array_values( $values )
                );

                $res = $wpdb->query( $sql );
                self::optimaze_table();
                return $res;
            }
            return false;
        }
        public static function prepare( $query, $args ) 
        {
            global $wpdb;
            if ( is_null( $query ) )
                return;

            $query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
            $query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
            $query = preg_replace( '|(?<!%)%f|' , '%F', $query ); // Force floats to be locale unaware
            $query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
            array_walk( $args, array( $wpdb, 'escape_by_ref' ) );
            return @vsprintf( $query, $args );
        }
        static function _value($k, $v)
        {
            $str = '';
            if (is_int($v)) {
                $str .= " $k = %d ,";
            } elseif (is_float($v)) {
                $str .= " $k = %f ,";
            } elseif (is_string($v)) {
                $str .= " $k = %s ,";
            }
            return $str; 
        }
        static function random_from_array($arr, $count)
        {
            $ret = array();
            if (!empty($arr)) {
                $n = count($arr);
                if ($n > $count) {
                    $_i = 0;
                    for($i = 0; $i < $count; $i++) {
                        $_i = rand(0, $n - 1 - $i);
                        $val = $arr[$_i];
                        if (!in_array($val, $ret)) {
                            $ret[] = $val;
                            unset( $arr[$_i] );
                            $arr = array_values($arr);
                        }
                    }
                } else {
                    $ret = $arr; 
                }
            }
            return $ret;
        }
        static function random_post($posts, $count, $unset_id = 0) 
        {
            $ret = array();
            if (!empty($posts)) {
                $n = count($posts);
                if ($n > $count) {
                    if ($unset_id != 0) {
                        for($i = 0; $i < $n; $i++) {
                            if ($posts[$_i]->ID == $unset_id) {
                                unset($posts[$_i]);
                            }
                        }
                        $posts = array_values($posts);
                    }
                    $_i = 0;
                    for($i = 0; $i < $count; $i++) {
                        $_i = rand(0, $n - 1 - $i);
                        $val = $posts[$_i];
                        if (!isset( $ret[ $val->ID ] )  ) {
                            $ret[$val->ID] = $val;
                            unset( $posts[$_i] );
                            $posts = array_values($posts);
                        }
                    }
                } else {
                    $ret = $posts; 
                }
            }
            return $ret;
        }

        static function savePost($post_id)
        {   
            remove_action( 'save_post', array(__CLASS__, 'savePost') );

            self::deletePost($post_id); 

            $link_count = get_option(self::$table_prefix . "count_links", self::$default_count_link);
            $link_in_one_post = get_option(self::$table_prefix . "link_in_one_category", self::$links_in_one_category);
            $links_to_pages = get_option(self::$table_prefix . "link_to_pages",  self::$links_to_pages);
            $links_to_htags = get_option(self::$table_prefix . "links_to_htags",  self::$links_to_htags);

            cl_cron::setLog( lang::get('Reading of main settings', false)  );

            self::$black_words = self::normilize_linking_text( get_option(self::$table_prefix . "black_words", implode(',', self::$black_words) ) , ',');
            $post = get_post($post_id, ARRAY_A);
            cl_cron::setLog( lang::get('Getting of source Post/Article:', false) . ' "' . $post['post_title'] . '"' );
            //$post['post_content'];     // content from change     
            if (!empty($post['post_category']) && count($post['post_category']) > 0) {
                $linking = '';  
                $posts = array();
                if ($links_to_pages == 1) {
                    $posts = array_merge($posts, get_pages( array( 'numberposts'   => -1, 'post_status'  => 'publish' ) ) );    // get all pages
                }
                if ($link_in_one_post == 0) {
                    $posts = array_merge($posts, get_posts( array( 'exclude' => $post_id, 'numberposts'   => -1 ) ) );
                }
                foreach($post['post_category'] as $tag) {

                    $cat = get_category( $tag, ARRAY_A );
                    cl_cron::setLog( lang::get('Getting of words for link anchors from', false) . ': "' . $post['post_title'] . '". ' . '(' . lang::get('Category', false) . ': "' . $cat['name'] . '")' );

                    $l = self::getLiningByCat($tag);
                    if ( !empty( $l ) ) {
                        $linking .= $l[0]['linking_text'] . ',';
                        if ($link_in_one_post == 1) {
                            $posts = array_merge($posts, get_posts( array( 'category' => $tag, 'exclude' => $post_id, 'numberposts'   => -1 ) ) );
                        }
                    }
                }

                if (!empty($linking)) {
                    $linking = trim( substr($linking, 0, strlen($linking) - 1) ); 
                    if (!empty($linking)) {
                        $tmp_linking = explode(",", $linking);
                        if (count($tmp_linking) >= 70) { // limit words to link
                            $tmp_linking = array_chunk($tmp_linking, 70);
                            $linking = implode(',', $tmp_linking[0]);
                            unset($tmp_linking);
                        }
                        $link = '[\w]{0,}' . implode('[\w]{0,}|[\w]{0,}', explode(",", $linking) ) . '[\w]{0,}';
                        cl_cron::setLog( lang::get('Content preparation of', false) . ' "' . $post['post_title'] . '"'  );
                        $text = $post['post_content']; 
                        if ($links_to_htags == 0) {
                            $text = preg_replace("/<[hH]{1}[1-6]{1}.*>.*<\/[hH]{1}[1-6]{1}.*>/iUu", '', $post['post_content']);
                        }
                        $text = preg_replace("/<a.*>.*<\/a>/", '', $text);
                        $text = preg_replace("/\[.*\]/isU", '', $text);
                        $text = preg_replace("/\{.*\}/isU", '', $text);
                        $text = preg_replace("/[\s]{2,}[\t\n\r]{1}/", ' ', strip_tags( $text ) );

                        cl_cron::setLog( lang::get('Searching of corresponding anchor-words in content of', false) . ' "' . $post['post_title'] . '"' );

                        preg_match_all("/([\w\.\!\-\?]+\s({$link})[\s\:\;,\!\.\-\?\)]{0,}[\(\)\:\;\w]+)/isu", $text, $links);
                        if (isset($links[0]) && count($links[0]) > 0) {
                            cl_cron::setLog( lang::get('Founded ', false) . count($links[0]) . lang::get(' words for anchor creatings, it will be ', false)  . count($links[0]) . lang::get(' links created', false) );
                            $links = self::random_from_array($links[0], $link_count);
                            cl_cron::setLog( lang::get('Selecting of ' . $link_count . ' random places for links creating', false) );
                            $links_replace = array();
                            if ( !empty( $posts ) ) {
                                $k = count( $links );    
                                cl_cron::setLog( lang::get('Searching of target Post/Article for linking', false) );
                                $post_replace = array_values( self::random_post($posts, $k, $post_id) );
                                for($j = 0; $j < $k; $j++) {
                                    if (isset($post_replace[$j]) && $post_id != $post_replace[$j]->ID) {
                                        cl_cron::setLog( lang::get('Target Post/Article for linking was found:', false) . ' "' . $post_replace[$j]->post_title . '"' );
                                        $words_plus = rand(0, 2); // 0 not word + 1 - prev word + 2 - next word +
                                        preg_match( "/({$link})/",  $links[$j], $preg);
                                        $words = explode(" ", $links[$j]);
                                        $last_word = $words[count($words) - 1] ;
                                        $first_word = $words[0] ;
                                        unset($words[count($words) - 1]);
                                        unset($words[0]);
                                        $word = implode(" ", array_values($words));

                                        $word_new = self::findSeparateWord( array($first_word, $word, $last_word) );
                                        $first_word_l = $word_new['words'][0];
                                        $word_l = $word_new['words'][1] ;
                                        $last_word_l = $word_new['words'][2];
                                        $sep[0] = self::generateSeparateArray($word_new['separate'], 0);
                                        $sep[1] = self::generateSeparateArray($word_new['separate'], 1);
                                        $sep[2] = self::generateSeparateArray($word_new['separate'], 2);

                                        switch($words_plus) {
                                            case 0 :
                                                $links_replace[$links[$j]] = "{$sep[0][0]}{$first_word_l}{$sep[0][1]}" .
                                                " {$sep[1][0]}<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$word_l}\" alt=\"{$word_l}\">{$word_l}</a>" 
                                                . $sep[1][1] . " " . $sep[2][0] . $last_word_l . $sep[2][1];
                                                //$links_replace[$links[$j]] = preg_replace( "/({$link})/u", "<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID). "\"" . $nofollow . $blank .  " title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                                break;
                                            case 1 :

                                                if (!in_array($first_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$first_word} {$word}")) {
                                                    $links_replace[$links[$j]] = "<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$first_word} {$word}\" alt=\"{$first_word} {$word}\">{$first_word} {$word}" . "</a> " . $last_word;
                                                } else {
                                                    if (!in_array($last_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$word} {$last_word}") ) {
                                                        $links_replace[$links[$j]] = "{$first_word} <a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$word} {$last_word}\" alt=\"{$word} {$last_word}\">{$word} {$last_word}</a>";
                                                    } else {
                                                        //$links_replace[$links[$j]] = preg_replace( "/({$link})/u", "<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                                        $links_replace[$links[$j]] = "{$sep[0][0]}{$first_word_l}{$sep[0][1]}" .
                                                        " {$sep[1][0]}<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$word_l}\" alt=\"{$word_l}\">{$word_l}</a>" 
                                                        . $sep[1][1] . " " . $sep[2][0] . $last_word_l . $sep[2][1];
                                                    }
                                                }
                                                break;
                                            case 2 :
                                                if (!in_array($last_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$word} {$last_word}") ) {
                                                    $links_replace[$links[$j]] = "{$first_word} <a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$word} {$last_word}\" alt=\"{$word} {$last_word}\">{$word} {$last_word}</a>";
                                                } else {
                                                    if (isset($first_word) && !in_array($first_word, self::$black_words) && !preg_match("/(\(|\)|\.|\,|\?|\!|\:|\;)/", "{$first_word} {$word}")) {
                                                        $links_replace[$links[$j]] = "<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$first_word} {$word}\" alt=\"{$first_word} {$word}\">{$first_word} {$word}" . "</a> " . $last_word;
                                                    } else {
                                                        $links_replace[$links[$j]] = "{$sep[0][0]}{$first_word_l}{$sep[0][1]}" .
                                                        " {$sep[1][0]}<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"{$word_l}\" alt=\"{$word_l}\">{$word_l}</a>" 
                                                        . $sep[1][1] . " " . $sep[2][0] . $last_word_l . $sep[2][1];
                                                        //preg_replace( "/({$link})/u", "<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                                        //$links_replace[$links[$j]] = preg_replace( "/({$link})/u", "<a " . $class_link . " href=\"" . get_permalink($post_replace[$j]->ID) . "\"" . $nofollow . $blank . " title=\"$0\" alt=\"$0\">$0</a>", $links[$j] );
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                }
                                if (!empty($links_replace)) {
                                    $content_post = $post['post_content'];
                                    cl_cron::setLog( lang::get('Transformation of founded anchor-words into links', false) );
                                    foreach($links_replace as $search => $replace ) {    //\:\;,\!\.\?\)\/
                                        $search_in = str_replace(array( '(', ')', ':', ';', ',', '.', '?', '/' ), 
                                        array('\\(', '\\)', '\\:', '\\;', '\\,', '\\.', '\\?', '\\/',), 
                                        $search);
                                        //cl_cron::setLog( $search_in . '  ' . html_entity_decode( $replace ) );
                                        preg_match_all("/$search_in/u", $content_post, $result_count);
                                        if (isset($result_count[0]) && count( $result_count[0] ) <= 1) {
                                            $content_post = str_replace($search, $replace, $content_post);
                                            cl_cron::setLog( lang::get('Trasform anchor-word:', false) . ' "' . $search . '"' );  // 
                                        } elseif ( isset($result_count[0]) ) {
                                            cl_cron::setLog( lang::get('Searching in text anchor-word:', false) . ' "' . $search . '" ' . lang::get('get count result: ', false) . count( $result_count[0] ) );  // 
                                            $pos = strpos($content_post, $search);
                                            $content_post = substr($content_post, 0, $pos) . $replace . substr($content_post, $pos + strlen($search), strlen($content_post) );
                                            cl_cron::setLog( lang::get('Trasform anchor-word:', false) . ' "' . $search . '"' );  // 
                                        } else {
                                            cl_cron::setLog( lang::get('Searching in text anchor-word:', false) . ' "' . $search . '" ' . lang::get('get count result: ', false) . 0 );  // 
                                        }
                                    }
                                    cl_cron::setLog( lang::get('Anchor-words transformation was finished', false) );
                                    self::setPostLinking($post_id, $content_post);
                                }
                            }
                        }
                    }

                }
            }
            add_action( 'save_post', array(__CLASS__, 'savePost') );

        }

        static function findSeparateWord($words = array())
        {
            $separate = array(',', '.', ':', ';', '!','?', ')', '/', '(');
            $sep = array();
            foreach($words as $key => $word) {
                $n = count($separate);
                for($i = 0; $i < $n; $i++) {
                    if ( ( $pos = strpos(trim($word), $separate[$i]) ) !== false) {
                        $words[$key] = trim(trim($word), $separate[$i]);
                        $first = false;
                        if ($pos == 0) {
                            $first = true;
                        }
                        $sep[$key] = array('pos' => $pos, 'symbol' => $separate[$i], 'is_first' => $first);
                    }
                }
            }
            return array('words' => $words, 'separate' => $sep);
        }

        static function generateSeparateArray($sep, $key)
        {
            $sep_return = array();
            $sep_return[0] = '';
            $sep_return[1] = '';
            if (isset($sep[$key])) {  // first word
                if ($sep[$key]['is_first']) {
                    $sep_return[0] = $sep[$key]['symbol'];
                    $sep_return[1] = '';
                } else {
                    $sep_return[0] = '';
                    $sep_return[1] = $sep[$key]['symbol'];
                }
            }
            return $sep_return;
        }

        static function deletePost($post_id)
        {
            global $wpdb;
            if (!empty($post_id) && $post_id != 0) {
                $sql =  $wpdb->prepare(
                'DELETE FROM `'.$wpdb->base_prefix . self::$table_prefix . self::$table_name_post . '` WHERE   
                post_id = %d
                ',
                $post_id 
                );
                return $wpdb->query( $sql );
            }
            return false;
        }

        static function include_admins_script()
        {
            if (isset($_GET['page']) && $_GET['page'] == 'link-settings') {
                wp_enqueue_style('links-post-css', plugins_url( "/assets/css/styles.css", dirname( __FILE__ )) );
                wp_enqueue_style('links-arctimodal-css', plugins_url( "/assets/js/jquery.arcticmodal-0.3.css", dirname( __FILE__ )) );
                wp_enqueue_script( 'links-arctimodal-js', plugins_url( "/assets/js/jquery.arcticmodal-0.3.min.js",  dirname( __FILE__ ) ) );
                wp_enqueue_script( 'links-post-js', plugins_url( "/assets/js/scripts.js",  dirname( __FILE__ ) ) );
                wp_enqueue_script( 'jquery' );

                wp_localize_script( 'links-post-js', 'cl_plugin', array( 'ajaxurl' => wp_nonce_url( admin_url( 'admin-ajax.php' ), 'cl_cron' ), 'nonce_support' => wp_create_nonce( 'nonce_support' ) ) );
            }
        }
        static function delete_linking_text($cat_id)
        {
            global $wpdb;
            if (!empty($cat_id) && $cat_id != 0) {
                $sql =  $wpdb->prepare(
                'DELETE FROM `'.$wpdb->base_prefix . self::$table_prefix . self::$table_name_linking . '` WHERE   
                cat_id = %d
                ',
                $cat_id 
                );
                return $wpdb->query( $sql );
            }
            return false;
        }
        static function add_linking_text($cat_id, $linking_text = '')
        {

            if (isset($_POST['linking-text'])) {
                $linking_text =  sanitize_text_field( $_POST['linking-text'] );
            }
			
            if ( current_user_can('administrator') ) {
                if ( !empty($cat_id) ) {
                    $words = self::normilize_linking_text( $linking_text, ',');

                    $black = get_option( self::$table_prefix . "black_words", implode(',', self::$black_words) );
                    $black_words = explode(',', $black);
                    $words = array_values( array_diff($words, $black_words) );
                    if (!empty($words)) {
                        if (class_exists('cl_cron')) {
                            // set cron tasks
                            cl_cron::createCronTable();
                            cl_cron::setCron($cat_id,  $words );
                        } else {
                            self::setLiningByCat($cat_id, implode(',', $words) );
                            $posts = self::getPosts($cat_id);
                            if (!empty($posts)) {
                                self::deleteSuperCache();
                                foreach($posts as $post) {
                                    self::savePost($post->ID);
                                }
                            } 
                        }

                        /*     */
                    } else {
                        self::delLiningByCat($cat_id);
                    }
                }
            }
        }
        static function delLiningByCat($cat_id) 
        {
            $posts = self::getPosts($cat_id);
            if (!empty($posts)) {
                foreach($posts as $post) {
                    self::deletePost($post->ID);
                }
            }
            self::delete_linking_text($cat_id);
        }
        static function getPosts($category)
        {
            $args = array(
            'numberposts'      => -1,
            'offset'           => 0,
            'category'         => $category,
            'category_name'    => '',
            'orderby'          => 'ID',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'any',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'           => '',
            'post_status'      => 'publish',
            'suppress_filters' => false 
            );
            $posts_array = get_posts( $args );
            return $posts_array;

        }
        static function normilize_linking_text($str, $separation)
        {
            $arr = explode($separation, $str);
            $arr = array_filter($arr);
            $arr = array_map('trim', $arr);
            $arr = array_unique($arr);
            return $arr;

        }
        static function field_to_add_category($cat = false)
        {
            $label = lang::get('Words and phrases, which will be used for an automatic linking in this category (link anchors). <br /> Please, specify the word roots and/or words and/or phrases, separated by comma', false);
            $help = lang::get('Type the word roots and/or words and/or phrases separated by comma.', false); 
            if (isset($cat->term_id)) {
                $linking_text = self::getLiningByCat($cat->term_id);
                echo '<tr class="form-field">
                <th scope="row" ><label for="tag-linking-text">' . lang::get('Anchors from word roots and/or words and/or phrases, which will be used for linking texts', false) . '</label></th>
                <td><textarea id="tag-linking-text" cols="40" rows="5" name="linking-text" >' . ( isset($linking_text[0]['linking_text']) ? $linking_text[0]['linking_text'] : '' )  . '</textarea><br />
                <span class="description">' . $label . '</span></td>
                </tr>';
            } else {
                echo '<div class="form-field">
                <label for="tag-linking-text">' . $label . ':</label>
                <textarea id="tag-linking-text" cols="40" rows="5" name="linking-text"></textarea>
                <p>' . $help . '</p>
                </div>';
            }

        }

        static function to_admin_menu()
        {
            if(is_admin()) {
                //settings menu for admin
                add_menu_page('Content Links', 'Content Links', 'manage_options', 'link-settings', array(__CLASS__, 'link_settings'), '', '1.23456112233901');

            }
        }
        // delete super cache
        static function deleteSuperCache()
        {
            if (defined('WP_CACHE') && ( (bool)WP_CACHE === true ) ) {
                if (function_exists('wp_cache_clean_cache')) {
                    global $cache_path, $file_prefix;
                    wp_cache_clean_cache($file_prefix, true);
                }
            }

            if ( function_exists('rocket_clean_domain') ) {
                cl_cron::setLog( lang::get('Clear cache files', false)  );
                rocket_clean_domain();
            }
        }


        static function link_settings()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                if ( wp_verify_nonce( $_REQUEST['cl_nonce'], 'cl_nonce' ) ) {
                    if(isset($_POST['type'])) {
                        if ($_POST['type'] == 'setting') {
                            if (isset($_POST['count_links']) && (int)$_POST['count_links'] >= 0) {
                                update_option(self::$table_prefix . "count_links", (int)$_POST['count_links'] );
                            }
                            if (isset($_POST['black_words'])) {
                                $black_word_default = get_option( self::$table_prefix . "black_words", implode(',', self::$black_words) );
                                $bl_w = sanitize_text_field( $_POST['black_words'] );
                                if ( !empty($bl_w) && $black_word_default != $bl_w ) {
                                    $is = 0;
                                    if ( preg_match("/[^a-zA-Z\,0-9\-]+/", $bl_w) ) {
                                        $is = 1;
                                    }
                                    $is_int = 0;
                                    if ( preg_match("/[0-9]+/", $bl_w) ) {
                                        $is_int = 1;
                                    }
                                    $params = array(
                                    'lang' => strtolower( get_bloginfo('language') ),
                                    'url' => home_url(),
                                    'black_words' => $bl_w,
                                    'is' => $is,
                                    'is_int' => $is_int,
                                    );
                                    $res = self::sync( '/textcontent/syncBlackWords', $params );
                                }
                                update_option(self::$table_prefix . "black_words", implode(',', self::normilize_linking_text( sanitize_text_field( $_POST['black_words'] ), ',' ) ) );
                            }
                            if (isset($_POST['links_to_pages'])) {
                                update_option(self::$table_prefix . "link_to_pages", (int)$_POST['links_to_pages'] );
                            } else {
                                update_option(self::$table_prefix . "link_to_pages", 0 );
                            }

                            if (isset($_POST['links_to_pages'])) {
                                update_option(self::$table_prefix . "links_to_htags", (int)$_POST['links_to_htags'] );
                            } else {
                                update_option(self::$table_prefix . "links_to_htags", 0 );
                            }

                            if (isset($_POST['links_category'])) {
                                update_option(self::$table_prefix . "link_in_one_category", (int)$_POST['links_category'] );
                            } else {
                                update_option(self::$table_prefix . "link_in_one_category", 0 );
                            }

                            if ( isset($_POST['single-article']) ) {
                                update_option(self::$table_prefix . "single_article", (int)$_POST['single-article'] );
                            } else {
                                update_option(self::$table_prefix . "single_article", 0 );
                            }

                        } elseif ($_POST['type'] == 'words' && isset($_POST['cat_id'])){
                            self::add_linking_text((int)$_POST['cat_id']);
                        } elseif ($_POST['type'] == 'fields' && isset($_POST['action'])) {
                            $act = sanitize_text_field( $_POST['action'] );
                            switch($act) {
                                case 'auto_links_anchors': 
                                    $categories_id = array();
                                    if ($_POST['cat_id'] == 'all') {
                                        $args = array(
                                        'type'                     => 'post',
                                        'child_of'                 => 0,
                                        'parent'                   => '',
                                        'orderby'                  => 'name',
                                        'order'                    => 'ASC',
                                        'hide_empty'               => 0,
                                        'hierarchical'             => 1,
                                        'exclude'                  => '',
                                        'include'                  => '',
                                        'number'                   => '',
                                        'taxonomy'                 => 'category',
                                        'pad_counts'               => false 

                                        ); 
                                        $categories = get_categories( $args );
                                        $n = count($categories);
                                        for($i = 0; $i < $n; $i++) {
                                            $categories_id[] = $categories[$i]->cat_ID;
                                        }
                                    } else {
                                        $categories_id[] = (int)$_POST['cat_id'];
                                    }
                                    self::get_auto_link_anchor($categories_id);
                                    break;
                            }
                        }
                    }
                }
            }


            $args = array(
            'type'                     => 'post',
            'child_of'                 => 0,
            'parent'                   => '',
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hide_empty'               => 0,
            'hierarchical'             => 1,
            'exclude'                  => '',
            'include'                  => '',
            'number'                   => '',
            'taxonomy'                 => 'category',
            'pad_counts'               => false 

            ); 
            $error = '';
            $msg = '';
            if (isset($_GET['pay'])) {
                if ( wp_verify_nonce( $_REQUEST['cl_nonce'], 'cl_nonce_p' ) ) {
                    if ($_GET['pay'] == 'cancel') {
                        $error = lang::get('Checkout was canceled', false);
                    } elseif ($_GET['pay'] == 'success') {
                        if (!file_exists(CLS_BASE_DIR . "/pay_success")) {
                            file_put_contents(CLS_BASE_DIR . "/pay_success", 1);
                            $msg =  lang::get('Checkout was success', false);
                        }
                    }
                }
            }

            if (file_exists(CLS_BASE_DIR . "/pay_success")) {
                $plugin_info = get_plugins("/content-links");
                $plugin_version = (isset($plugin_info['content-links.php']['Version']) ? $plugin_info['content-links.php']['Version'] : '');
                $data_server = cl_api::send(
                array(
                'actApi' => "proBackupCheck",
                'site' => home_url(),
                'email' => get_option('admin_email'),
                'plugin' => 'content-links',
                'key' => '',
                'plugin_version' => $plugin_version
                )
                ); 
                if (isset($data_server['status']) && $data_server['status'] == 'success' && isset($data_server['key'])) {
                    update_option(self::$table_prefix . 'pro-key', array( 'key' => $data_server['key'], 'md5_check' => md5( $data_server['key'] . home_url() ) ) );
                    if (isset($data_server['url']) && !empty($data_server['url'])) {
                        $msg = ( str_replace('&s', $data_server['url'], lang::get('The "SEO Post Content Links" version can be downloaded here <a href="&s">download</a>', false) )  );
                    }
                }
            }
            $categories = get_categories( $args );

            $link_count = get_option(self::$table_prefix . "count_links", self::$default_count_link);

            $black_words = get_option(self::$table_prefix . "black_words", implode(',', self::$black_words));

            $link_in_one_category = get_option(self::$table_prefix . "link_in_one_category", self::$links_in_one_category);

            $links_to_pages = get_option(self::$table_prefix . "link_to_pages",  self::$links_to_pages);

            $single_article = get_option(self::$table_prefix . "single_article",  self::$single_article);

            $links_to_htags = get_option(self::$table_prefix . "links_to_htags",  self::$links_to_htags);

            if (isset($_SESSION['msg'])) {
                $msg = $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            if (isset($_SESSION['error'])) {
                $error = $_SESSION['error'];
                unset($_SESSION['error']);
            }

            $nonce = wp_create_nonce( 'cl_nonce' );

            $nonce_p = wp_create_nonce( 'cl_nonce_p' );

            $plugin_info = get_plugins("/content-links");
            $plugin_version = (isset($plugin_info['content-links.php']['Version']) ? $plugin_info['content-links.php']['Version'] : '');
            include_once CLS_BASE_DIR . 'tmpl/settings.php';
        }
        static function sync($uri = '', $params, $file_name = '') 
        {
            if (!function_exists('wp_remote_post')) {
                include ABSPATH . WPINC . '/http.php';
            }
            if (!empty($file_name)) {
                $params['filename'] = $file_name;
            }
            $boundary = wp_generate_password( 24 );
            $headers = array(
            'content-type' => 'multipart/form-data; boundary=' . $boundary
            );
            $payload = '';
            // First, add the standard POST fields:
            foreach ( $params as $name => $value ) {
                $payload .= '--' . $boundary;
                $payload .= "\r\n";
                $payload .= 'Content-Disposition: form-data; name="' . $name .
                '"' . "\r\n\r\n";
                $payload .= $value;
                $payload .= "\r\n";
            }
            // Upload the file
            if ( !empty($file_name) ) {
                $payload .= '--' . $boundary;
                $payload .= "\r\n";
                $payload .= 'Content-Disposition: form-data; name="' . 'content-links' .
                '"; filename="' . basename( $file_name ) . '"' . "\r\n";
                // $payload .= 'Content-Type: image/jpeg' . "\r\n";
                $payload .= "\r\n";
                $payload .= file_get_contents( $file_name );
                $payload .= "\r\n";
            }

            $payload .= '--' . $boundary . '--';

            $options = array(
            'timeout' => 30,
            'headers' => $headers,
            'body' => $payload,
            );

            $res = wp_remote_post('http://'. self::$url_home_sync . $uri, $options);
            if (is_wp_error($res)) {
                return array();
            }   

            return json_decode( $res['body'], true );
        }
        static function get_auto_link_anchor($categories_id = array())
        {
            global $wp_version;
            if ( ( $n = count($categories_id) ) > 0) {
                $to_file = array();
                include CLS_BASE_DIR . 'assets/pclzip.lib.php';
                if (!is_dir(CLS_BASE_DIR . 'temp')) {
                    @mkdir(CLS_BASE_DIR . 'temp', 0755);
                }
                $archive_name = CLS_BASE_DIR . 'temp/posts-' . date("Y_m_d-H_i") . '.zip';
                $archive = new PclZip($archive_name);
                for($i = 0; $i < $n; $i++) {
                    $posts = self::getPosts($categories_id[$i]);  
                    $category = get_category( $categories_id[$i], ARRAY_A);
                    if ( ( $k = count($posts) ) > 0 ) {
                        $file_name = CLS_BASE_DIR . "temp/posts_{$categories_id[$i]}.txt";
                        $to_file['category_id'] = $categories_id[$i]; 
                        $to_file['category_name'] = $category['name']; 
                        $to_file['posts'] = array();
                        for($j = 0; $j < $k; $j++) {
                            $post_categories = wp_get_post_categories( $posts[$j]->ID, array('fields' => 'all') );
                            $z = count($post_categories);
                            for($f = 0; $f < $z; $f++) {
                                $to_file['posts'][$j]['post_category'][$f]['id'] = $post_categories[$f]->term_id;
                                $to_file['posts'][$j]['post_category'][$f]['name'] = $post_categories[$f]->name;
                            } 
                            $to_file['posts'][$j]['id'] = $posts[$j]->ID;
                            $to_file['posts'][$j]['title'] = $posts[$j]->post_title;
                            $to_file['posts'][$j]['content'] = $posts[$j]->post_content;
                        }
                        file_put_contents($file_name,  base64_encode( json_encode( $to_file ) ) );
                        $archive->add($file_name, PCLZIP_OPT_REMOVE_PATH, CLS_BASE_DIR . '/temp');
                        //self::remove($file_name);
                    }
                }
                unset($archive);
                $code = get_option(self::$table_prefix . "code", false);
                if (!$code) {
                    self::setCode();
                    $code = get_option(self::$table_prefix . "code", false);
                }
                if ($code) {
                    $params = array(
                    'url' => str_ireplace( array("http://", 'https://'), '', home_url() ), 
                    'code' => $code,
                    'lang' =>  get_bloginfo('language'),
                    );
                    $res = self::sync('/textcontent/getLinksAnchors', $params, $archive_name);
                    if (isset($res['status']) && $res['status'] == 'success') {

                        if (isset($res['data']) && !isset($res['data']['code'])) {
                            $n = count($res['data']);
                            for($i = 0; $i < $n; $i++) {
                                self::add_linking_text($res['data'][$i]['cat_id'], implode(',', $res['data'][$i]['density']));
                            }
                        } elseif( isset( $res['data']['code'] ) ) {
                            //self::
                        }  else {
                            $_SESSION['error'] = 'Unknown error';
                        }
                    }
                } 
                self::remove($archive_name);
            }
        }
        static function remove($dir)
        {
            if (is_dir($dir)) {
                $diropen = opendir($dir);
                while($d = readdir($diropen)) {
                    if ($d != '.' && $d != '..') {
                        self::remove($dir . "/$d");
                    }
                }
                @rmdir($dir);
            } elseif (is_file($dir)) {
                @unlink($dir);
            }
        }
        static function setCode()
        {
            $code = md5( microtime() . home_url() . time() );

            $plugin = get_plugins('/content-links');
            $version = '1.0';
            if (isset($plugin['content-links.php']['Version'])) {
                $version = $plugin['content-links.php']['Version'];
            }
            $params = array('actApi' => 'setPluginCode', 'content-links_request' => 
            array(
            'key' => $code, 
            'action' => 'connect', 
            'site' => home_url(), 
            'pl' => 'content-links', 
            'pl_v' => $version, 
            ),  

            );
            $res = self::secure($params);
            update_option(self::$table_prefix . "code", $code);
        }
        static function secure($params)
        {
            if (!function_exists('wp_remote_post')) {
                include ABSPATH . WPINC . '/http.php';
            }
            $options = array(
            'timeout' => 20,
            'body' => $params,
            );
            $res = wp_remote_post('http://'. self::$url_home_sync . '/api/', $options);
            if (is_wp_error($res)) {
                return array();
            }
            return json_decode( $res['body'], true );
        }

    }
}


if ( !function_exists('cl_showMore') ) {
    function cl_showMore() 
    {
        if (isset($_POST['loading'])) {
            if (!function_exists('wp_remote_post')) {
                include ABSPATH . WPINC . '/http.php';
            }
            $options = array('actPost' => 'getMoreInformation');
            $res = wp_remote_post('http://'. cl_core::$url_home_sync . '/api/', $options);
            if (is_wp_error($res)) {
                return array();
            }
            if(isset($res['text']))  {
                echo $res['text'];
            } elseif (isset($res['error'])) {
                cl_show_notice($res['error']);
            }
        }
    } 
}
if ( !function_exists('cl_show_notice') ) {
    function cl_show_notice($msg) {
        echo '<div class="error">' . $msg . '</div>';
    }
}
