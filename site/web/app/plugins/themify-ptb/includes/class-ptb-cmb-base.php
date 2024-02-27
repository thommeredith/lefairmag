<?php
/**
 * Custom meta box base class
 *
 * @link       http://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 */

/**
 * Custom meta box base class
 * All types should inherit this class
 *
 *
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB_CMB_Base {

	/**
	 * The ID of plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $plugin_name The ID of this plugin.
	 */
	public static $plugin_name;

	/**
	 * The version of plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The type of custom meta box.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $type The type of custom meta box.
	 */
	private $type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string $type The type of custom meta box.
	 * @var      string $plugin_name The name of plugin.
	 * @var      string $version The version of plugin.
	 */
	public function __construct( $type, $plugin_name, $version ) {

		$this->type        = $type;
		self::$plugin_name = $plugin_name;
		$this->version     = $version;

		$this->init_hooks();

	}

	/**
	 * Getter of property $type
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type() {

		return $this->type;

	}

	/**
	 * Getter of property $plugin_name
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {

		return self::$plugin_name;

	}

	/**
	 * @param string $id the id template
	 */
	public function action_template_type( $id, array $languages ) {

	}

	/**
	 * Getter of property $version
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plugin_version() {

		return $this->version;

	}

	/**
	 * Init hooks of custom meta boxes
	 */
	public function init_hooks() {

		// register custom meta box type
		add_filter( 'ptb_cmb_types', array( $this, 'filter_register_custom_meta_box_type' ), 10, 1 );

		// render the specific fields
		// of custom meta box to general template
		// on custom post type builder dashboard
		add_action( 'ptb_cmb_template_' . $this->get_type(), array( $this, 'action_template_type' ), 10, 2 );

		//render cmb
		add_action( 'ptb_cmb_render_' . $this->get_type(), array( $this, 'render_post_type_meta' ), 10, 3 );


		add_filter( 'ptb_cmb_update', array( $this, 'update_post_type_meta' ), 10, 3 );


		//add_action( 'ptb_cmb_render', function ( $post, $meta_key, $args ) {}, 10, 3 );
             
		add_action( 'ptb_cmb_print_' . $this->get_type(), array( $this, 'print_meta_data' ), 10, 3 );

		add_action( 'ptb_cmb_template', array( $this, 'action_template' ), 10, 1 );


		// render the themplate in admin
		add_action( 'ptb_template_' . $this->get_type(), array( $this, 'action_them_themplate' ), 10, 6 );
		// render the themplate in public
		add_action( 'ptb_template_public' . $this->get_type(), array( $this, 'action_public_themplate' ), 4, 6 );

	}


	/**
	 * Adds the custom meta type to the plugin meta types array
	 *
	 * @since 1.0.0
	 *
	 * @param array $cmb_types Array of custom meta types of plugin
	 *
	 * @return array
	 */
	public function filter_register_custom_meta_box_type( $cmb_types ) {

		return $cmb_types;

	}


	/**
	 * Renders the meta data in post template
	 *
	 * @param string $post_id
	 * @param string $meta_key The key of custom meta field
	 * @param string $meta_name The name of custom meta defined in post type builder
	 *
	 * @return string The html which should places on custom post template
	 */
	public function print_meta_data( $post_id, $meta_key, $meta_name ) {

		$value = get_post_meta( $post_id, $meta_key, true );

		return sprintf( '<p><strong>%s</strong>: %s</p>', $meta_name, esc_attr( $value ) );

	}


	/**
	 * Update post meta
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post
	 * @param PTB_Options $options_obj
	 */
	public function update_post_type_meta( $post, $options_obj ) {

		$cmb_options = $options_obj->get_cpt_cmb_options( $post->post_type );

		foreach ( $cmb_options as $id => $args ) {

			if ( $args['type'] == $this->get_type() ) {

				$meta_key = $id;

				if ( ! isset( $_POST[ $meta_key ] ) ) {
					return;
				}

				// Sanitize user input.
				$my_data = $_POST[ $meta_key ];

				$wp_meta_key = sprintf( '%s_%s', $this->get_plugin_name(), $meta_key );

				// Update the meta field in the database.
				update_post_meta( $post->ID, $wp_meta_key, $my_data );

			}

		}

	}


	/**
	 * Echo the repeatable texts
	 *
	 * @since 1.0.0
	 *
	 * @param string $display type
	 * @param array $data
         * @param string seperatorr 
	 */
	protected function get_repeateable_text( $display, array $data,$seperatorr=false ) {
		if ( empty( $data ) || ! $display ) {
			return;
		}
                $i=0;
                if($seperatorr){
                    $seperatorr = esc_attr($seperatorr);
                }
		?>
		
                <?php switch ( $display ):
                        case 'list':
                        case 'numbered_list':
                                ?>
                                <ul>
                                    <?php foreach ( $data as $value ): ?>
                                        <li>
                                            <?php if($seperatorr && $i!=0):?>
                                                <?php echo $seperatorr;?>
                                            <?php endif;?>
                                            <?php echo esc_attr( $value ) ?>
                                            <?php $i++?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php
                                break;
                        case 'bullet_list':
                            ?>
                                <ol>
                                        <?php foreach ( $data as $value ): ?>
                                             <li>
                                                <?php if($seperatorr && $i!=0):?>
                                                    <?php echo $seperatorr;?>
                                                <?php endif;?>
                                                <?php echo esc_attr( $value ) ?>
                                                <?php $i++?>
                                            </li>
                                        <?php endforeach; ?>
                                </ol>
                                <?php
                            break;
                        case 'paragraph':
                                ?>
                                <?php foreach ( $data as $value ): ?>
                                    <p class="<?php echo self::$plugin_name ?>_paragraph">
                                        <?php if($seperatorr && $i!=0):?>
                                            <?php echo $seperatorr;?>
                                        <?php endif;?>
                                        <?php echo esc_attr( $value ); ?>
                                        <?php $i++;?>
                                    </p>
                                <?php endforeach;?>
                                <?php
                                break;

                        case 'one_line':
                                ?>
                                <span class="<?php echo self::$plugin_name ?>_one_line">
                                    <?php foreach ( $data as $value ): ?>
                                        <?php if($seperatorr && $i!=0):?>
                                            <?php echo $seperatorr;?>
                                        <?php endif;?>
                                        <?php echo esc_attr( $value ); ?>
                                        <?php $i++;?>
                                    <?php endforeach;?>
                                </span>
                                <?php
                                break;

                                ?>
                        <?php endswitch; ?>
		
	<?php
	}

	/**
	 * Echo multilanguage html text for template
	 *
	 * @since 1.0.0
	 *
	 * @param number $id input id
	 * @param array $data saved data
	 * @param array $languages languages array
	 * @param string $key
         * @param string $name
	 */

	public static function module_multi_text( $id, array $data, array $languages, $key, $name,$input='text',$placeholder=false ) {
                ?>
		<div class="<?php echo self::$plugin_name ?>_back_active_module_row">
                    <?php if(!$placeholder):?>
			<div class="<?php echo self::$plugin_name ?>_back_active_module_label">
                            <label for="<?php echo self::$plugin_name ?>_<?php echo $id?>_<?php echo $key?>"><?php echo $name; ?></label>
                        </div>
                    <?php endif;?>
                    <?php self::module_language_tabs($id,$data,$languages,$key,$input,$placeholder);?>
		</div>
	<?php
	}
        
        
        /**
	 * Echo multilanguage html text for template
	 *
	 * @since 1.0.0
	 *
	 * @param number $id input id
	 * @param array $data saved data
	 * @param array $languages languages array
	 * @param string $key
	 */

	public static function module_language_tabs( $id, array $data, array $languages, $key,$input='text',$placeholder=false ) {
		$lng_count = count($languages)>1;
                ?>
                <?php if ( ! empty( $languages ) ): ?>
                        <div class="<?php echo self::$plugin_name ?>_back_active_module_input">
                                <?php if($lng_count):?>
                                    <ul class="<?php echo self::$plugin_name ?>_language_tabs">
                                            <?php foreach ( $languages as $code => $lng ): ?>
                                                    <li <?php if (isset( $lng['selected'] )): ?>class="<?php echo self::$plugin_name ?>_active_tab_lng"<?php endif; ?>>
                                                            <a class="<?php echo self::$plugin_name . '_lng_' . $code ?>"
                                                               title="<?php echo $lng['name'] ?>" href="#"></a></li>
                                            <?php endforeach; ?>
                                    </ul>
                                <?php endif;?>
                                <ul class="<?php echo self::$plugin_name ?>_language_fields">
                                        <?php foreach ( $languages as $code => $lng ): ?>
                                                <li <?php if (isset( $lng['selected'] )): ?>class="<?php echo self::$plugin_name ?>_active_lng"<?php endif; ?>>
                                                    <?php switch ($input){
                                                        case 'text':
                                                        ?>
                                                    <input id="<?php echo self::$plugin_name ?>_<?php echo $id?>_<?php echo $key?>" placeholder="<?php echo $placeholder?>" type="text" class="<?php echo self::$plugin_name ?>_towidth"
                                                               name="[<?php echo $id ?>][<?php echo $key ?>][<?php echo $code ?>]"
                                                               <?php if (isset( $data[ $key ] ) && isset( $data[ $key ][ $code ] )): ?>value="<?php echo $data[ $key ][ $code ] ?>"<?php endif; ?>/>
                                                        <?php
                                                        break;
                                                        case 'textarea':
                                                        ?>
                                                            <textarea id="<?php echo self::$plugin_name ?>_<?php echo $id?>_<?php echo $key?>" placeholder="<?php echo $placeholder?>" class="<?php echo self::$plugin_name ?>_towidth"
                                                               name="[<?php echo $id ?>][<?php echo $key ?>][<?php echo $code ?>]"><?php if (isset( $data[ $key ] ) && isset( $data[ $key ][ $code ] )): ?> <?php echo $data[ $key ][ $code ] ?><?php endif; ?></textarea>
                                                          
                                                        <?php
                                                        break; 
                                                        case 'wp_editor':
                                                            $value = isset( $data[ $key ] ) && isset( $data[ $key ][ $code ] )?$data[ $key ][ $code ]:'';
                                                            $id = self::$plugin_name.'_'.$id.'_'.$key;
                                                            $name = '['.$id.']['.$key.']['.$code.']';
                                                            wp_editor($value,$id,array('textarea_name'=>$name));
                                                        break; 
                                                        }?>
                                                    
                                                </li>
                                        <?php endforeach; ?>
                                </ul>
                        </div>
                <?php endif; ?>
		
	<?php
	}

	/**
	 * Echo text_before/text_after html
	 *
	 * @since 1.0.0
	 *
	 * @param string $text
	 */
	public static function get_text_after_before( $text,$before=TRUE ) {
		if ( ! isset( $text ) || ! $text ) {
			return false;
		}
                $style = $before?'before':'after';
		?>
		<span class="<?php echo self::$plugin_name ?>_text_<?php echo $style?>"><?php echo esc_attr( $text ) ?></span>
	<?php
	}

	/**
	 * Echo text html
	 *
	 * @since 1.0.0
	 *
	 * @param string $text
	 * @param string $type metabox type
	 */
	protected function get_text( $text, $type ) {
		if ( ! isset( $text ) || ! $text ) {
			return false;
		}
		?>
		<?php echo esc_attr( $text ) ?>
	<?php
	}

	/**
	 * Resize image
	 *
	 * @since 1.0.0
	 *
	 * @param string $img_url
	 * @param int $width
	 * @param int $height
	 * @param bool $crop
         * @param bool $cached
	 *
	 * @return array
	 */

	public static function ptb_resize($src_url, $width, $height, $crop = false, $cached = true){
            if ( empty( $src_url ) ) {
                return FALSE;
            }
            $width = intval($width);
            $height = intval($height);
            $src_url = esc_url($src_url);
            if ( $width<=0 || $height<=0) {
                return $src_url;
            }

            $src_info = pathinfo($src_url);
            $upload_info = wp_upload_dir();

            $upload_dir = $upload_info['basedir'];
            $upload_url = $upload_info['baseurl'];
            $thumb_name = $src_info['filename'].'_'.$width.'X'.$height.'.'.$src_info['extension'];

            if ( FALSE === strpos( $src_url, home_url() ) ){
                $source_path = $upload_info['path'].'/'.$src_info['basename'];
                $thumb_path = $upload_info['path'].'/'.$thumb_name;
                $thumb_url = $upload_info['url'].'/'.$thumb_name;
                if (!file_exists($source_path) && !copy($src_url, $source_path)) {
                    return FALSE;
                }

            }else{
                // define path of image
                $rel_path = str_replace( $upload_url, '', $src_url );
                $source_path = $upload_dir . $rel_path;
                $source_path_info = pathinfo($source_path);
                $thumb_path = $source_path_info['dirname'].'/'.$thumb_name;

                $thumb_rel_path = str_replace( $upload_dir, '', $thumb_path);
                $thumb_url = $upload_url . $thumb_rel_path;
            }

            if($cached && file_exists($thumb_path)){
                return $thumb_url;
            }

            $editor = wp_get_image_editor( $source_path );
            if($editor && !is_wp_error($editor)){
                    $editor->resize( $width, $height, $crop );
                    $new_image_info = $editor->save( $thumb_path );
            }
            else{
                    return $src_url;
            }

            if(empty($new_image_info)){
                return FALSE;
            }

            return $thumb_url;
	}
        
        
        /**
	 * Show link to post template params
	 *
	 * @since 1.0.5
	 *
	 * @param string $name
	 * @param string $type
	 * @param array $data
	 *
	 */
        public static function link_to_post($name,$type,$data){
        ?>
            <?php if($type==PTB_Post_Type_Template::ARCHIVE):?>
                <?php $key = $name.'_link';
                      $links = array('1'=>__('Link to post permalink','ptb'),'lightbox'=>__('Yes','ptb'),'new_window'=>__('Open in New Window'),"0"=>__('No','ptb'));                                  
                ?>
                <div class="<?php echo self::$plugin_name ?>_back_active_module_row">
                    <div class="<?php echo self::$plugin_name ?>_back_active_module_label">
                        <label><?php _e( 'Open in Lightbox', 'ptb') ?></label>
                    </div>
                    <div class="<?php echo self::$plugin_name ?>_back_active_module_input">
                        <?php foreach($links as $l=>$n):?>
                            <input type="radio" id="<?php echo self::$plugin_name ?>_<?php echo $name?>_radio_<?php echo $l?>"
                                   name="[<?php echo $name ?>][<?php echo $key?>]" value="<?php echo $l?>"
                                   <?php if ((! isset( $data[$key] ) && $l=='1') || ( isset( $data[$key] ) && $data[$key]=="$l")): ?>checked="checked"<?php endif; ?>/>
                            <label for="<?php echo self::$plugin_name ?>_<?php echo $name?>_radio_<?php echo $l?>"><?php echo $n ?></label>
                        <?php endforeach;?>
                    </div>
            </div>
            <?php endif;?>
        <?php
        }
        
}