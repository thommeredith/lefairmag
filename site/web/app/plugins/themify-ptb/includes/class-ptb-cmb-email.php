<?php
/**
 * Custom meta box class of type email
 *
 * @link       http://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 */

/**
 * Custom meta box class of type Text
 *
 *
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB_CMB_Email extends PTB_CMB_Base {

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

		$cmb_types[ $this->get_type() ] = array(
			'name' => __( 'Email', 'ptb' )
		);

		return $cmb_types;

	}

	/**
	 * @param string $id the id template
	 * @param array $languages
	 */
	public function action_template_type( $id, array $languages ) {
		?>
                <div class="ptb_cmb_input_row">
                    <label for="<?php print( $id ); ?>_default_value" class="ptb_cmb_input_label">
                        <?php _e( "Default Value", 'ptb' ); ?>
                    </label>
                    <div class="ptb_cmb_input">
                      <input type="email" id="<?php print( $id ); ?>_default_value"/>
                    </div>
                </div>

	<?php

	}

	/**
	 * Renders the meta boxes for themplates
	 *
	 * @since 1.0.0
	 *
	 * @param string $id the metabox id
	 * @param string $type the type of the page(Arhive or Single)
	 * @param array $args Array of custom meta types of plugin
	 * @param array $data saved data
	 * @param array $languages languages array
	 */
	public function action_them_themplate( $id, $type, $args, $data = array(), array $languages = array() ) {
	
            
        }

	/**
	 * Renders the meta boxes  in public
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Array of custom meta types of plugin
	 * @param array $data themplate data
	 * @param array or string $meta_data post data
	 * @param string $lang language code
	 * @param boolean $is_single single page
	 */
	public function action_public_themplate( array $args, array $data, $meta_data, $lang = false, $is_single = false ) {
            if(isset($meta_data[$args['key']]) && $meta_data[$args['key']]){
                echo antispambot($meta_data[$args['key']]);
            }

	}

	/**
	 * Renders the meta boxes on post edit dashboard
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post
	 * @param string $meta_key
	 * @param array $args
	 */
	public function render_post_type_meta( $post, $meta_key, $args ) {

            $value = get_post_meta( $post->ID, 'ptb_' . $meta_key, true );
            $name  = esc_attr( $meta_key );
            ?>
            <input type="email" id="<?php print( $name ); ?>" name="<?php print( $name ); ?>" value="<?php print( esc_attr( $post->post_status=='auto-draft' ? PTB_Utils::get_label( $args['defaultValue'] ) : $value ) ); ?>"/>
            <?php

	}


}