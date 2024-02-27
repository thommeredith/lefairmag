<?php
/**
 * Custom meta box class of type Text
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
class PTB_CMB_Text extends PTB_CMB_Base {

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
			'name' => __( 'Text', 'ptb' )
		);

		return $cmb_types;

	}

	/**
	 * @param string $id the id template
	 * @param array $languages
	 */
	public function action_template_type( $id, array $languages ) {
                $lng_count = count($languages)>1;
		?>

                <div class="ptb_cmb_input_row">
                        <label for="<?php print( $id ); ?>_default_value" class="ptb_cmb_input_label">
                                <?php _e( "Default Value", 'ptb' ); ?>
                        </label>

                        <div class="ptb_cmb_input">
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
                                                        <input type="text" id="<?php print( $id ); ?>_default_value_<?php echo $code ?>"/>
                                                </li>
                                        <?php endforeach; ?>
                                </ul>
                        </div>
                </div>


		<div class="ptb_cmb_input_row">
			<label for="<?php print( $id ); ?>_repeatable" class="ptb_cmb_input_label">
				<?php _e( "Repeatable", 'ptb' ); ?>
			</label>
			<fieldset class="ptb_cmb_input">
				<label for="<?php print( $id ); ?>_repeatable_yes" title="Yes">
					<input type="radio" id="<?php print( $id ); ?>_repeatable_yes"
					       name="<?php print( $id ); ?>_repeatable" value="Yes"/>
					<span><?php _e( "Yes", 'ptb' ); ?></span>
				</label>&nbsp;&nbsp;
				<label for="<?php print( $id ); ?>_repeatable_no" title="No">
					<input type="radio" id="<?php print( $id ); ?>_repeatable_no"
					       name="<?php print( $id ); ?>_repeatable" value="No"  checked="checked"/>
					<span><?php _e( "No", 'ptb' ); ?></span>
				</label><br>
			</fieldset>
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
		$pluginame = $this->get_plugin_name();
		?>
		<?php if ( $args['repeatable'] ): ?>
			<div class="<?php echo $pluginame ?>_back_active_module_row">
				<div class="<?php echo $pluginame ?>_back_active_module_label">
                                    <label for="<?php echo $pluginame ?>_<?php echo $id ?>[display_one_line]"><?php _e( 'Display repeatable text as', 'ptb' ) ?></label>
                                </div>
				<div class="<?php echo $pluginame ?>_back_active_module_input <?php echo $pluginame ?>_back_text">

					<input type="radio" id="<?php echo $pluginame ?>_<?php echo $id ?>[display_one_line]"
					       name="[<?php echo $id ?>][display]" value="one_line"
					       <?php if ( ! isset($data['display']) ||  $data['display'] == 'one_line'): ?>checked="checked"<?php endif; ?> />
					<label
						for="<?php echo $pluginame ?>_<?php echo $id ?>[display_one_line]"><?php _e( 'One line', 'ptb' ) ?></label>
					<input type="radio" id="<?php echo $pluginame ?>_<?php echo $id ?>[display_bullet_list]"
					       name="[<?php echo $id ?>][display]" value="bullet_list"
					       <?php if (isset( $data['display'] ) && $data['display'] == 'bullet_list'): ?>checked="checked"<?php endif; ?> />
					<label
						for="<?php echo $pluginame ?>_<?php echo $id ?>[display_bullet_list]"><?php  _e( 'Bullet list', 'ptb' ) ?></label>
					<input type="radio" id="<?php echo $pluginame ?>_<?php echo $id ?>[display_numbered_list]"
					       name="[<?php echo $id ?>][display]" value="numbered_list"
					       <?php if (isset( $data['display'] ) && $data['display'] == 'numbered_list'): ?>checked="checked"<?php endif; ?> />
					<label
						for="<?php echo $pluginame ?>_<?php echo $id ?>[display_numbered_list]"><?php _e( 'Numbered list', 'ptb' ) ?></label>
				</div>
			</div>
                        <div class="ptb_back_active_module_row">	
                            <div class="<?php echo $pluginame ?>_back_active_module_label">
                                <label for="<?php echo $pluginame ?>_<?php echo $id?>_text_seperator"><?php _e( 'Seperator', 'ptb' ) ?></label>
                            </div>
                            <div class="<?php echo $pluginame ?>_back_active_module_input">
                                <input id="<?php echo $pluginame ?>_<?php echo $id?>_text_seperator" value="<?php echo isset($data['seperator'])?$data['seperator']:''?>"  type="text" name="[<?php echo $id ?>][seperator]" class="<?php echo $pluginame?>_xsmall" />
                            </div>
                        </div>
		<?php endif; ?>
                <?php if ( !$args['repeatable'] ): ?>
                    <div class="<?php echo $pluginame ?>_back_active_module_row">
                           <div class="<?php echo $pluginame ?>_back_active_module_label">
                               <label for="<?php echo $pluginame ?>_<?php echo $id?>_output_tag"><?php _e( 'HTML Tag', 'ptb' ) ?></label>
                           </div>
                           <div class="<?php echo $pluginame ?>_back_active_module_input">
                               <div class="<?php echo $pluginame?>_custom_select">
                                    <select id="<?php echo $pluginame ?>_<?php echo $id?>_output_tag" name="[<?php echo $id ?>][tag]">
                                        <option value=""></option>
                                        <option value="span"<?php if (isset( $data['tag'] ) && $data['tag'] == 'span'): ?> selected="selected"<?php endif;?>>Span</option>
                                        <option value="p"<?php if (isset( $data['tag'] ) && $data['tag'] == 'p'): ?> selected="selected"<?php endif;?>>Paragraph</option>
                                        <option value="strong"<?php if (isset( $data['tag'] ) && $data['tag'] == 'strong'): ?> selected="selected"<?php endif;?>>Strong</option>
                                        <option value="i"<?php if (isset( $data['tag'] ) && $data['tag'] == 'i'): ?> selected="selected"<?php endif;?>>Italic</option>
                                        <option value="h1"<?php if (isset( $data['tag'] ) && $data['tag'] == 'h1'): ?> selected="selected"<?php endif;?>>h1</option>
                                        <option value="h2"<?php if (isset( $data['tag'] ) && $data['tag'] == 'h2'): ?> selected="selected"<?php endif;?>>h2</option>
                                        <option value="h3"<?php if (isset( $data['tag'] ) && $data['tag'] == 'h3'): ?> selected="selected"<?php endif;?>>h3</option>
                                        <option value="h4"<?php if (isset( $data['tag'] ) && $data['tag'] == 'h4'): ?> selected="selected"<?php endif;?>>h4</option>
                                        <option value="h5"<?php if (isset( $data['tag'] ) && $data['tag'] == 'h5'): ?> selected="selected"<?php endif;?>>h5</option>
                                        <option value="h6"<?php if (isset( $data['tag'] ) && $data['tag'] == 'h6'): ?> selected="selected"<?php endif;?>>h6</option>
                                    </select>
                               </div>
                           </div>
                    </div>
                <?php endif;?>

	<?php
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
              
                $meta_data = $meta_data[$args['key']];
                if ( ! isset( $data['display'] ) ) {
			$data['display'] = 'one_line';
		}
                if ( ! isset( $data['seperator'] ) || !$data['seperator'] ) {
			$data['seperator'] = ', ';
		}
                $data['seperator']  = trim($data['seperator']).' ';
		if ( $args['repeatable'] ) {
			if ( ! is_array( $meta_data ) ) {
				$meta_data = array( $meta_data );

			}
                        $seperator = $data['display']=='one_line'?$data['seperator']:FALSE;
			$this->get_repeateable_text( $data['display'], $meta_data,$seperator);
                        
		} else {
                        if(is_array($meta_data)){
                            if( $data['display']=='one_line'){
                                $text = implode($data['seperator'], $meta_data );
                            }
                        }
                        else{
                            $text = $meta_data;
                        }
                        
			?>  
                            <?php if(isset($data['tag']) && $data['tag']):?>
                                <<?php echo $data['tag']?>><?php $this->get_text( $text, 'text' );?></<?php echo $data['tag']?>>
                            <?php else:?>
                                <?php $this->get_text( $text, 'text' );?>
                            <?php endif;?>
                        <?php
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

		// START: this is a temporary code for migration to new version
		// TODO: remove after migration
		if ( empty( $value ) ) {
			$value = get_post_meta( $post->ID, $meta_key, true );
			delete_post_meta( $post->ID, $meta_key, $value );
			update_post_meta( $post->ID, 'ptb_' . $meta_key, $value );
		}
		// END: this is a temporary code for migration to new version

		if ( $args['repeatable'] ) {

			$name = esc_attr( sprintf( '%s[]', $meta_key ) );

			?>

			<fieldset class="ptb_cmb_input">
				<ul id="<?php print( esc_attr( $meta_key ) ); ?>_options_wrapper" class="ptb_cmb_options_wrapper">
					<?php if ( empty( $value ) ) {
						?>
						<li class="<?php print( esc_attr( $meta_key ) ); ?>_option_wrapper ptb_cmb_option">
							<span class="ti-split-v ptb_cmb_option_sort"></span>
							<input name="<?php print( $name ); ?>" type="text"
							       value="<?php print( esc_attr( PTB_Utils::get_label( $args['defaultValue'] ) ) ); ?>"/>
							<span class="<?php print( esc_attr( $meta_key ) ); ?>_remove remove ti-close"></span>
						</li>
					<?php } else {
						$value = ! is_array( $value ) ? array( $value ) : $value;
						foreach ( $value as $text ) {
							?>
							<li class="<?php print( esc_attr( $meta_key ) ); ?>_option_wrapper ptb_cmb_option">
								<span class="ti-split-v ptb_cmb_option_sort"></span>
								<input name="<?php print( $name ); ?>" type="text"
								       value="<?php print( esc_attr( $text ) ); ?>"/>
								<span class="<?php print( esc_attr( $meta_key ) ); ?>_remove remove ti-close"></span>
							</li>
						<?php
						}
					} ?>
				</ul>
				<div id="<?php print( esc_attr( $meta_key ) ); ?>_add_new" class="ptb_cmb_option_add">
					<span class="ti-plus"></span>
					<?php _e('Add new','ptb')?>
				</div>
			</fieldset>

		<?php

		} else {

			$name  = esc_attr( $meta_key );
			$value = is_array( $value ) ? implode( ' ', $value ) : $value;
			?>

			<input type="text" id="<?php print( $name ); ?>"
			       name="<?php print( $name ); ?>"
			       value="<?php print( esc_attr( empty( $value ) ? PTB_Utils::get_label( $args['defaultValue'] ) : $value ) ); ?>"/>

		<?php

		}

	}


}