<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PTB_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
        
        private static $options;
        
        public function __construct($version='1.0.0') {

		$this->plugin_name = 'ptb';
		$this->version     = $version;

		$this->load_dependencies();
		$this->set_locale();
                self::$options = new PTB_Options( $this->get_plugin_name(), $this->get_version() );
                $this->define_admin_hooks();
                if(!is_admin()){
                    $this->define_public_hooks();
                }
               
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - PTB_Loader. Orchestrates the hooks of the plugin.
	 * - PTB_i18n. Defines internationalization functionality.
	 * - PTB_Admin. Defines all hooks for the dashboard.
	 * - PTB_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
                $plugindir = plugin_dir_path( dirname( __FILE__ ) );
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $plugindir . 'includes/class-ptb-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $plugindir.'includes/class-ptb-i18n.php';
		require_once $plugindir.'includes/class-ptb-utils.php';
		require_once $plugindir.'includes/class-ptb-cmb-base.php';
		require_once $plugindir.'includes/class-ptb-cmb-text.php';
		require_once $plugindir.'includes/class-ptb-cmb-email.php';
		require_once $plugindir.'includes/class-ptb-cmb-textarea.php';
		require_once $plugindir.'includes/class-ptb-cmb-select.php';
		require_once $plugindir.'includes/class-ptb-cmb-checkbox.php';
		require_once $plugindir.'includes/class-ptb-cmb-radio-button.php';
		require_once $plugindir.'includes/class-ptb-cmb-image.php';
		require_once $plugindir.'includes/class-ptb-cmb-link-button.php';
		require_once $plugindir.'includes/class-ptb-cpt.php';
		require_once $plugindir.'includes/class-ptb-ctx.php';
		require_once $plugindir.'includes/class-ptb-ptt.php';
		require_once $plugindir.'includes/class-ptb-options.php';
		require_once $plugindir.'includes/class-ptb-form-cpt.php';
		require_once $plugindir.'includes/class-ptb-form-ctx.php';
		require_once $plugindir.'includes/class-ptb-form-ptt.php';
		require_once $plugindir.'includes/class-ptb-form-import-export.php';
		require_once $plugindir.'includes/class-ptb-form-css.php';

		//classes for working with themplates
		require_once $plugindir.'includes/class-ptb-form-ptt-them.php';
		require_once $plugindir.'includes/class-ptb-form-ptt-archive.php';
		require_once $plugindir.'includes/class-ptb-form-ptt-single.php';

		require_once $plugindir.'includes/class-ptb-list-cpt.php';
		require_once $plugindir.'includes/class-ptb-list-ctx.php';
		require_once $plugindir.'includes/class-ptb-list-ptt.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once $plugindir.'admin/class-ptb-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $plugindir.'public/class-ptb-public.php';

		$this->loader = new PTB_Loader();
                do_action('ptb_loaded');
	}
        
     

                /**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the PTB_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PTB_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

            $plugin_admin   = new PTB_Admin( $this->get_plugin_name(), $this->get_version(), self::$options );
            $this->loader->add_action('init', self::$options, 'ptb_register_custom_taxonomies', 10 );
            $this->loader->add_action('init', self::$options, 'ptb_register_custom_post_types', 11 );
            $this->loader->add_action('save_post', self::$options, 'save_custom_meta', 10, 3 );
            //Ajax actions registration

            $this->loader->add_action('wp_ajax_ptb_ajax_post_type_name_validate', self::$options, 'ptb_ajax_post_type_name_validate' );
            $this->loader->add_action('wp_ajax_ptb_ajax_post_type_remove', self::$options, 'ptb_ajax_post_type_remove' );
            $this->loader->add_action('wp_ajax_ptb_ajax_taxonomy_name_validate', self::$options, 'ptb_ajax_taxonomy_name_validate' );
            $this->loader->add_action('wp_ajax_ptb_ajax_taxonomy_remove', self::$options, 'ptb_ajax_taxonomy_remove' );
            $this->loader->add_action('wp_ajax_ptb_ajax_taxonomy_remove', self::$options, 'ptb_ajax_taxonomy_remove' );
            $this->loader->add_action('wp_ajax_ptb_ajax_themes', self::$options, 'ptb_ajax_theme' );
            $this->loader->add_action('wp_ajax_ptb_ajax_themes_save', self::$options, 'ptb_ajax_theme_save' );
            $this->loader->add_action('wp_ajax_ptb_ajax_get_post_type', self::$options, 'ptb_ajax_get_post_type' );
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

            $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
            $this->loader->add_action('admin_init', $plugin_admin, 'register_plugin_settings',11 );
            $this->loader->add_action('admin_head', $plugin_admin, 'add_ptb_shortcode' );
            $this->loader->add_action('wp_ajax_nopriv_ptb_single_lightbox', self::$options, 'single_lightbox');
            $this->loader->add_action('wp_ajax_ptb_single_lightbox', self::$options, 'single_lightbox');

            $this->init_custom_meta_box_types();
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
          
            $plugin_public = new PTB_Public( $this->get_plugin_name(), $this->get_version() );
            $this->loader->add_action('wp_head', self::$options, 'ptb_filter_wp_head' );
            $this->loader->add_action('body_class', self::$options, 'ptb_filter_body_class' );
            $this->loader->add_action('loop_start', self::$options, 'ptb_filter_post_type_start' );
            $this->loader->add_filter('post_class', self::$options, 'ptb_filter_post_type_class' );
            $this->loader->add_filter('the_title', self::$options, 'ptb_filter_post_type_title', 100, 1 );
            $this->loader->add_filter('the_content', self::$options, 'ptb_filter_post_type_content_post',100,1);
            $this->loader->add_filter('get_the_excerpt', self::$options, 'ptb_filter_post_type_exceprt_post',1,1);
            $this->loader->add_filter('post_thumbnail_html', self::$options, 'ptb_post_thumbnail', 100, 1);
            $this->loader->add_action('loop_end', self::$options, 'ptb_filter_post_type_end' );
            $this->loader->add_filter('pre_get_posts', self::$options, 'ptb_filter_cpt_category_archives', 1, 1 );
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
            $this->loader->add_action('wp_head',$plugin_public,'define_ajaxurl');
            
	}

	/**
	 * Creates instances of custom meta boxes
	 *
	 * @since 1.0.0
	 */
	private function init_custom_meta_box_types() {

		new PTB_CMB_Text('text', $this->plugin_name, $this->version );
                new PTB_CMB_Email('email', $this->plugin_name, $this->version);
		new PTB_CMB_Textarea('textarea', $this->plugin_name, $this->version );
		new PTB_CMB_Radio_Button('radio_button', $this->plugin_name, $this->version );
		new PTB_CMB_Checkbox('checkbox', $this->plugin_name, $this->version );
		new PTB_CMB_Select('select', $this->plugin_name, $this->version );
		new PTB_CMB_Image('image', $this->plugin_name, $this->version );
		new PTB_CMB_Link_Button('link_button', $this->plugin_name, $this->version );
               
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    PTB_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
        
        public static function get_option(){
            
            return self::$options;
        }
}
