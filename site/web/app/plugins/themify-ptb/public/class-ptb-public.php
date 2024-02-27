<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    PTB
 * @subpackage PTB/public
 * @author     Themify <ptb@themify.me>
 */
class PTB_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $plugin_name The name of the plugin.
	 * @var      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
        }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in PTB_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The PTB_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            global $wp_styles;
            $srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src') );
            $plugin_url = plugin_dir_url( __FILE__ ) ;
            if (!in_array('font-awesome.css', $srcs) && !in_array('font-awesome.min.css', $srcs)  ) {
                wp_enqueue_style($this->plugin_name.'-fontawesome', plugin_dir_url(dirname(__FILE__)).'admin/themify-icons/font-awesome.min.css', array(), $this->version, 'all' );
            }
            wp_enqueue_style($this->plugin_name.'-colors', plugin_dir_url(dirname(__FILE__)).'admin/themify-icons/themify.framework.css', array(), $this->version, 'all' );
            wp_enqueue_style($this->plugin_name, $plugin_url . 'css/ptb-public.css', array(), $this->version, 'all' );
            wp_enqueue_style($this->plugin_name.'-lightbox', $plugin_url . 'css/lightbox.css', array(), '0.9.9', 'all' );
			// change by haresh
			wp_enqueue_style($this->plugin_name.'-lightbox-font-lightcase', $plugin_url . 'css/font-lightcase.css', array(), '0.9.9', 'all' );
			wp_enqueue_style($this->plugin_name.'-lightbox-max-640', $plugin_url . 'css/lightcase-max-640.css', array(), '0.9.9', 'all' );
			wp_enqueue_style($this->plugin_name.'-lightbox-min-641', $plugin_url . 'css/lightcase-min-641.css', array(), '0.9.9', 'all' );
			// end change by haresh

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in PTB_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The PTB_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            $plugin_url = plugin_dir_url( __FILE__ ) ;
            wp_enqueue_script( $this->plugin_name.'-lightbox-touch', $plugin_url. 'js/jquery.events.touch.js', array('jquery'), '1.4.5', false );
            wp_enqueue_script( $this->plugin_name.'-lightbox', $plugin_url. 'js/lightbox.js', array($this->plugin_name.'-lightbox-touch'), '2.1.1', false );
            wp_enqueue_script( $this->plugin_name.'-isotop', $plugin_url. 'js/jquery.isotope.min.js', array('jquery'), '2.2.0', false );
            wp_enqueue_script( $this->plugin_name, $plugin_url . 'js/ptb-public.js', array( $this->plugin_name.'-isotop',$this->plugin_name.'-lightbox'), $this->version, false );
	}
        
        
        /**
        * Register the ajax url
        *
        * @since    1.0.0
        */
       public static function define_ajaxurl(){
       ?>
           <script type="text/javascript">
               ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
           </script>
       <?php
       }


}
