<?php

/**
 * The plugin options management class
 *
 * @link       http://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 */

/**
 * The plugin options helper class
 *
 *
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB_Options {

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
	 * The plugin options array
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $options The options of the plugin.
	 */
	private $options;

	/**
	 * Custom Post Types array
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $option_custom_post_types The options of custom post types.
	 */
	private $option_custom_post_types;

	/**
	 * Custom Taxonomies array
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $option_custom_taxonomies The options of custom taxonomies.
	 */
	private $option_custom_taxonomies;

	/**
	 * Custom Post Types Templates array
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $option_post_type_templates The options of custom post types templates.
	 */
	public $option_post_type_templates;
       

	/* options keys */
	private $options_key_plugin_name;
	private $options_key_version;
	private $options_key_custom_css;
	private $options_key_custom_post_types;
	private $options_key_custom_taxonomies;
	private $options_key_post_type_templates;
	private $options_key_custom_meta_boxes;
	private static $render_instance = false;
	public  static $rendder_content = false;
	private $prefix_cpt_id;
	private $prefix_ctx_id;
	private $prefix_ptt_id;

	private $settings_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $plugin_name The name of this plugin.
	 * @var      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		/* options keys */
		$this->settings_key = $this->plugin_name . '_plugin_options';

		$this->options_key_plugin_name = 'plugin';
		$this->options_key_version     = 'version';

		$this->options_key_custom_post_types   = 'cpt';
		$this->options_key_custom_post_types   = 'cpt';
		$this->options_key_custom_taxonomies   = 'ctx';
		$this->options_key_post_type_templates = 'ptt';
		$this->options_key_custom_css         = 'css';
		$this->options_key_custom_meta_boxes   = 'meta_boxes';

		$this->prefix_cpt_id = 'ptb_cpt_';
		$this->prefix_ctx_id = 'ptb_ctx_';
		$this->prefix_ptt_id = 'ptb_ptt_';

		$this->load_options();

	}

	//==================================================================================================================
	// Options
	//==================================================================================================================

	/**
	 * Loads the plugin options.
	 * Default options created if plugin options are empty
	 *
	 * @since 1.0.0
	 */
	protected function load_options() {

		$this->options = get_option( $this->settings_key );

		if ( empty( $this->options ) ) {

			$this->options = $this->get_options_blueprint();

		}
		$this->option_custom_post_types = &$this->options[ $this->options_key_custom_post_types ];

		$this->option_custom_taxonomies = &$this->options[ $this->options_key_custom_taxonomies ];

		$this->option_post_type_templates = &$this->options[ $this->options_key_post_type_templates ];

	}

	public function get_options_blueprint() {

		return array(
			$this->options_key_plugin_name         => $this->plugin_name,
			$this->options_key_version             => $this->version,
			$this->options_key_custom_post_types   => array(),
			$this->options_key_custom_taxonomies   => array(),
			$this->options_key_post_type_templates => array()
		);

	}

	/**
	 * Updates the plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $recreate
	 *
	 * @return bool
	 */
	public function update( $recreate = false ) {

		if ( $recreate ) {

			if ( delete_option( $this->settings_key ) ) {

				return add_option( $this->settings_key, $this->options, '', 'yes' );

			} else {

				return false;

			}

		} else {
            
			return update_option( $this->settings_key, $this->options );

		}
	}

	//==================================================================================================================
	// Getters
	//==================================================================================================================

	/**
	 * Getter of settings key
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_settings_key() {

		return $this->settings_key;

	}

	/**
	 * Getter of options array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_options() {

		return $this->options;

	}

	/**
	 * setter of options array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function set_options( $options ) {

		$this->options = $options;
	}

	/**
	 * Getter of custom post types array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_custom_post_types_options() {

		return $this->option_custom_post_types;

	}

	/**
	 * Getter of custom taxonomies array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_custom_taxonomies_options() {

		return $this->option_custom_taxonomies;

	}

	/**
	 * Getter of custom post types templates array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_templates_options() {

		return $this->option_post_type_templates;

	}

	/**
	 * Setter of custom post types array
	 *
	 * @since 1.0.0
	 *
	 * @param array $value
	 *
	 */
	public function set_custom_post_types_options( $value ) {

		$this->options[ $this->options_key_custom_post_types ] = $value;

	}
        
        /**
	 * Getter of custom css 
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_custom_css() {

		return isset($this->options[ $this->options_key_custom_css ])?$this->options[ $this->options_key_custom_css ]:false;

	}
        
        /**
	 * Setter of custom post types array
	 *
	 * @since 1.0.0
	 *
	 * @param array $value
	 *
	 */
	public function set_custom_css( $value ) {

		$this->options[ $this->options_key_custom_css ] = $value;

	}

	/**
	 * Setter of custom taxonomies array
	 *
	 * @since 1.0.0
	 *
	 * @param array $value
	 *
	 */
	public function set_custom_taxonomies_options( $value ) {

		$this->options[ $this->options_key_custom_taxonomies ] = $value;

	}

	/**
	 * Setter of custom post types templates array
	 *
	 * @since 1.0.0
	 *
	 * @param array $value
	 *
	 */
	public function set_templates_options( $value ) {

		$this->options[ $this->options_key_post_type_templates ] = $value;

	}


	//==================================================================================================================
	// Custom Post Type
	//==================================================================================================================

	/**
	 * Registers the custom post types of the plugin
	 *
	 * @since    1.0.0
	 */
	public function ptb_register_custom_post_types() {

		$cpt_objects_array = $this->get_custom_post_types();
               
		foreach ( $cpt_objects_array as $cpt_object ) {
                    
			register_post_type( $cpt_object->slug, $cpt_object->get_args() );
                        $cmb_options = $this->get_cpt_cmb_options( $cpt_object->slug );
                        if(!empty($cmb_options)){
                            add_action( 'add_meta_boxes_' . $cpt_object->slug, array( $this, 'add_custom_meta_boxes' ), 10, 1 );
                        }
                        add_filter('manage_edit-'.$cpt_object->slug.'_columns', array( $this, 'ptb_colums' ));
                       
		}
                add_shortcode($this->plugin_name, array( $this, 'ptb_shortcode' ) );
		flush_rewrite_rules(); //TODO need optimization
	}

	/**
	 * Adds custom post type to options
	 *
	 * @since 1.0.0
	 *
	 * @param PTB_Custom_Post_Type $cpt
	 */
	public function add_custom_post_type( $cpt ) {

		$cpt->id = $cpt->slug;

		$id                 = $cpt->id;
		$cpt_options        = &$this->options[ $this->options_key_custom_post_types ];
		$cpt_options[ $id ] = $cpt->serialize();

		$custom_meta_boxes = $cpt_options[ $id ][ $this->options_key_custom_meta_boxes ];
            
		foreach ( $custom_meta_boxes as $key => $options ) {
			if ( $options['deleted'] ) {
				unset( $cpt_options[ $id ][ $this->options_key_custom_meta_boxes ][ $key ] );
			}
		}

		$this->synchronize_post_type_to_taxonomy( $id, $cpt );

	}

	/**
	 * Post type options validation function.
	 * Checks is the post type name allowed to use?
	 * Used from dashboards trough ajax call.
	 *
	 * @since 1.0.0
	 */
	public function ptb_ajax_post_type_name_validate() {

		if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-ptb-cpt-nonce' ) ) {

			$cpt_slug = esc_attr($_POST['slug']);
                        $reserved_by_theme = array('menu','section');
			$message  = '';

			if ( post_type_exists( $cpt_slug ) ) {

                            $message = sprintf( __( 'Post type "%s" exists', 'ptb'), $cpt_slug );
			}
			elseif ( strlen( $cpt_slug ) > 20 ) {

                            $message = sprintf( __( 'Post type name can\'t be longer than 20 symbols', 'ptb'), $cpt_slug );

			}
			elseif ( strlen( $cpt_slug ) < 1 ) {

                            $message = sprintf( __( 'Post type name can\'t be empty', 'ptb'), $cpt_slug );

			}
                        elseif(is_plugin_active('themify-builder/themify-builder.php') && in_array($cpt_slug,$reserved_by_theme)){
                            
                            $message = sprintf( __( 'Post type name is reserved by themify-builder, please type another name', 'ptb'), $cpt_slug );
                        }
			elseif ( preg_match( "/[^a-z0-9_-]/", $cpt_slug, $match ) ) {

                            $message = sprintf( __( 'Post type name should only contain lowercase letters and the underscore or dash character', 'ptb') );
			}

			die( $message );

		}

	}

	/**
	 * Get post type layout themplate by type
	 * Used from dashboards trough ajax call.
	 *
	 * @since 1.0.0
	 */
	public function ptb_ajax_theme() {

		if ( isset( $_REQUEST['template'] ) && $_REQUEST['template'] ) {

			$name  = $_REQUEST['template'];
			$type  = strtoupper( $name );
			$class = 'PTB_Form_PTT_' . $type;

			if ( class_exists( $class ) && $_REQUEST[ $this->plugin_name . '-ptt'] ) {

				$themplate_id = $_REQUEST[ $this->plugin_name . '-ptt'];
				$them         = new $class( $this->plugin_name, $this->version, $this, $themplate_id );

				$them->add_settings_section( $name );
			}
			die;

		}
	}
        
        
        /**
	 * Set post type colums
	 *
	 * @since 1.0.0
	 */
	public function ptb_colums($columns ) {
            foreach($columns as $col=>$value){
                if(strpos($col,'taxonomy-')!==false){
                    unset($columns[$col]);
                }
            }
            return $columns;
	}

	/**
	 * Save post themplate
	 * Used from dashboards trough ajax call.
	 *
	 * @since 1.0.0
	 */
	public function ptb_ajax_theme_save() {
		if ( check_ajax_referer( $this->plugin_name . '_them_ajax', $this->plugin_name . '_nonce', true ) ) {
			$themplate_id = $_REQUEST[ $this->plugin_name . '-' . PTB_Form_PTT_Them::$key ];
			$them         = new PTB_Form_PTT_Them( $this->plugin_name, $this->version, $this, $themplate_id );
			$them->save_themplate( $_POST );
		}

	}

	/**
	 * get post_type data
	 * Used from shortcode trough ajax call.
	 *
	 * @since 1.0.0
	 */
	public function ptb_ajax_get_post_type() {
		if ( isset( $_POST['post_type'] ) && $_POST['post_type'] ) {
			$post_type   = $_POST['post_type'];
                        $result = array();
                        $templateObject = $this->get_post_type_template_by_type( $post_type );
                        if($templateObject){
                            $cmb_options = $post_support = $post_taxonomies = array();
                            $this->get_post_type_data( $post_type, $cmb_options, $post_support, $post_taxonomies );
                             $post_filer = array();
                            if ( array_search( 'category', $post_support ) !=false) {
                                    $post_taxonomies[] = 'category';
                            }

                            if ( ! empty( $post_taxonomies ) ) {
                                $empty = false;
                                $html = '<div class="'.$this->plugin_name.'_custom_select">';
                                $html.='<select size="5" id="ptb_post_filer" multiple="multiple">';
                                foreach ( $post_taxonomies as $k => $taxes ) {

                                            $values = get_categories( array(
                                                    'type'       => $post_type,
                                                    'hide_empty' => 1,
                                                    'taxonomy'   => $taxes
                                            ) );
                                            if ( empty( $values ) ) {
                                                    continue;
                                            }
                                            $tax = get_taxonomy( $taxes );
                                            if($tax){
                                                $result['taxes'][ $k ]['values'] = $values;
                                                $result['taxes'][ $k ]['label']  = $tax->labels->name;
                                                $result['taxes'][ $k ]['name']   = $taxes;
                                                $post_filer[$taxes] = $values;
                                                $empty = TRUE;
                                                foreach($values as $v){
                                                    $html.='<option value="'.$v->term_id.'">'.$v->name.'</option>';
                                                }
                                            }
                                    }
                                if($empty){
                                    $html.='</select>';
                                    $html.='</div>';
                                    $result['data'][ 'post_filer' ]['label'] = __('Post Filter','ptb');
                                    $result['data'][ 'post_filer' ]['type']   = 'container';
                                    $result['data'][ 'post_filer' ]['html'] = $html;
                                    $result['data'][ 'spacer' ]['type'] = 'spacer';
                                    $result['data'][ 'spacer' ]['classes'] = 'ptb_tinymce_spacer';
                                }
                            }

                            $sortable = array(
                                    'date'=>__('Date','ptb'),
                                    'id'=>__('Id','ptb'),
                                    'author'=>__('Author','ptb'),
                                    'title'=>__('Title','ptb'),
                                    'name'=>__('Name','ptb'),
                                    'modified'=>__('Modified','ptb'),
                                    'rand'=>__('Rand','ptb'),
                                    'comment_count'=>__('Comment count','ptb')
                            );
                            $fields   = $grids = $by = array();

                            $lang = PTB_Utils::get_current_language_code();

                            foreach ( $sortable as $key=>$s ) {
                                    $fields[] = array( 'text' => $s, 'value' => $key );
                            }
                            foreach($cmb_options as $key=>$m){
                                if(in_array($m['type'],PTB_Form_PTT_Archive::$sorttypes) && 
                                   (($m['type']=='text' && !$m['repeatable']) 
                                    || 
                                   ($m['type']=='select' && !$m['multipleSelects'])
                                    ||   
                                   ($m['type']=='checkbox' && count($m['options']==1))
                                    )){
                                        $fields[] = array( 'text' => isset($m['name'][$lang])?$m['name'][$lang]:'', 'value' => $key );
                                    }
                            }
                            $layouts = PTB_Form_PTT_Archive::$layouts;
                            foreach ( $layouts as $k => $l ) {
                                    $grids[] = array( 'text' => ucfirst($k), 'value' => $k );
                            }
                            unset( $sortable );
                            $by[] = array(
                                    'text'  => __( 'Ascending', 'ptb'),
                                    'value' => 'asc'
                            );
                            $by[] = array(
                                    'text'  => __( 'Descending', 'ptb'),
                                    'value' => 'desc'
                            );
                            $archive        = $templateObject->get_archive();
                            unset( $archive['layout'] );
                            $archive['posts_per_page'] = $archive['ptb_ptt_offset_post'];
                            $archive['style']          = $archive['ptb_ptt_layout_post'];
                            unset( $archive['ptb_ptt_layout_post'], $archive['ptb_ptt_offset_post'] );
                            if ( ! $archive['posts_per_page'] ) {
                                    $archive['posts_per_page'] = get_option( 'posts_per_page' );
                            }
                            foreach ( $archive as $key => $arh ) {
                                    $key                    = str_replace( array( 'ptb_ptt_', '_post' ), '', $key );
                                    $name                   = ucfirst(str_replace( '_', ' ', $key ));
                                    $result['data'][ $key ] = array(
                                            'label' => $name,
                                            'value' => $arh
                                    );

                                    if ( $key == 'order' ) {
                                            $result['data'][ $key ]['type']   = 'listbox';
                                            $result['data'][ $key ]['values'] = $by;
                                    } elseif ( $key == 'orderby' ) {
                                            $result['data'][ $key ]['type']   = 'listbox';
                                            $result['data'][ $key ]['values'] = $fields;
                                    } elseif ( $key == 'pagination' ) {
                                            $result['data'][ $key ]['type']   = 'radio';
                                            $result['data'][ $key ]['values'] = 1;
                                    } elseif ( $key == 'style' ) {
                                            $result['data'][ $key ]['type']   = 'listbox';
                                            $result['data'][ $key ]['values'] = $grids;
                                    }
                                    else {
                                            $result['data'][ $key ]['type'] = 'textbox';
                                    }
                            }
                        }
                        $result['title'] = __('PTB Shortcode Options','ptb');
                        $result = apply_filters('ptb_ajax_shortcode_result',$result,$post_type);
			die( json_encode( $result ) );
		}
	}


	//todo: add documentation
	public function ptb_ajax_post_type_remove() {

		if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-ptb-cpt-nonce' ) ) {

			$cpt_slug = $_POST['slug'];

			if ( $this->has_custom_post_type( $cpt_slug ) ) {

				$cpt = $this->get_custom_post_type( $cpt_slug );
				$this->remove_custom_post_type( $cpt->id );
				$this->update();

				$result = '1';

			} else {

				$result = '0';

			}

			die( $result );

		}

	}

	/**
	 * Edits (replace) custom post type in options
	 *
	 * @since 1.0.0
	 *
	 * @param $id
	 * @param PTB_Custom_Post_Type $cpt
	 * @param bool $continue
	 */
	public function edit_custom_post_type( $id, $cpt, $continue = true ) {


		if ( $this->has_custom_post_type( $id ) ) {

			$cpt->id = $cpt->slug;

			$meta_keys = $cpt->meta_boxes;
                      
			foreach ( $meta_keys as $key => $options ) {
				if ( $options['deleted'] ) {
					$this->remove_custom_meta( $id, $key );
					unset( $cpt->meta_boxes[ $key ] );
				}

			}
                        
			//remove old id
			unset( $this->option_custom_post_types[ $id ] );

			//add new id
			$new_id                                    = $cpt->slug;
			$this->option_custom_post_types[ $new_id ] = $cpt->serialize();

			$ptt = $this->get_post_type_template_by_type( $id );

			if ( ! is_null( $ptt ) ) {

                            $ptt->set_post_type( $cpt->slug );
                            $this->update_post_type_template( $ptt );
			}
                        if($continue){
                            $this->synchronize_post_type_to_taxonomy( $id, $cpt );
                        }
			global $wpdb;
			$wpdb->query( "UPDATE $wpdb->posts SET post_type = '$new_id' WHERE post_type = '$id'" );


			//get post type templates
			$post_type_template_set = $this->option_post_type_templates;

			foreach ( $post_type_template_set as $template_id => $post_type_template_args ) {

				//check for assigned template
				if ( $post_type_template_args['post_type'] === $id ) {

					$template_obj = new PTB_Post_Type_Template( $this->plugin_name, $this->version );
					$template_obj->deserialize( $post_type_template_args );
					$template_obj->set_id( $template_id );
					$template_obj->set_post_type( $new_id );

					//update template custom meta boxes

					$archive = $template_obj->get_archive();
					$single  = $template_obj->get_single();
					$template_obj->set_archive( $archive );
					$template_obj->set_single( $single );

				}

			}

		}

	}

	/**
	 * Removes custom post type from database and options
	 *
	 * @since 1.0.0
	 *
	 * @param $id
	 *
	 * @return bool true if post type removed successfully and false otherwise
	 */
	public function remove_custom_post_type( $id ) {

		if ( $this->has_custom_post_type( $id ) ) {

			$query = new WP_Query( array(
				'post_type'      => $id,
				'post_status'    => array(
					'publish',
					'pending',
					'draft',
					'auto-draft',
					'future',
					'private',
					'inherit',
					'trash'
				),
				'posts_per_page' => - 1
			) );

			$posts_to_delete = $query->get_posts();

			foreach ( $posts_to_delete as $post ) {

				wp_delete_post( $post->ID, true );

			}

			$this->remove_custom_post_type_from_custom_taxonomies( $id );
                        $themplate = $this->get_post_type_template_by_type($id);
                        if(isset($themplate)){
                            $this->remove_post_type_template($themplate->get_id());
                        }
			unset( $this->option_custom_post_types[ $id ] );

			return true;

		}

		return false;

	}

	/**
	 * Removes custom custom post type from taxonomies
	 *
	 * @since 1.0.0
	 *
	 * @param string $id the id of custom taxonomy
	 */
	public function remove_custom_post_type_from_custom_taxonomies( $id ) {

		foreach ( $this->option_custom_taxonomies as $ctx_id => $ctx_option ) {

			$ctx = new PTB_Custom_Taxonomy( $this->plugin_name, $this->version );

			$ctx->deserialize( $ctx_option );

			if ( $ctx->is_attached_to_post_type( $id ) ) {

				$ctx->attach_to_post_type( $id, false );

				$this->edit_custom_taxonomy( $ctx_id, $ctx );

			}

		}

	}

	/**
	 * Synchronize all custom post types registered by this plugin
	 * from custom taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id post type id
	 * @param PTB_Custom_Post_Type $cpt
	 */
	private function synchronize_post_type_to_taxonomy( $id, $cpt ) {

		$taxonomies = $this->get_custom_taxonomies();

		foreach ( $taxonomies as $ctx ) {

			$state = $cpt->has_taxonomy( $ctx->slug );
			$ctx->attach_to_post_type( $cpt->slug, $state );

			if ( $cpt->slug != $id ) {

				$ctx->attach_to_post_type( $id, false );

			}

			$this->edit_custom_taxonomy( $ctx->id, $ctx, false );

		}

	}

	/**
	 * Checks for custom post type existence by id
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function has_custom_post_type( $id ) {

		return array_key_exists( $id, $this->option_custom_post_types );

	}

	/**
	 * Returns the custom post type by ID. Returns null if post type does not exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id the id of post type
	 *
	 * @return PTB_Custom_Post_Type | null
	 */
	public function get_custom_post_type( $id ) {

		if ( $this->has_custom_post_type( $id ) ) {

			$cpt_options = $this->option_custom_post_types[ $id ];

			$cpt = new PTB_Custom_Post_Type( $this->plugin_name, $this->version );
			$cpt->deserialize( $cpt_options );
         

		} else {

			$cpt = null;

		}

		return $cpt;
	}

	/**
	 * Returns the custom post types array
	 *
	 * @since 1.0.0
	 *
	 * @return PTB_Custom_Post_Type[]
	 */
	public function get_custom_post_types() {

		$cpt_objects_array = array();

		foreach ( $this->option_custom_post_types as $id => $source ) {
			$cpt_object = new PTB_Custom_Post_Type( $this->plugin_name, $this->version );
			$cpt_object->deserialize( $source );
			array_push( $cpt_objects_array, $cpt_object );
		}

		return $cpt_objects_array;
	}

	/**
	 * Returns all registered public custom post types
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_all_post_types() {
		$args     = array(
			'public'   => true,
			'_builtin' => false

		);
		$output   = 'objects'; // or names
		$operator = 'and'; // 'and' or 'or'
		return get_post_types( $args, $output, $operator );
	}

	/**
	 * Returns all post types containing given taxonomy
	 *
	 * @since 1.0.0
	 *
	 * @param string $tax
	 *
	 * @return string[]
	 */
	public static function get_post_types_by_taxonomy( $tax ) {

		$result = array();

		$post_types = PTB_Options::get_all_post_types();

		foreach ( $post_types as $post_type ) {

			$taxonomies = get_object_taxonomies( $post_type->name );

			if ( in_array( $tax, $taxonomies ) ) {

				PTB_Utils::add_to_array( $post_type, $result );

			}

		}

		return $result;
	}

	//==================================================================================================================
	// Custom Taxonomies
	//==================================================================================================================

	/**
	 * Registers the custom taxonomies of the plugin
	 *
	 * @since    1.0.0
	 */
	public function ptb_register_custom_taxonomies() {

		$ctx_objects_array = $this->get_custom_taxonomies();

		foreach ( $ctx_objects_array as $ctx_object ) {

			register_taxonomy( $ctx_object->slug, $ctx_object->attach_to, $ctx_object->get_args() );

		}

		flush_rewrite_rules(); //TODO need optimization

	}

	/**
	 * Adds new custom taxonomy
	 *
	 * @since 1.0.0
	 *
	 * @param PTB_Custom_Taxonomy $ctx
	 */
	public function add_custom_taxonomy( $ctx ) {

		$ctx->id = $ctx->slug;

		$id                = $ctx->id;
		$ct_options        = &$this->options[ $this->options_key_custom_taxonomies ];
		$ct_options[ $id ] = $ctx->serialize();

		$this->synchronize_taxonomy_to_post_type( $id, $ctx );

	}

	/**
	 * Taxonomy type options validation function.
	 * Checks is the taxonomy name allowed to use?
	 * Used from dashboards trough ajax call.
	 *
	 * @since 1.0.0
	 */
	public function ptb_ajax_taxonomy_name_validate() {

		if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-ptb-ctx-nonce' ) ) {

			$ctx_slug = $_POST['slug'];
			$message  = '';

			if ( taxonomy_exists( $ctx_slug ) ) {

				$message = sprintf( __( 'Taxonomy "%s" exists', 'ptb'), $ctx_slug );

			}

			if ( strlen( $ctx_slug ) > 32 ) {

				$message = sprintf( __( 'Taxonomy name can\'t be longer than 32 symbols', 'ptb') );

			}

			if ( strlen( $ctx_slug ) < 1 ) {

				$message = sprintf( __( 'Taxonomy name can\'t be empty', 'ptb') );

			}

			if ( preg_match( "/[^a-z0-9_]/", $ctx_slug, $match ) ) {

				$message = sprintf( __( 'Taxonomy name should only contain lowercase letters and the underscore character', 'ptb') );

			}

			die( $message );

		}

	}

	//todo: add documentation
	public function ptb_ajax_taxonomy_remove() {

		if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-ptb-ctx-nonce' ) ) {

			$ctx_slug = $_POST['slug'];

			if ( $this->has_custom_taxonomy( $ctx_slug ) ) {

				$ctx = $this->get_custom_taxonomy( $ctx_slug );
				$this->remove_custom_taxonomy( $ctx->id );
				$this->update();

				$result = '1';

			} else {

				$result = '0';

			}

			die( $result );

		}

	}

	/**
	 * Updates custom taxonomy by id
	 *
	 * @since 1.0.0
	 *
	 * @param string $id
	 * @param PTB_Custom_Taxonomy $ctx
	 * @param bool $continue
	 */
	public function edit_custom_taxonomy( $id, $ctx, $continue = true ) {

		if ( $this->has_custom_taxonomy( $id ) ) {

			$ctx->id = $ctx->slug;

			$this->option_custom_taxonomies[ $id ] = $ctx->serialize();

			$continue && $this->synchronize_taxonomy_to_post_type( $id, $ctx );

			//remove old id
			unset( $this->option_custom_taxonomies[ $id ] );

			//add new id
			$new_id                                    = $ctx->slug;
			$this->option_custom_taxonomies[ $new_id ] = $ctx->serialize();

			global $wpdb;
			$wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = '$new_id' WHERE taxonomy = '$id'" );

		}

	}

	/**
	 * Removes custom taxonomy from database and options
	 *
	 * @since 1.0.0
	 *
	 * @param $id
	 *
	 * @return bool true if taxonomy removed successfully and false otherwise
	 */
	public function remove_custom_taxonomy( $id ) {

		if ( $this->has_custom_taxonomy( $id ) ) {

			$all_tax_to_delete = get_terms( $id, array( 'hide_empty' => 0 ) );

			foreach ( $all_tax_to_delete as $term ) {
				wp_delete_term( $term->term_id, $id );
			}


			$this->remove_custom_taxonomy_from_custom_post_types( $id );

			unset( $this->option_custom_taxonomies[ $id ] );

			return true;

		}

		return false;

	}

	/**
	 * Removes custom taxonomy from custom post types
	 *
	 * @since 1.0.0
	 *
	 * @param string $id the id of custom taxonomy
	 */
	public function remove_custom_taxonomy_from_custom_post_types( $id ) {

		foreach ( $this->option_custom_post_types as $cpt_id => $cpt_option ) {

			$cpt = new PTB_Custom_Post_Type( $this->plugin_name, $this->version );

			$cpt->deserialize( $cpt_option );

			if ( $cpt->has_taxonomy( $id ) ) {

				$cpt->set_taxonomy( $id, false );

				$this->edit_custom_post_type( $cpt_id, $cpt );

			}
		}

	}

	/**
	 * Synchronize all custom post types registered by this plugin
	 * from custom taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id taxonomy id
	 * @param PTB_Custom_Taxonomy $ctx
	 */
	private function synchronize_taxonomy_to_post_type( $id, $ctx ) {

		$post_types = $this->get_custom_post_types();

		foreach ( $post_types as $cpt ) {

			$state = $ctx->is_attached_to_post_type( $cpt->slug );
			$cpt->set_taxonomy( $ctx->slug, $state );

			if ( $ctx->slug != $id ) {

				$cpt->set_taxonomy( $id, false );

			}

			$this->edit_custom_post_type( $cpt->id, $cpt, false );

		}

	}

	/**
	 * Checks for custom taxonomy existence by id
	 *
	 * @since 1.0.0
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function has_custom_taxonomy( $id ) {

		return array_key_exists( $id, $this->option_custom_taxonomies );

	}

	/**
	 * Returns the custom taxonomy by ID. Returns null if taxonomy does not exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id the id of taxonomy
	 *
	 * @return PTB_Custom_Taxonomy | null
	 */
	public function get_custom_taxonomy( $id ) {

		if ( $this->has_custom_taxonomy( $id ) ) {

			$ctx_options = $this->option_custom_taxonomies[ $id ];
			$ctx         = new PTB_Custom_Taxonomy( $this->plugin_name, $this->version );
			$ctx->deserialize( $ctx_options );

		} else {

			$ctx = null;

		}

		return $ctx;

	}

	/**
	 * Returns the custom taxonomies array
	 *
	 * @since 1.0.0
	 * @return PTB_Custom_Taxonomy[]
	 */
	public function get_custom_taxonomies() {

		$ctx_objects_array = array();

		foreach ( $this->option_custom_taxonomies as $id => $source ) {
			$ctx_object = new PTB_Custom_Taxonomy( $this->plugin_name, $this->version );
			$ctx_object->deserialize( $source );
			array_push( $ctx_objects_array, $ctx_object );
		}

		return $ctx_objects_array;

	}

	/**
	 * Returns all non build in and public taxonomies
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_all_custom_taxonomies() {
		$args     = array(
			'public'   => true,
			'_builtin' => false

		);
		$output   = 'objects'; // or names
		$operator = 'and'; // 'and' or 'or'
		return get_taxonomies( $args, $output, $operator );
	}

	//==================================================================================================================
	// Custom Meta Boxes
	//==================================================================================================================


	/**
	 * todo: add documentation
	 * @since 1.0.0
	 * @return mixed|void
	 */
	public static function get_cmb_types() {
            return apply_filters( 'ptb_cmb_types', array() );
	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $cpt_type
	 * @param $cmb_key
	 *
	 * @return bool
	 */
	private function remove_custom_meta( $cpt_type, $cmb_key ) {
		if ( $this->has_custom_post_type( $cpt_type ) ) {

			$query = new WP_Query( array(
				'post_type'      => $cpt_type,
				'post_status'    => array(
					'publish',
					'pending',
					'draft',
					'auto-draft',
					'future',
					'private',
					'inherit',
					'trash'
				),
				'posts_per_page' => - 1
			) );

			$posts = $query->get_posts();

			foreach ( $posts as $post ) {
				delete_post_meta( $post->ID, 'ptb_' . $cmb_key );
			}

			return true;

		}

		return false;
	}

	/**
	 * Add custom meta boxes from plugin options
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post the post object
	 */
	public function add_custom_meta_boxes( $post ) {
           
		add_meta_box(
			'ptb_cmb_' . $post->ID,
			__( "Meta Box", 'ptb'),
			array( $this, 'add_custom_meta_box_cb' ),
			$post->post_type,
			'normal',
			'default',
			array(
				'post_type' => $post
			)
		);

	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $cpt_id
	 *
	 * @return array
	 */
	public function get_cpt_cmb_options( $cpt_id ) {

		if ( $this->has_custom_post_type( $cpt_id ) ) {
			return $this->option_custom_post_types[ $cpt_id ][ $this->options_key_custom_meta_boxes ];
		} else {
			return array();
		}

	}

	/**
	 * @since 1.0.0
	 *
	 * @param string $post_type
	 * @param array $cmb_options post type options
	 * @param array $post_support post type support
	 * @param array $post_taxonomies post type support taxonomies
	 *
	 * @return void
	 */
	public function get_post_type_data( $post_type, array &$cmb_options = array(), array &$post_support = array(), array &$post_taxonomies = array() ) {

		$cmb_options     = $this->get_cpt_cmb_options( $post_type );
		$post_taxonomies = $this->get_cpt_cmb_taxonomies( $post_type );
		$post_support    = $this->get_cpt_cmb_support( $post_type );
		$unset           = array_search( 'page-attributes', $post_support );
		if ( isset( $post_support[ $unset ] ) ) {
			unset( $post_support[ $unset ] );
		}
		if ( ! empty( $post_support ) ) {
                    foreach ( $post_support as $support ) {
                        $cmb_options[ $support ] = array( 'type' => $support );
                    }
		}

		if ( ! empty( $post_taxonomies ) ) {
			$tag = array_search( 'post_tag', $post_taxonomies );
			if ($tag!==false) {
                            $post_support[]          = 'post_tag';
                            $cmb_options['post_tag'] = array( 'type' => 'post_tag' );
                            unset( $post_taxonomies[ $tag ] );
                        }
                        if ( ! empty( $post_taxonomies ) ) {
                            $category = array_search( 'category', $post_taxonomies );
                            if ($category!==false) {
                                $post_support[]          = 'category';
                                $cmb_options['category'] = array( 'type' => 'category' );
                                unset( $post_taxonomies[ $category ] );
                            }
                            if ( ! empty( $post_taxonomies ) ) {
                                $post_support[]            = 'taxonomies';
                                $cmb_options['taxonomies'] = array( 'type' => 'taxonomies' );
                            }
                        }
				
			
		}
		$post_support[]              = 'custom_text';
		$post_support[]              = 'date';
		$post_support[]              = 'custom_image';
                $post_support[]              = 'permalink';
		$cmb_options['custom_text']  = array( 'type' => 'custom_text' );
		$cmb_options['date']         = array( 'type' => 'date' );
		$cmb_options['custom_image'] = array( 'type' => 'custom_image' );
                $cmb_options['permalink'] = array( 'type' => 'permalink' );

	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $cpt_id
	 *
	 * @return array
	 */
	public function get_cpt_cmb_support( $cpt_id ) {

		if ( $this->has_custom_post_type( $cpt_id ) ) {
			return $this->option_custom_post_types[ $cpt_id ]['supports'];
		} else {
			return array();
		}

	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $cpt_id
	 *
	 * @return array
	 */
	public function get_cpt_cmb_taxonomies( $cpt_id ) {
               
		if ( $this->has_custom_post_type( $cpt_id ) ) {
			return $this->option_custom_post_types[ $cpt_id ]['taxonomies'];
		} else {
			return array();
		}

	}

	/**
	 * Callback for add_meta_box action
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post current post
	 */
	public function add_custom_meta_box_cb( $post ) {

		wp_nonce_field( 'ptb_meta_box', 'ptb_meta_box_nonce' );

		$cmb_options = $this->get_cpt_cmb_options( $post->post_type );
                
		?>
                
                    <div class="ptb_post_cmb_wrapper">
                            <?php
                            foreach ( $cmb_options as $meta_box_id => $args ) {

                                    ?>
                                    <div class="ptb_post_cmb_item_wrapper ptb_post_cmb_item_<?php print( $args['type'] ) ?>" data-ptb-cmb-type="<?php print( $args['type'] ) ?>"
                                         id="<?php print( $meta_box_id ) ?>">
                                            <div class="ptb_post_cmb_title_wrapper">
                                                    <h4 class="ptb_post_cmb_name"><?php echo PTB_Utils::get_label( $args['name'] ); ?></h4>
                                            </div>
                                            <div class="ptb_post_cmb_body_wrapper">
                                                    <?php
                                                    do_action( 'ptb_cmb_render', $post, $meta_box_id, $args );
                                                    do_action( 'ptb_cmb_render_' . $args['type'], $post, $meta_box_id, $args );
                                                    ?>
                                                    <p class="ptb_post_cmb_description"><?php echo PTB_Utils::get_label( $args['description'] ); ?></p>
                                            </div>
                                    </div>

                            <?php

                            }

                            ?>
                    </div>
            
	<?php

	}

	/**
	 * Callback for save_post action
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @return mixed
	 */
	public function save_custom_meta( $post_id, $post, $update ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['ptb_meta_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['ptb_meta_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'ptb_meta_box' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, its safe for us to save the data now. */
              
		do_action( 'ptb_cmb_update', $post, $this );

	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $option_key
	 * @param $prefix
	 *
	 * @return mixed|string
	 */
	private function get_next_id( $option_key, $prefix ) {
		$collection = $this->options[ $option_key ];

		if ( empty( $collection ) ) {
			$first_id = $prefix . str_pad( '0', 4, '0', STR_PAD_LEFT );

			return $first_id;
		} else {
			$collection_keys = array_keys( $collection );
			$max_id          = max( $collection_keys );

			return ++ $max_id;
		}
	}

	//==================================================================================================================
	// Post Type Templates
	//==================================================================================================================

	/**
	 * Returns post type template options by id
	 *
	 * @since 1.0.0
	 *
	 * @param string $id
	 *
	 * @return array
	 */
	public function get_post_type_template( $id ) {

		if ( $this->has_post_type_template( ( $id ) ) ) {

			return $this->option_post_type_templates[ $id ];

		} else {

			return array();

		}

	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function has_post_type_template( $id ) {

		return array_key_exists( $id, $this->option_post_type_templates );

	}

	/**
	 * @param string $type
	 *
	 * @return null|PTB_Post_Type_Template
	 */
	public function get_post_type_template_by_type( $type ) {

		$templates = $this->get_post_type_templates();

		foreach ( $templates as $template ) {

			if ( $template->get_post_type() === $type ) {

				return $template;

			}

		}

		return null;

	}

	/**
	 * Updates post type template options.
	 * The new one weill be added if nothing to update
	 *
	 * @since 1.0.0
	 *
	 * @param PTB_Post_Type_Template $ptt
	 */
	public function update_post_type_template( $ptt ) {

		$ptt_id = $ptt->get_id();
		if ( false === $this->has_post_type_template( $ptt_id ) ) {

			$ptt_id = $this->get_next_id( $this->options_key_post_type_templates, $this->prefix_ptt_id );

		}

		$this->option_post_type_templates[ $ptt_id ] = $ptt->serialize($ptt_id);

	}

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $id
	 */
	public function remove_post_type_template( $id ) {

		if ( $this->has_post_type_template( $id ) ) {

			unset( $this->option_post_type_templates[ $id ] );
                        return TRUE;
		}
                return FALSE;
	}

	/**
	 * Returns the custom post types args array
	 *
	 * @since 1.0.0
	 * @return array
	 */
	/*public function get_custom_post_types() {

		$custom_post_types = array();

		foreach ( $this->option_custom_post_types as $id => $source ) {
			$custom_post_type = new PTB_Custom_Post_Type( $this->plugin_name, $this->version );
			$custom_post_type->deserialize( $source );
			$custom_post_types[ $id ] = $custom_post_type->get_args();
		}

		return $custom_post_types;
	}*/

	/**
	 * todo: add documentation
	 * @since 1.0.0
	 *
	 * @param $atts
	 *
	 * @return string|void
	 */
	public function ptb_shortcode( $atts ) {

		$type = esc_attr($atts['type']);
                $template = $this->get_post_type_template_by_type( $type );
		if ( null == $template ) {
                    return;
		} 
                
		// WP_Query arguments
		$args  = array(
			'post_type'      => $type,
			'post_status'    => 'publish',
			'nopaging'       => false,
			'posts_per_page' => isset( $atts['posts_per_page'] ) && intval($atts['posts_per_page'])>0 ? $atts['posts_per_page'] : - 1,
			'paged'          => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
			
                );
                if(isset($atts['post_id']) && is_numeric($atts['post_id'])){
                    $args['p'] = $atts['post_id'];
                    $atts['style'] = '';
                }
                else{
                    $args['orderby'] = $atts['orderby'];
                    $args['order']   = esc_attr($atts['order']);
                    $taxes = array();
                    foreach ( $atts as $key => $value ) {

                            if ( strpos( $key, $this->plugin_name . '_tax_' ) !== false ) {
                                    $key     = str_replace( $this->plugin_name . '_tax_', '', $key );
                                    $taxes[] = array(
                                            'taxonomy' => esc_attr($key),
                                            'field'    => 'slug',
                                            'terms'    => esc_attr($value)
                                    );
                            }
                    }

                    if ( ! empty( $taxes ) ) {

                            $args['tax_query']             = $taxes;
                            $args['tax_query']['relation'] = 'AND';

                    }
                    if(isset(PTB_Form_PTT_Archive::$sortfields[$atts['orderby']])){
                        $args['orderby'] = $atts['orderby'];
                    }
                    else{
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = $this->plugin_name.'_'.$atts['orderby'];
                    }
                }
		// The Query
		$query = new WP_Query( $args );

	

		// The Loop
		if ( $query->have_posts() ) {
                        $html = '';
			$cmb_options = $post_support = $post_taxonomies = array();
			$this->get_post_type_data( $atts['type'], $cmb_options, $post_support, $post_taxonomies );
			$themplate        = new PTB_Form_PTT_Them( $this->plugin_name, $this->version, $this, false );
			$themplate_layout = isset($args['p'])?$template->get_single():$template->get_archive();
                        if(isset($atts['post_filer']) && !isset($atts['post_id'])){
                            $terms = explode(',',$atts['post_filer']);
                            $query_terms = get_terms($post_taxonomies,array('hide_empty'=>false,'include'=>$terms,'fields'=>'all'));
                            if(!empty($query_terms)){
                                $html.='<ul class="'.$this->plugin_name.'-post-filter">';
                                foreach($query_terms as $t){
                                    $html.='<li data-tax="'.$t->term_id.'"><a href="'.get_term_link($t) .'">'.$t->name.'</a></li>';
                                }
                                $html.='</ul>';
                            }
                        }
                        $html.= '<div class="'.$this->plugin_name.'_loops_wrapper clearfix '.$this->plugin_name.'_'.$atts['style'].'">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_meta               = array();
                                $class = array($this->plugin_name.'_post','clearfix');
				$post_meta['post_url']   = get_permalink();
				$post_meta['taxonomies'] = !empty($post_taxonomies)?wp_get_post_terms( get_the_ID(), array_values($post_taxonomies)):array();
                                if(isset($atts['post_filer']) && !empty($post_meta['taxonomies'])){
                                    foreach ($post_meta['taxonomies'] as $p){
                                        $class[] = $this->plugin_name.'-tax-'.$p->term_id;
                                    }
                                }
                                $post_meta = array_merge( $post_meta, get_post_custom(), get_post( '', ARRAY_A ) );
                                $class = implode(' ',$class);
				$html .= '<article id="post-'.get_the_ID().'" itemscope itemtype="http://schema.org/Article" class="'.$class.'">';
				$html .= $themplate->display_public_themplate( $themplate_layout['layout'], $post_support, $cmb_options, $post_meta, $type, false );
				$html .= '</article>';
			}
			$html .= '</div>';
			if ( isset( $atts['pagination'] ) && $query->max_num_pages > 1 ) {
                                $html.='<div class="'.$this->plugin_name.'_pagenav">';
				$html .= paginate_links( array(
					'total' => $query->max_num_pages,
				) );
                                $html.='</div>';
			}
                      
                        // Restore original Post Data
                        wp_reset_postdata();
			return $html;
		} 
	}

	/**
	 * @param $title
	 * @param null $id
	 *
	 * @return string
	 */
	public function ptb_filter_post_type_title( $title, $id = null ) {

		if ($id !== get_the_ID() ) {

			return $title;

		}


		$templateObject = $this->get_post_type_template_by_type( get_post_type() );

		if ( is_null( $templateObject ) ) {

			return $title;

		}

		if ( $templateObject->has_single() && is_singular( get_post_type() ) ) {

			return '';

		}

		if ( $templateObject->has_archive() && ( is_tax() || is_category() || is_post_type_archive( get_post_type() ) ) ) {

			return '';

		}

		return $title;

	}

	


	/*
	 * @since 1.0.0
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function ptb_filter_post_type_content_post( $content ) {
		
		$post_type      = get_post_type();
		$templateObject = $this->get_post_type_template_by_type( $post_type );

		if (is_null( $templateObject ) || self::$rendder_content) {
                        self::$rendder_content = false;
			return $content;
		}
		elseif(self::$render_instance=='excerpt'){
			return '';
		}
		self::$render_instance = 'content';
		$single  = $templateObject->has_single() && is_singular( $post_type );
		$archive = ! $single && $templateObject->has_archive() && ( is_tax() || is_category() || is_post_type_archive( $post_type ) );
		if ( $single || $archive ) {
			$cmb_options = $post_support = $post_taxonomies = array();
			$this->get_post_type_data( $post_type, $cmb_options, $post_support, $post_taxonomies );
			$post_meta               = array();
			$post_meta               = array_merge( $post_meta, get_post_custom(), get_post( '', ARRAY_A ) );
			$post_meta['post_url']   = get_permalink();
			$post_meta['taxonomies'] = !empty($post_taxonomies)?wp_get_post_terms( get_the_ID(), array_values($post_taxonomies) ):array();
			$themplate               = new PTB_Form_PTT_Them( $this->plugin_name, $this->version, $this, false );
			$themplate_layout        = $single ? $templateObject->get_single() : $templateObject->get_archive();
                        
			if ( isset( $themplate_layout['layout'] ) ) {
				return $themplate->display_public_themplate( $themplate_layout['layout'], $post_support, $cmb_options, $post_meta, $post_type, $single );
			}
		}
		
		return $content;
	}
	
		/*
	 * @since 1.0.0
	 *
	 * @param $exceprt
	 *
	 * @return string
	 */
	public function ptb_filter_post_type_exceprt_post( $content ) {
		
		$post_type      = get_post_type();
		$templateObject = $this->get_post_type_template_by_type( $post_type );

		if (is_null( $templateObject )) {
			return $content;
		}
		elseif(self::$render_instance=='content' || is_singular()){
			return '';
		}
		self::$render_instance = 'excerpt';
		$archive = $templateObject->has_archive() && ( is_tax() || is_category() || is_post_type_archive( $post_type ) );
		if ($archive) {
			$cmb_options = $post_support = $post_taxonomies = array();
			$this->get_post_type_data( $post_type, $cmb_options, $post_support, $post_taxonomies );
			$post_meta               = array();
			$post_meta               = array_merge( $post_meta, get_post_custom(), get_post( '', ARRAY_A ) );
			$post_meta['post_url']   = get_permalink();
			$post_meta['taxonomies'] = !empty($post_taxonomies)?wp_get_post_terms( get_the_ID(), array_values($post_taxonomies) ):array();
			$themplate               = new PTB_Form_PTT_Them( $this->plugin_name, $this->version, $this, false );
			$themplate_layout        = $templateObject->get_archive();

			if ( isset( $themplate_layout['layout'] ) ) {
				return $themplate->display_public_themplate( $themplate_layout['layout'], $post_support, $cmb_options, $post_meta, $post_type, false );
			}
		}
		
		return $content;
	}
        
        
        public function ptb_filter_body_class( $classes ) {

		$post_type      = get_post_type();
		$templateObject = $this->get_post_type_template_by_type( $post_type );
		if (is_null( $templateObject ) ) {
			return $classes;
		}
               
		$single  = $templateObject->has_single() && is_singular( $post_type );
		$archive = ! $single && $templateObject->has_archive() && ( is_tax() || is_category() || is_post_type_archive( $post_type ) );
                if ($archive){
                    $classes[] = $this->plugin_name.'_archive';
                    $classes[] = $this->plugin_name.'_archive_'.$post_type;
		}
                elseif ($single){
                    $classes[] = $this->plugin_name.'_single';
                    $classes[] = $this->plugin_name.'_single_'.$post_type;
		}

		return $classes;
	}
        
        public function ptb_filter_wp_head(){
            $custom_css = $this->get_custom_css();
            if($custom_css){
                echo '<!-- PTB CUSTOM CSS --><style type="text/css">'.$custom_css.'</style><!--/PTB CUSTOM CSS -->';
          
            }
        }
        
	public function ptb_filter_post_type_class( $classes ) {

		$post_type      = get_post_type();
		$templateObject = $this->get_post_type_template_by_type( $post_type );
		if (is_null( $templateObject ) ) {
			return $classes;
		}

		$single  = $templateObject->has_single() && is_singular( $post_type );
		$archive = ! $single && $templateObject->has_archive() && ( is_tax() || is_category() || is_post_type_archive( $post_type ) );
		if ( $single || $archive ){
                    echo ' itemscope itemtype="http://schema.org/Article" ';
                    $classes[]      = $this->plugin_name.'_post';
                    $classes[]      = 'clearfix';
		}

		return $classes;
	}
        
        public function ptb_filter_post_type_start(){
            $post_type = get_post_type();
            if ((is_tax() || is_category()) && is_post_type_archive( $post_type ) ){
                $templateObject = $this->get_post_type_template_by_type( $post_type );
                if ( isset( $templateObject ) ) {
                    $archive = $templateObject->get_archive();
                    if ( isset( $archive['layout'] ) ) {
                        $grid = $archive[ $this->prefix_ptt_id . 'layout_post' ];
                        echo '<div class="'.$this->plugin_name.'_loops_wrapper '.$this->plugin_name.'_'.$grid.' clearfix">';
                    }
                }
            }
        }
        
        public function ptb_filter_post_type_end(){
            $post_type = get_post_type();
             if ((is_tax() || is_category()) && is_post_type_archive( $post_type ) ){
                $templateObject = $this->get_post_type_template_by_type( $post_type );
                if ($templateObject) {
                    $archive = $templateObject->get_archive();
                    if ( isset( $archive['layout'] ) ) {
                         echo '</div>';
                    }
                }
             }
        }

        public function ptb_post_thumbnail( $html ) {
		$post_type      = get_post_type();
		$templateObject = $this->get_post_type_template_by_type( $post_type );
		if (!$templateObject) {
                    return $html;
		}

		return '';
	}


	/**
	 * @param WP_Query $query
	 *
	 * @return WP_Query
	 */
	public function ptb_filter_cpt_category_archives( &$query ) {
		if ($query->is_main_query() && ($query->is_category() || $query->is_tag() || $query->is_tax()) && empty( $query->query_vars['suppress_filters'] ) ) {
             
                    $cpt_objects_array = self::get_all_post_types();
                    if(!empty($cpt_objects_array)){		

                        $args = array_keys($cpt_objects_array);
                        $tax = $query->tax_query->queries;
                        if(!empty($tax)){
                            $tax = current($tax);
                            $val = current($tax['terms']);
                            $tax = $tax['taxonomy'];
                            $_types = $this->get_custom_post_types();
                            if(!empty($_types)){
                                $ptb_types = array();
                                foreach ($_types as $types){
                                    $ptb_types[ $types->slug] = 1;
                                }
                                unset($_types);
                                $taxonomy = get_taxonomy($tax);
                                if($taxonomy){
                                    $custom_post_type = $taxonomy->object_type;
                                    $template = false;
                                    if($custom_post_type){
                                        array_reverse($ptb_types);
                                        foreach($custom_post_type as $type){
                                            if(isset($ptb_types[$type])){
                                                $t = $this->get_post_type_template_by_type($type);
                                                if($t && $t->has_archive()){
                                                    $template = $t;
                                                    break;
                                                }
                                            }
                                        }
                                    
                                    if($template){
                                       $archive = $template->get_archive();
                                       unset($template);
                                       if(!$archive['ptb_ptt_pagination_post']){
                                           $query->set('posts_per_page',-1);
                                       }
                                       elseif($archive['ptb_ptt_offset_post']>0){
                                            $query->set('posts_per_page',intval($archive['ptb_ptt_offset_post']));
                                       }
                                       if(isset(PTB_Form_PTT_Archive::$sortfields[$archive['ptb_ptt_orderby_post']])){
                                           $query->set('orderby',$archive['ptb_ptt_orderby_post']);
                                       }
                                       else{
                                           $query->set('orderby','meta_value_num');
                                           $query->set('meta_key',$this->plugin_name.'_'.$archive['ptb_ptt_orderby_post']);
                                       }
                                       $query->set('order',$archive['ptb_ptt_order_post']);
                                    }
                                 }
                               }
                           }
                        }
                        $args[] ='post';
                        $query->set( 'post_type', $args );
                        $query->set( 'suppress_filters', true ); //wpml filter
                    }
		}

		return $query;

	}
     
	
	/**
	 * Returns post type templates options
	 *
	 * @since 1.0.0
	 *
	 * @return PTB_Post_Type_Template[]
	 */
	public function get_post_type_templates() {

		$post_type_templates = array();

		foreach ( $this->option_post_type_templates as $id => $options ) {

			$ptt = new PTB_Post_Type_Template( $this->plugin_name, $this->version );

			$ptt->set_id( $id );
			$ptt->deserialize( $options );

			PTB_Utils::add_to_array( $ptt, $post_type_templates );

		}

		return $post_type_templates;

	}
        
        
        

        public function single_lightbox(){
           if(!empty($_GET) && isset($_GET['id']) && is_numeric($_GET['id'])){
               $id = intval($_GET['id']);
               $post = get_post($id);        
               if(!$post || $post->post_status!='publish'){
                   wp_die();
               }
               $short_code =  '['.$this->plugin_name.' post_id='.$id.' type='.$post->post_type.']';
               
               echo '<div class="'.$this->plugin_name.'_single_lightbox">'.
                       do_shortcode($short_code);
                    '</div>';
               exit;
           }
        }

}