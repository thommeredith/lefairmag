<?php

class PTB_Form_PTT_Them {

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
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      template type
	 */
	protected $type;

	protected $settings_section;

	protected $post_taxonomies;


	/**
	 * The options management class of the the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PTB_Options $options Manipulates with plugin options
	 */
	protected $options;

	public static $key = 'ptt';

	protected $themplate_id = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name
	 * @param string $version
	 * @param PTB_Options $options the plugin options instance
	 *
	 */
	public function __construct( $plugin_name, $version, PTB_Options $options, $themplate_id ) {

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->themplate_id = $themplate_id;
		$this->options      = $options;

	}

	/**
	 * Add settings section for themplage
	 *
	 * @since    1.0.0
	 *
	 * @param string $type
	 *
	 */
	public function add_settings_section( $type ) {
		$this->type             = $type;
		$this->settings_section = $this->plugin_name . '-ptt-' . $type;
		add_settings_section(
			$this->settings_section,
			'',
			array( $this, 'main_section_cb' ),
			$this->settings_section
		);
                require_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/ptb-admin-display-edit-ptt-them.php';
	}

	public function main_section_cb() {

		$value                 = $this->get_edit_value( $this->type, array() );
		$ptt                   = $this->get_ptt();
		$languages             = PTB_Utils::get_all_languages();
		$layout                = isset( $ptt[ $this->type ]['layout'] ) ? $ptt[ $this->type ]['layout'] : false;
		$cmb_options           = $this->options->get_cpt_cmb_options( $ptt['post_type'] );//post type metaboxes
		$post_support          = $this->options->get_cpt_cmb_support( $ptt['post_type'] );//post type support
		$this->post_taxonomies = $this->options->get_cpt_cmb_taxonomies( $ptt['post_type'] );//post type taxonomies
		
                $unset                 = array_search( 'page-attributes', $post_support );
		if ( isset( $post_support[ $unset ] ) ) {
			unset( $post_support[ $unset ] );
		}

		$replace_name = array(
			'editor'    => __( 'Content', 'ptb' ),
			'thumbnail' => __( 'Featured Image', 'ptb' )
		);
		if ( ! empty( $post_support ) ) {
			foreach ( $post_support as $support ) {
				$name                    = isset( $replace_name[ $support ] ) ? $replace_name[ $support ] : ucfirst($support);
				$cmb_options[ $support ] = array( 'type' => $support, 'name' => $name );
			}
		}
               
		if ( ! empty( $this->post_taxonomies ) ) {
			$tag = array_search( 'post_tag', $this->post_taxonomies );
			if ($tag!==false) {
				$post_support[]          = 'post_tag';
				$cmb_options['post_tag'] = array( 'type' => 'post_tag', 'name' => __( 'Tags', 'ptb' ) );
				unset( $this->post_taxonomies[ $tag ] );
                        }	
                    
                        if ( ! empty( $this->post_taxonomies ) ) {
                            $category = array_search( 'category', $this->post_taxonomies );
                            if ($category!==false) {
                                    $post_support[]          = 'category';
                                    $cmb_options['category'] = array(
                                            'type' => 'category',
                                            'name' => __( 'Categories', 'ptb' )
                                    );
                                    unset( $this->post_taxonomies[ $category ] );
                            } 
                            if ( ! empty( $this->post_taxonomies ) ) {
                                    $post_support[]            = 'taxonomies';
                                    $cmb_options['taxonomies'] = array(
                                            'type' => 'taxonomies',
                                            'name' => __( 'Taxonomies', 'ptb' )
                                    );
                            }
					
                        }
			
		}
      
		$post_support[]              = 'custom_text';
		$post_support[]              = 'date';
		$post_support[]              = 'custom_image';
		$cmb_options['custom_text']  = array('type' => 'custom_text', 'name' => __( 'Static Text', 'ptb' ) );
		$cmb_options['date']         = array('type' => 'date', 'name' => __( 'Date', 'ptb' ) );
		$cmb_options['custom_image'] = array('type' => 'custom_image','name' => __( 'Static Image', 'ptb' ));
                if($this->type!='single'){
                    $post_support[]              = 'permalink';
                    $cmb_options['permalink']    = array('type' => 'permalink','name' => __( 'Permalink', 'ptb' ));
                }
                $cmb_options = apply_filters('ptb_template_modules',$cmb_options,$this->type);
                $this->add_fields( $ptt[ $this->type ] );
                $method = 'ptb_'.$this->type.'_template';
		?>  <input type="hidden" value="<?php echo $this->type ?>" name="<?php echo $this->plugin_name ?>_type"/>
		<input type="hidden" value="<?php echo $this->themplate_id ?>"
		       name="<?php echo $this->plugin_name ?>-<?php echo self::$key ?>"/>
		<input type="hidden" value="" name="<?php echo $this->plugin_name ?>_layout"
		       id="<?php echo $this->plugin_name ?>_layout"/>
		<div class="<?php echo $this->plugin_name ?>_back_builder">
			<?php //Metabox Buttons ?>
			<div class="<?php echo $this->plugin_name ?>_back_module_panel">
				<?php foreach ( $cmb_options as $meta_key => $args ): ?>
					<?php
					$name     = esc_html( PTB_Utils::get_label( $args['name'] ) );
					$type     = esc_attr( $args['type'] );
                                      
					$meta_key = esc_attr( $meta_key );
                                        $metabox = in_array( $type, $post_support );
                                        $id = !$metabox?$meta_key:$type;
					?>
					<div data-type="<?php echo $type ?>"
					     id="<?php echo $this->plugin_name ?>_cmb_<?php echo $meta_key ?>"
					     class="<?php echo $this->plugin_name ?>_back_module">
						<strong class="<?php echo $this->plugin_name ?>_module_name"><?php echo $name ?></strong>

						<div class="<?php echo $this->plugin_name ?>_active_module">
							<div class="<?php echo $this->plugin_name ?>_back_module_top">
								<div class="<?php echo $this->plugin_name ?>_left">
									<span
										class="<?php echo $this->plugin_name ?>_back_active_module_title"><?php echo $name ?></span>
								</div>
								<div class="<?php echo $this->plugin_name ?>_right">
									<a href="#"
									   class="<?php echo $this->plugin_name ?>_module_btn <?php echo $this->plugin_name ?>_toggle_module"></a>
									<a href="#"
									   class="<?php echo $this->plugin_name ?>_module_btn <?php echo $this->plugin_name ?>_delete_module"></a>
								</div>
							</div>
							<div data-type="<?php echo $type ?>"
							     class="<?php echo $this->plugin_name ?>_back_active_module_content">
                                                                <?php do_action('before_template_row',$id,array(), $this->type,$languages);?>
                                                                <?php if(has_action($method)):?>
                                                                        <?php do_action($method,$type, $id, $args, array(), $languages);?>
                                                                <?php else:?>
                                                                    <?php if (!$metabox): ?>
                                                                            <?php do_action( 'ptb_template_' . $type, $id, $this->type, $args, array(), $languages ) ?>
                                                                    <?php else: ?>
                                                                            <?php $this->get_main_fields( $id, $name, array(), $languages ) ?>
                                                                    <?php endif; ?>
                                                                <?php endif;?>
                                                                <?php  if($type!='custom_text' && $type!='editor'){
                                                                        PTB_CMB_Base::module_multi_text( $id, array(), $languages,'text_before',__('Text Before','ptb') );
                                                                        PTB_CMB_Base::module_multi_text( $id, array(), $languages,'text_after',__('Text After','ptb') );
                                                                      }
                                                                ?>
                                                                <div class="ptb_back_active_module_row">	
                                                                    <div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                                                        <label for="<?php echo $this->plugin_name ?>_<?php echo $id ?>[display_inline]"><?php _e( 'Display Inline', 'ptb' ) ?></label>
                                                                    </div>
                                                                    <div class="<?php echo $this->plugin_name ?>_back_active_module_input">
                                                                        <label>
                                                                            <input id="<?php echo $this->plugin_name ?>_<?php echo $id ?>[display_inline]" type="checkbox" name="[<?php echo $id ?>][display_inline]" />
                                                                            <?php  _e('Display this module inline (float left)', 'ptb');?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                               <?php do_action('after_template_row',$id,array(), $this->type,$languages);?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php //Dropping container ?>
			<div class="<?php echo $this->plugin_name ?>_back_row_panel"
			     id="<?php echo $this->plugin_name ?>_row_wrapper">
				<?php if ( ! empty( $layout ) ): ?>
					<?php foreach ( $layout as $row_key => $_row ): ?>
						<?php $grid_keys = array_keys( $_row );
						$array_gid_keys  = array();
						foreach ( $grid_keys as $keys ) {
							$tmp_keys         = explode( '-', $keys );
							$array_gid_keys[] = $tmp_keys[0] . '-' . $tmp_keys[1];
						}
						$grid_keys = implode( '-', $array_gid_keys );
						?>
						<div
							class="<?php echo $this->plugin_name ?>_back_row<?php if ( $row_key == 0 ): ?> <?php echo $this->plugin_name ?>_first_row<?php endif; ?>">
							<div class="<?php echo $this->plugin_name ?>_back_row_top">
								<div class="<?php echo $this->plugin_name ?>_left">
									<div class="<?php echo $this->plugin_name ?>_grid_menu">
										<a class="<?php echo $this->plugin_name ?>_row_btn <?php echo $this->plugin_name ?>_grid_options"></a>

										<div class="<?php echo $this->plugin_name ?>_grid_list_wrapper">
											<ul class="<?php echo $this->plugin_name ?>_grid_list clearfix">
												<li>
													<ul>
														<li <?php if ($grid_keys == '1-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_1_1"
															   data-grid=["1-1"]></a></li>
														<li <?php if ($grid_keys == '4-2-4-2'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_2_4_2"
															   data-grid=["4-2","4-2"]></a></li>
														<li <?php if ($grid_keys == '3-1-3-1-3-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_3_1_3_1_3_1"
															   data-grid=["3-1","3-1","3-1"]></a></li>
														<li <?php if ($grid_keys == '4-1-4-1-4-1-4-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_1_4_1_4_1"
															   data-grid=["4-1","4-1","4-1","4-1"]></a></li>
														<li <?php if ($grid_keys == '5-1-5-1-5-1-5-1-5-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_5_1_5_1_5_1_5_1_5_1"
															   data-grid=["5-1","5-1","5-1","5-1","5-1"]></a></li>
														<li <?php if ($grid_keys == '6-1-6-1-6-1-6-1-6-1-6-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_6_1_6_1_6_1_6_1_6_1_6_1"
															   data-grid=["6-1","6-1","6-1","6-1","6-1","6-1"]></a></li>
													</ul>
												</li>
												<li>
													<ul>
														<li <?php if ($grid_keys == '4-1-4-3'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_3"
															   data-grid=["4-1","4-3"]></a></li>
														<li <?php if ($grid_keys == '4-1-4-1-4-2'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_1_4_2"
															   data-grid=["4-1","4-1","4-2"]></a></li>
														<li <?php if ($grid_keys == '4-1-4-2-4-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_2_4_1"
															   data-grid=["4-1","4-2","4-1"]></a></li>
														<li <?php if ($grid_keys == '4-2-4-1-4-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_2_4_1_4_1"
															   data-grid=["4-2","4-1","4-1"]></a></li>
														<li <?php if ($grid_keys == '4-3-4-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_3_4_1"
															   data-grid=["4-3","4-1"]></a></li>
													</ul>
												</li>
												<li>
													<ul>
														<li <?php if ($grid_keys == '3-2-3-1'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_3_2_3_1"
															   data-grid=["3-2","3-1"]></a></li>
														<li <?php if ($grid_keys == '3-1-3-2'): ?>class="selected"<?php endif; ?>>
															<a href="#"
															   class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_3_1_3_2"
															   data-grid=["3-1","3-2"]></a></li>
													</ul>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="<?php echo $this->plugin_name ?>_right">
									<a href="#"
									   class="<?php echo $this->plugin_name ?>_row_btn <?php echo $this->plugin_name ?>_toggle_module"></a>
									<a href="#"
									   class="<?php echo $this->plugin_name ?>_row_btn <?php echo $this->plugin_name ?>_delete_module"></a>
								</div>
							</div>
							<div class="<?php echo $this->plugin_name ?>_back_row_content">
								<?php $count = 6 - count( $_row );  //6 is the maximum number of grids ?>
								<?php if ( $count > 0 ): ?>
									<?php for ( $i = 0; $i < $count; $i ++ ): ?>
										<?php $_row[] = array();//fill array for set maximum colums count?>
									<?php endfor; ?>
								<?php endif; ?>
								<?php $first = true; ?>
								<?php foreach ( $_row as $col_key => $col ): ?>
									<?php
									$grid_keys = false;
									if ( ! is_numeric( $col_key ) ) {
										$tmp_key   = explode( '-', $col_key );
										$grid_keys = $tmp_key[0] . '-' . $tmp_key[1];
									}
									?>
									<div
										class="<?php if ( $first && $grid_keys ): ?>first <?php $first = false; ?><?php endif; ?><?php echo $this->plugin_name ?>_back_col<?php if ( $grid_keys ): ?> <?php echo $this->plugin_name ?>_col<?php echo $grid_keys ?> <?php echo $this->plugin_name ?>_show_grid<?php endif; ?>"
										<?php if ($grid_keys): ?>data-grid="<?php echo $grid_keys ?>"<?php endif; ?>>
										<div class="<?php echo $this->plugin_name ?>_module_holder">
											<div
												class="<?php echo $this->plugin_name ?>_empty_holder_text"><?php  _e( 'Drop module here', 'ptb' ) ?></div>
											<?php if ( ! empty( $col ) ): ?>
												<?php foreach ( $col as $module ): ?>
													<?php
													$meta_key = esc_attr( $module['key'] );
                                                                                                        if(!isset($cmb_options[ $meta_key ])){
                                                                                                            continue;
                                                                                                        }
													$args     = $cmb_options[ $meta_key ];
													$name     = esc_html( PTB_Utils::get_label( $args['name'] ) );
													if ( $module['type'] != 'custom_text' ) {
														foreach ( $module as &$values ) {
															if ( ! is_array( $values ) ) {
																$values = esc_attr( $values );
															} elseif ( ! empty( $values ) ) {
																foreach ( $values as &$value ) {
																	$value = esc_attr( $value );
																}
															}
														}
													}
													$type = $module['type'];
                                                                                                        $metabox = in_array( $type, $post_support );
                                                                                                        $id = !$metabox?$meta_key:$type;
                                                                                                     
													?>
													<div data-type="<?php echo $type ?>"
													     class="<?php echo $this->plugin_name ?>_back_module <?php echo $this->plugin_name; ?>_dragged">
														<strong
															class="<?php echo $this->plugin_name ?>_module_name"><?php echo $name ?></strong>

														<div class="<?php echo $this->plugin_name ?>_active_module">
															<div
																class="<?php echo $this->plugin_name ?>_back_module_top">
																<div class="<?php echo $this->plugin_name ?>_left">
																	<span
																		class="<?php echo $this->plugin_name ?>_back_active_module_title"><?php echo $name ?></span>
																</div>
																<div class="<?php echo $this->plugin_name ?>_right">
																	<a href="#"
																	   class="<?php echo $this->plugin_name ?>_module_btn <?php echo $this->plugin_name ?>_toggle_module"></a>
																	<a href="#"
																	   class="<?php echo $this->plugin_name ?>_module_btn <?php echo $this->plugin_name ?>_delete_module"></a>
																</div>
															</div>
															<div data-type="<?php echo $type ?>"
															     class="<?php echo $this->plugin_name ?>_back_active_module_content">                                                                                                                         
                                                                                                                                <?php do_action('before_template_row',$id,$module, $this->type,$languages);?>
                                                                                                                                 <?php if(has_action($method)):?>
                                                                                                                                    <?php do_action($method,$type,$id, $args, $module, $languages);?>
                                                                                                                                <?php else:?>
                                                                                                                                    <?php if ( !$metabox): ?>
                                                                                                                                            <?php do_action( 'ptb_template_' . $type, $id, $this->type, $args, $module, $languages ) ?>
                                                                                                                                    <?php else: ?>
                                                                                                                                            <?php $this->get_main_fields( $id, $name, $module, $languages ) ?>
                                                                                                                                    <?php endif; ?>  
                                                                                                                                <?php endif;?>
                                                                                                                                <?php  if($type!='custom_text' && $type!='editor'){
                                                                                                                                        PTB_CMB_Base::module_multi_text( $id, $module, $languages,'text_before',__('Text Before','ptb') );
                                                                                                                                        PTB_CMB_Base::module_multi_text( $id, $module, $languages,'text_after',__('Text After','ptb') );
                                                                                                                                      }
                                                                                                                                ?>
                                                                                                                                <div class="ptb_back_active_module_row">
                                                                                                                                   <div class="<?php echo $this->plugin_name?>_back_active_module_label">
                                                                                                                                        <label for="<?php echo $this->plugin_name ?>_<?php echo $id ?>[display_inline]"><?php _e( 'Display Inline', 'ptb' ) ?></label>
                                                                                                                                   </div>
                                                                                                                                   <div class="<?php echo $this->plugin_name ?>_back_active_module_input">
                                                                                                                                        <label>
                                                                                                                                            <input id="<?php echo $this->plugin_name ?>_<?php echo $id ?>[display_inline]" type="checkbox"
                                                                                                                                               name="[<?php echo $id ?>][display_inline]"
                                                                                                                                               <?php if (isset( $module['display_inline'] ) && $module['display_inline']): ?>checked="checked"<?php endif; ?>  />
                                                                                                                                                <?php _e('Display this module inline (float left)', 'ptb');?>
                                                                                                                                        </label>
                                                                                                                                    </div>
                                                                                                                                </div>  
                                                                                                                                <?php do_action('after_template_row',$id,$module, $this->type,$languages);?>
															</div>
														</div>
													</div>
                                                                                                      
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="<?php echo $this->plugin_name ?>_back_row <?php echo $this->plugin_name ?>_first_row <?php echo $this->plugin_name ?>_new-themplate">
						<div class="<?php echo $this->plugin_name ?>_back_row_top">
							<div class="<?php echo $this->plugin_name ?>_left">
								<div class="<?php echo $this->plugin_name ?>_grid_menu">
									<a class="<?php echo $this->plugin_name ?>_row_btn <?php echo $this->plugin_name ?>_grid_options"></a>

									<div class="<?php echo $this->plugin_name ?>_grid_list_wrapper">
										<ul class="<?php echo $this->plugin_name ?>_grid_list clearfix">
											<li>
												<ul>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_1_1"
													       data-grid=["1-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_2_4_2"
													       data-grid=["4-2","4-2"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_3_1_3_1_3_1"
													       data-grid=["3-1","3-1","3-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_1_4_1_4_1"
													       data-grid=["4-1","4-1","4-1","4-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_5_1_5_1_5_1_5_1_5_1"
													       data-grid=["5-1","5-1","5-1","5-1","5-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_6_1_6_1_6_1_6_1_6_1_6_1"
													       data-grid=["6-1","6-1","6-1","6-1","6-1","6-1"]></a></li>
												</ul>
											</li>
											<li>
												<ul>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_3"
													       data-grid=["4-1","4-3"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_1_4_2"
													       data-grid=["4-1","4-1","4-2"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_1_4_2_4_1"
													       data-grid=["4-1","4-2","4-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_2_4_1_4_1"
													       data-grid=["4-2","4-1","4-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_4_3_4_1"
													       data-grid=["4-3","4-1"]></a></li>
												</ul>
											</li>
											<li>
												<ul>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_3_2_3_1"
													       data-grid=["3-2","3-1"]></a></li>
													<li><a href="#"
													       class="<?php echo $this->plugin_name ?>_column_select <?php echo $this->plugin_name ?>_grid_3_1_3_2"
													       data-grid=["3-1","3-2"]></a></li>
												</ul>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="<?php echo $this->plugin_name ?>_right">
								<a href="#"
								   class="<?php echo $this->plugin_name ?>_row_btn <?php echo $this->plugin_name ?>_toggle_module"></a>
								<a href="#"
								   class="<?php echo $this->plugin_name ?>_row_btn <?php echo $this->plugin_name ?>_delete_module"></a>
							</div>
						</div>
						<div class="<?php echo $this->plugin_name ?>_back_row_content">
							<?php //6 is the maximum number of grids ?>
							<?php for ( $i = 0; $i < 6; $i ++ ): ?>
								<div class="<?php echo $this->plugin_name ?>_back_col">
									<div class="<?php echo $this->plugin_name ?>_module_holder">
                                                                            <div class="<?php echo $this->plugin_name ?>_empty_holder_text"><?php _e( 'Drop module here', 'ptb') ?></div>
                                                                            
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="<?php echo $this->plugin_name ?>_add_row <?php echo $this->plugin_name ?>_cmb_add_field"><span class="ti-plus circle"></span><?php _e( 'Add Row', 'ptb') ?></div>
			</div>
		</div>
	<?php
	}

	protected function get_field_name( $input_key ) {
		return sprintf( '%s_%s_%s', $this->plugin_name, self::$key, $input_key );
	}

	protected function get_field_id( $field_key ) {

		return sprintf( '%s_%s_%s', $this->plugin_name, self::$key, $field_key );
	}

	/**
	 * Save post themplate
	 *
	 * @since 1.0.0
	 *
	 * @param post array $data
	 */
	public function save_themplate( $data ) {
		$post_type = $this->get_ptt();

		if ( $post_type ) {
			$this->type = $data[ $this->plugin_name . '_type' ];
                        $data = apply_filters('ptb_themplate_save',$data);
                        if ( ! isset( $post_type[ $this->type ] ) ) {
                            $post_type[$this->type] = array();
			}
			$post_type[ $this->type ]['layout'] = array();
			if ( isset( $data[ $this->plugin_name . '_layout' ] ) ) {
				$layout                             = stripslashes_deep( $data[ $this->plugin_name . '_layout' ] );
				$post_type[ $this->type ]['layout'] = json_decode( $layout, true );
			}
			if ( $this->type == PTB_Post_Type_Template::ARCHIVE ) {
				$arhive_keys = array( 'layout_post', 'offset_post', 'orderby_post', 'order_post', 'pagination_post' );
				foreach ( $arhive_keys as $key ) {
					$fieldname = $this->get_field_name( $key );
					if ( isset( $data[ $fieldname ] ) ) {
						$post_type[ $this->type ][ $fieldname ] = sanitize_text_field( $data[ $fieldname ] );
					}
				}
			}
			$this->options->option_post_type_templates[ $this->themplate_id ] = $post_type;
			$this->options->update();
			die( json_encode( array(
				'status' => '1',
				'text'   => __( 'Template successfully updated', 'ptb')
			) ) );

		}
	}

	protected function get_ptt() {
		$ptt = null;
		if ( $this->options->has_post_type_template( $this->themplate_id ) ) {
			$ptt_options = $this->options->get_templates_options();
			$ptt         = $ptt_options[ $this->themplate_id ];
		}

		return $ptt;

	}

	private function get_edit_value( $key, $default ) {

		$ptt = $this->get_ptt();

		$value = ( isset( $ptt ) && array_key_exists( $key, $ptt ) ? $ptt[ $key ] : $default );

		return $value;

	}
        
        
        /**
	 * Render post fields
	 *
	 * @since 1.0.0
	 * @param string $type
         * @param string $name
         * @param array $data
         * @param array $languages
	 */

	private function get_main_fields( $type, $name, array $data = array(), array $languages = array() ) {
		switch ( $type ):
			case 'editor':
			case 'author':
			case 'comments':
				?>
				<input type="hidden" name="[<?php echo $type?>][<?php echo $type?>]"/>
				<?php break;?>
			<?php
			case 'title':
				?>
				<div class="<?php echo $this->plugin_name?>_back_active_module_row">
                                    <div class="<?php echo $this->plugin_name?>_back_active_module_label">
                                        <label for="<?php echo $this->plugin_name ?>_title_tag"><?php _e('HTML Tag', 'ptb')?></label>
                                    </div>
					<div class="<?php echo $this->plugin_name?>_back_active_module_input">
						<div class="<?php echo $this->plugin_name?>_custom_select">
							<select name="[<?php echo $type?>][title_tag]" id="<?php echo $this->plugin_name ?>_title_tag">
								<?php for ( $i = 1; $i <= 6; $i ++ ): ?>
									<option
										<?php if (isset( $data['title_tag'] ) && $data['title_tag'] == $i): ?>selected="selected"<?php endif; ?>
										value="<?php echo $i ?>">h<?php echo $i ?></option>
								<?php endfor;?>
							</select>
						</div>
					</div>
				</div>
                                <?php PTB_CMB_Base::link_to_post('title',$this->type,$data);?>
				<?php break;?>
			<?php case 'excerpt': ?>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
				<div
                                    class="<?php echo $this->plugin_name ?>_back_active_module_label"><label for="<?php echo $this->plugin_name ?>_excerpt_count"><?php _e( 'Word Count', 'ptb') ?></label></div>
				<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
					<input id="<?php echo $this->plugin_name ?>_excerpt_count" type="text" class="<?php echo $this->plugin_name ?>_xsmall"
					       name="[<?php echo $type ?>][excerpt_count]"
					       <?php if (isset( $data['excerpt_count'] )): ?>value="<?php echo $data['excerpt_count'] ?>"<?php endif; ?> />
					<?php  _e( 'Words', 'ptb') ?>
				</div>
			</div>
			<?php break; ?>
		<?php case 'custom_text': ?>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row <?php echo $this->plugin_name ?>_<?php echo $type ?>">
				<?php if(count($languages)>1):?>
                                <ul class="<?php echo $this->plugin_name ?>_language_tabs">
                                        <?php foreach ( $languages as $code => $lng ): ?>
                                                <li <?php if (isset( $lng['selected'] )): ?>class="<?php echo $this->plugin_name ?>_active_tab_lng"<?php endif; ?>>
                                                        <a class="<?php echo $this->plugin_name . '_lng_' . $code ?>"
                                                           title="<?php echo $lng['name'] ?>" href="#"></a></li>
                                        <?php endforeach; ?>
                                </ul>
                                <?php endif;?>
                                <ul class="<?php echo $this->plugin_name ?>_language_fields">
                                        <?php foreach ( $languages as $code => $lng ): ?>
                                                <li <?php if (isset( $lng['selected'] )): ?>class="<?php echo $this->plugin_name ?>_active_lng"<?php endif; ?>>
                                       <textarea class="<?php echo $this->plugin_name ?>_wp_editor"
                                                 name="[<?php echo $type ?>][text][<?php echo $code ?>]">
                                        <?php if ( isset( $data['text'][ $code ] ) ): ?> <?php echo $data['text'][ $code ] ?><?php endif; ?>
                                       </textarea>
                                                </li>
                                        <?php endforeach; ?>
                                </ul>
			</div>
			<?php break; ?>
		<?php case 'taxonomies': ?>
			<?php if ( ! empty( $this->post_taxonomies ) ): ?>
				
				<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
					<div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                            <label for="<?php echo $this->plugin_name ?>_select_taxonomies"><?php _e( 'Select Taxonomies', 'ptb') ?></label>
                                        </div>
					<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
						<div class="<?php echo $this->plugin_name ?>_custom_select">
							<select id="<?php echo $this->plugin_name ?>_select_taxonomies" name="[<?php echo $type ?>][taxonomies]">
								<?php foreach ( $this->post_taxonomies as $tax ): ?>
									<option
										<?php if (isset( $data['taxonomies'] ) && $data['taxonomies'] == $tax): ?>selected="selected"<?php endif; ?>
										value="<?php echo $tax ?>"><?php echo $tax ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
					<div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                            <label for="<?php echo $this->plugin_name ?>_seperator_taxonomies"><?php _e( 'Seperator', 'ptb') ?></label>
                                        </div>
					<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
						<input id="<?php echo $this->plugin_name ?>_seperator_taxonomies" type="text" class="<?php echo $this->plugin_name ?>_towidth"
						       name="[<?php echo $type ?>][seperator]"
						       <?php if (isset( $data['seperator'] )): ?>value="<?php echo $data['seperator'] ?>"<?php endif; ?> />
					</div>
				</div>
			<?php endif; ?>
			<?php break; ?>
		<?php case 'date': ?>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
                            <div class="<?php echo $this->plugin_name?>_back_active_module_label">
                                <label for="<?php echo $this->plugin_name ?>_date_format"><?php _e( 'Date Format', 'ptb') ?></label>
                            </div>
                            <div class="<?php echo $this->plugin_name ?>_back_active_module_input">
					<input id="<?php echo $this->plugin_name ?>_date_format" type="text"
					       class="<?php echo $this->plugin_name ?>_towidth" name="[<?php echo $type ?>][date_format]"
					       <?php if (isset( $data['date_format'] )): ?>value="<?php echo $data['date_format'] ?>"<?php endif; ?> />
					<?php   _e( '(e.g. M j,Y)','ptb') ?> <a
						href="http://php.net/manual/ru/function.date.php"
						target="_blank"><?php _e( 'More info', 'ptb') ?></a>
				</div>
			</div>
			<?php break; ?>
		<?php case 'post_tag':
                      case 'category':
				?>
				<div class="<?php echo $this->plugin_name?>_back_active_module_row">
					<div class="<?php echo $this->plugin_name?>_back_active_module_label">
						<label for="<?php echo $this->plugin_name?>_category_seperator"><?php _e('Seperator', 'ptb')?></label>
					</div>
					<div class="<?php echo $this->plugin_name?>_back_active_module_input">
						<input id="<?php echo $this->plugin_name?>_category_seperator" type="text" class="<?php echo $this->plugin_name?>_towidth"
						       name="[<?php echo $type?>][seperator]"
						       <?php if (isset( $data['seperator'] )): ?>value="<?php echo $data['seperator'] ?>"<?php endif;
						?> />
					</div>
				</div>
				<?php break;?>
			<?php case 'thumbnail': ?>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
				<div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                    <label for="<?php echo $this->plugin_name ?>_thumbnail_width"><?php  _e( 'Image Dimension', 'ptb') ?></label>
                                </div>
				<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
					<input id="<?php echo $this->plugin_name ?>_thumbnail_width" type="text" class="<?php echo $this->plugin_name ?>_xsmall"
					       name="[<?php echo $type ?>][width]"
					       <?php if (isset( $data['width'] )): ?>value="<?php echo $data['width'] ?>"<?php endif; ?> />
					<label><?php _e( 'Width', 'ptb') ?></label>
					<input type="text" class="<?php echo $this->plugin_name ?>_xsmall"
					       name="[<?php echo $type ?>][height]"
					       <?php if (isset( $data['height'] )): ?>value="<?php echo $data['height'] ?>"<?php endif; ?> />
					<label><?php _e( 'Height', 'ptb') ?>(px)</label>
				</div>
			</div>
                        <?php PTB_CMB_Base::link_to_post('thumbnail',$this->type,$data);?>
			<?php break; ?>
		<?php case 'custom_image': ?>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
				<div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                    <label for="<?php echo $this->plugin_name?>_custom_image_file"><?php _e( 'Image File', 'ptb') ?></label>
                                </div>
				<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
					<div class="<?php echo $this->plugin_name ?>_post_image_wrapper">
						<div class="<?php echo $this->plugin_name ?>_post_image_thumb_wrapper">
							<div
								class="<?php echo $this->plugin_name ?>_post_image_thumb" <?php if ( isset( $data['image'] ) ): ?> style="background-image: url(<?php echo $data['image'] ?>)"<?php endif; ?>></div>
						</div>
						<div class="<?php echo $this->plugin_name ?>_post_image_add_wrapper">
							<input id="<?php echo $this->plugin_name?>_custom_image_file" type="text" class="<?php echo $this->plugin_name ?>_towidth"
							       name="[<?php echo $type ?>][image]"
							       <?php if (isset( $data['image'] )): ?>value="<?php echo $data['image'] ?>"<?php endif; ?> />
							<a href="#" onclick="PTB.ImageUpload(this)"
							   class="<?php echo $this->plugin_name ?>_post_image_add">+<?php _e( 'Media Library', 'ptb') ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
				<div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                    <label for="<?php echo $this->plugin_name?>_custom_image_width"><?php _e( 'Image Dimension', 'ptb') ?></label>
                                </div>
				<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
					<input id="<?php echo $this->plugin_name ?>_custom_image_width" type="text"
					       class="<?php echo $this->plugin_name ?>_xsmall"
					       name="[<?php echo $type ?>][width]"
					       <?php if (isset( $data['width'] )): ?>value="<?php echo $data['width'] ?>"<?php endif; ?> />
					<label
						for="<?php echo $this->plugin_name ?>_width"><?php _e( 'Width', 'ptb') ?></label>
					<input id="<?php echo $this->plugin_name ?>_height" type="text"
					       class="<?php echo $this->plugin_name ?>_xsmall"
					       name="[<?php echo $type ?>][height]"
					       <?php if (isset( $data['height'] )): ?>value="<?php echo $data['height'] ?>"<?php endif; ?> />
					<label
						for="<?php echo $this->plugin_name ?>_height"><?php _e( 'Height', 'ptb') ?>
						(px)</label>
				</div>
			</div>
			<div class="<?php echo $this->plugin_name ?>_back_active_module_row">
				<div class="<?php echo $this->plugin_name ?>_back_active_module_label">
                                    <label for="<?php echo $this->plugin_name?>_custom_image_link"><?php _e( 'Image Link', 'ptb') ?></label>
                                </div>
				<div class="<?php echo $this->plugin_name ?>_back_active_module_input">
					<input id="<?php echo $this->plugin_name?>_custom_image_link" type="text" class="<?php echo $this->plugin_name ?>_towidth"
					       name="[<?php echo $type ?>][link]"
					       <?php if (isset( $data['link'] )): ?>value="<?php echo $data['link'] ?>"<?php endif; ?>/>
				</div>
			</div>
                    <?php break; ?>
                    <?php case 'permalink': ?>
                        <?php PTB_CMB_Base::module_multi_text( $type, $data, $languages,'text',__('Text','ptb') );?>
			<?php do_action( 'ptb_template_link_button',$type, $this->type, array(), $data, $languages )?>
                    <?php break; ?>
		<?php endswitch; ?>

	<?php
	}
        
        
         /**
	 * Frontend layout render
	 *
	 * @since 1.0.0
	 * @param array   $layout
         * @param array   $post_support
         * @param array   $cmb_options
         * @param array   $post_meta
         * @param string  $post_type 
         * @param boolean $is_single 
	 */
	public function display_public_themplate( array $layout, array $post_support, array $cmb_options, array $post_meta, $post_type, $is_single = false ) {
                $lang  = PTB_Utils::get_current_language_code();
		$count = count( $layout ) - 1;
                ob_start();
	
		?>
		<?php foreach ( $layout as $k => $row ): ?>
			<?php
			$class = '';
			if ( $k == 0 ) {
				$class .= 'first';
			} elseif ( $k == $count ) {
				$class .= 'last';
			}
			?>
			<div class="<?php if ( $class ): ?><?php echo $this->plugin_name . '_' . $class . '_row' ?> <?php endif; ?><?php echo $this->plugin_name ?>_row <?php echo $this->plugin_name ?>_<?php echo $post_type ?>_row">
				<?php if ( ! empty( $row ) ): ?>
					<?php
					$colums_count = count( $row ) - 1;
					$i            = 0;
					?>
					<?php foreach ( $row as $col_key => $col ): ?>
						<?php
						$tmp_key = explode( '-', $col_key );
						$key     = $tmp_key[0] . '-' . $tmp_key[1];
						?>
						<div
							class="<?php echo $this->plugin_name ?>_col <?php echo $this->plugin_name ?>_col<?php echo $key ?><?php if ( $i == 0 ): ?> <?php echo $this->plugin_name ?>_col_first<?php elseif ( $i == $colums_count ): ?> <?php echo $this->plugin_name ?>_col_last<?php endif; ?>">
							<?php if ( ! empty( $col ) ): ?>
								<?php foreach ( $col as $index=>$module ): ?>
									<?php
									if ( $module['type'] != 'custom_text' && $module['type']!='editor') {
										foreach ( $module as &$values ) {
											if ( ! is_array( $values ) ) {
												$values = esc_attr( $values );
											} elseif ( ! empty( $values ) ) {
												foreach ( $values as &$value ) {
													$value = esc_attr( $value );
												}
											}
										}
									} else {
										$meta_data = array();
									}
									$meta_key = $module['key'];
                                                                                                                               
                                                                        if(!isset($cmb_options[ $meta_key ])){
                                                                            continue;
                                                                        }
                                                                        $args     = $cmb_options[ $meta_key ];
									$type     = $module['type'];
									$args['key'] = $meta_key;
                                                                      
                                                                        ?>
                                                                        <?php if( in_array( $type, $post_support ) || (isset($args['can_be_empty'])) || (isset( $post_meta[ $this->plugin_name . '_' . $meta_key ] ) && ! empty( $post_meta[ $this->plugin_name . '_' . $meta_key ] ) )):?>
                                                                            <?php $fields = in_array( $type, $post_support );?>
                                                                            <div class="<?php echo $this->plugin_name?>_module <?php echo $this->plugin_name?>_<?php echo $type?><?php if(!$fields):?> <?php echo $this->plugin_name?>_<?php echo $meta_key?><?php endif;?><?php echo isset($module['display_inline']) && $module['display_inline']?' '.$this->plugin_name.'_module_inline':''?>">
                                                                                <?php if ( isset( $module['text_before'][ $lang ] ) ):?>
                                                                                     <?php PTB_CMB_Base::get_text_after_before( $module['text_before'][ $lang ],true );?>
                                                                                <?php endif;?>
                                                                                <?php if ($fields):?>
                                                                                     <?php $this->get_public_main_fields( $type, $args, $module, $post_meta, $lang, $is_single, $k.'_'.$col_key.'_'.$index );?>
                                                                                <?php else:?>
                                                                                    <?php          
                                                                                       $meta_value = isset($post_meta[ $this->plugin_name . '_' . $meta_key ]) 
                                                                                                     && !empty($post_meta[ $this->plugin_name . '_' . $meta_key ])?
                                                                                                    $post_meta[ $this->plugin_name . '_' . $meta_key ]:false;
																						
                                                                                       if($meta_value){
                                                                                            $meta_data = maybe_unserialize(current($meta_value));
                                                                                            if ($meta_data === false ) {
                                                                                                $meta_data = current($meta_value);
                                                                                            }
                                                                                            if(!is_array($meta_data) || !isset($meta_data[$meta_key])){
                                                                                                $post_meta[$meta_key] = $meta_data;
                                                                                                if(!is_array($meta_data)){
                                                                                                    $meta_data = array($meta_data);
                                                                                                }
                                                                                            }
                                                                                            $meta_data = array_merge($meta_data,$post_meta);
                                                                                        }
                                                                                        else{
                                                                                            $meta_data = $post_meta;
                                                                                        }
                                                                                        apply_filters( 'ptb_template_public' . $type, $args, $module, $meta_data, $lang, $is_single, $k.'_'.$col_key.'_'.$index );
                                                                                    ?>
                                                                                <?php endif;?>
                                                                                <?php if ( isset( $module['text_after'][ $lang ] ) ):?>
                                                                                     <?php PTB_CMB_Base::get_text_after_before( $module['text_after'][ $lang ],false );?>
                                                                                <?php endif;?>
                                                                            </div>
                                                                        <?php endif;?>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<?php $i ++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endforeach;
		$content = ob_get_contents();
		ob_end_clean();

		return $content; ?>

	<?php
	}
        
        
        /**
	 * Frontend post fields render
	 *
	 * @since 1.0.0
	 * @param string $type
         * @param array $args
         * @param array $data
         * @param array $meta_data
         * @param array $lang
         * @param boolean $is_single single page
         * @param string $index index in themplate
	 */
        
	private function get_public_main_fields( $type, array $args, array $data, array $meta_data, $lang = false, $is_single=false, $index=false ) {
          
		switch ( $type ):
                    case 'title':
                            ?>
                            <?php if ( isset( $meta_data['post_title'] ) ): ?>
                                <h<?php echo $data['title_tag'] ?> class="<?php echo $this->plugin_name ?>_post_title <?php echo $this->plugin_name ?>_entry_title" itemprop="name">
                                    <?php if ( isset( $data['title_link'] ) && $data['title_link'] ): echo '<a '.($data['title_link']=='lightbox'?'data-href="'.admin_url('admin-ajax.php?id='.get_the_ID().'&action=ptb_single_lightbox').'" class="'.$this->plugin_name.'_open_lightbox"':'').($data['title_link']=='new_window'?'target="_blank"':'').'href="' . $meta_data['post_url'] . '">'; endif; ?>
                                        <?php echo $meta_data['post_title'] ?>
                                    <?php if ( isset( $data['title_link'] ) && $data['title_link'] ): echo '</a>'; endif; ?>
                                </h<?php echo $data['title_tag'] ?>>
                            <?php endif;?>
                            <?php break;?>
                    <?php case 'excerpt': ?>
                            <?php $excerpt = $meta_data['post_excerpt']?>
                            <?php if ( $data['excerpt_count'] > 0 ): ?>
                                <?php $excerpt =  wp_trim_words($excerpt,$data['excerpt_count']); ?>
                            <?php endif; ?>
                            <?php echo !has_shortcode($excerpt,$this->plugin_name)?do_shortcode($excerpt):$excerpt; ?>
                    <?php break; ?>
                    <?php case 'author': ?>
                                    <span class="<?php echo $this->plugin_name ?>_post_author <?php echo $this->plugin_name ?>_post_meta">
                                        <span class="<?php echo $this->plugin_name ?>_author" itemprop="author" itemscope="" itemtype="http://schema.org/Person"><a  href="<?php echo get_the_author_link()?>" rel="author" itemprop="name"><?php echo get_the_author(); ?></a></span>
                                    </span>
                    <?php break; ?>
                    <?php case 'custom_text': ?>
                            <?php if ( $data['text'][ $lang ] ): ?>
                                    <div class="<?php echo $this->plugin_name ?>_custom_text">
                                        <?php echo !has_shortcode($data['text'][ $lang ],$this->plugin_name)?do_shortcode($data['text'][ $lang ]):$data['text'][ $lang ]; ?>
                                    </div>
                            <?php endif; ?>
                    <?php break; ?>
                    <?php case 'taxonomies': ?>
                            <div class="<?php echo $this->plugin_name ?>_taxonomies <?php echo $this->plugin_name ?>_taxonomies_<?php echo str_replace( '-', '_', $data['taxonomies'] ) ?>">
                                    <?php if ( ! empty( $meta_data['taxonomies'] ) ): ?>
                                            <?php $taxs = array(); ?>
                                            <?php foreach ( $meta_data['taxonomies'] as $tax ): ?>
                                                    <?php if (isset($tax->taxonomy) && $data['taxonomies'] == $tax->taxonomy ): ?>
                                                            <?php
                                                            $term_link             = get_term_link( $tax, $tax->taxonomy );
                                                            $taxs[ $tax->term_id ] = '<a href="' . $term_link . '">' . $tax->name . '</a>'; ?>
                                                    <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if ( ! empty( $taxs ) ): ?>
                                                    <?php if ( ! $data['seperator'] ) {
                                                            $data['seperator'] = ', ';
                                                    } ?>
                                                    <?php echo implode( $data['seperator'], $taxs ) ?>
                                            <?php endif; ?>
                                    <?php endif; ?>
                            </div>
                    <?php break; ?>
                    <?php case 'date': ?>
                                <time class="<?php echo $this->plugin_name ?>_post_date <?php echo $this->plugin_name ?>_post_meta" datetime="<?php echo date('Y-m-d', strtotime( $meta_data['post_date'] ) )?>" itemprop="datePublished">
                                    <?php if ( isset( $data['date_format'] ) && $data['date_format'] ): ?>
                                            <?php echo date( $data['date_format'], strtotime( $meta_data['post_date'] ) ) ?>
                                    <?php else: ?>
                                            <?php echo $meta_data['post_date'] ?>
                                    <?php endif; ?>
                                </time>
                    <?php break; ?>
                    <?php case 'post_tag':
                          case 'category':
                                    ?>
                                    <?php $key = $type == 'post_tag' ? 'tags_input' : 'post_category';?>
                                    <?php if ( ! empty( $meta_data[ $key ] ) ): ?>
                                        <span class="<?php echo $this->plugin_name ?>_post_category <?php echo $this->plugin_name ?>_post_meta">
                                            <?php if ( ! $data['seperator'] ) {
                                                   $data['seperator'] = ',';
                                           } ?>
                                           <?php if ( $key == 'tags_input' ):?>
                                               <?php the_tags('', $data['seperator'],'');?>
                                           <?php else:?>
                                               <?php the_category($data['seperator'] );?>
                                           <?php endif;?>
                                        </span>   
                                    <?php endif;?>
                    <?php break;?>
                    <?php case 'thumbnail': ?>
                            <?php if ( isset( $meta_data['_thumbnail_id'] ) && $meta_data['_thumbnail_id'] ): ?>
                                    <?php if ( has_post_thumbnail( get_the_ID() ) ):?>
                                        <?php
                                        $url    = wp_get_attachment_url( current( $meta_data['_thumbnail_id'] ) );
                                        $url = PTB_CMB_Base::ptb_resize($url,$data['width'],$data['height']);
                                        $title = isset( $meta_data['post_title'] ) ? $meta_data['post_title'] : '';

                                        ?>
                                        <figure class="<?php echo $this->plugin_name ?>_post_image clearfix">
                                            <?php if ( isset( $data['thumbnail_link'] ) && $data['thumbnail_link'] ): echo '<a '.($data['thumbnail_link']=='lightbox'?'data-href="'.admin_url('admin-ajax.php?id='.get_the_ID().'&action=ptb_single_lightbox').'" class="'.$this->plugin_name.'_open_lightbox"':'').($data['thumbnail_link']=='new_window'?'target="_blank"':'').' href="' . $meta_data['post_url'] . '">'; endif; ?>
                                            <img src="<?php echo $url ?>" alt="<?php echo $title ?>"
                                             title="<?php echo $title ?>"/>
                                            <?php if ( isset( $data['thumbnail_link'] ) && $data['thumbnail_link'] ): echo '</a>'; endif; ?>
                                        </figure>
                                    <?php endif; ?>
                            <?php endif; ?>
                    <?php break; ?>
                    <?php case 'custom_image': ?>
                            <?php if(isset($data['image']) && $data['image']):?>
                                <?php
                                $url = PTB_CMB_Base::ptb_resize($data['image'],$data['width'],$data['height']);
                                ?>
                                <figure class="<?php echo $this->plugin_name ?>_post_image clearfix">
                                    <?php if ( isset( $data['link'] ) && $data['link'] ): echo '</a>'; endif; ?>
                                        <img src="<?php echo $url?>" />
                                    <?php if ( isset( $data['link'] ) && $data['link'] ): echo '<a href="' . $data['link'] . '">'; endif; ?>
                                </figure>
                           <?php endif;?>
                    <?php break; ?>
                    <?php case 'editor': ?>
                            <?php PTB_Options::$rendder_content = true;?>
                            <div class="<?php echo $this->plugin_name ?>_entry_content" itemprop="articleBody">
                                    <?php the_content(); ?>
                            </div>
                    <?php break; ?>
                    <?php case 'comments': ?>
                            <div class="<?php echo $this->plugin_name ?>_comments">

                                    <?php
                                    //Gather comments for a specific page/post
                                    $comments = get_comments( array(
                                            'post_id' => get_the_ID(),
                                            'status'  => 'approve' //Change this to the type of comments to be displayed
                                    ) );
                                    ?>
                                    <ul class="commentlist">    
                                        <?php
                                        //Display the list of comments
                                        wp_list_comments( array(
                                                'per_page'          => 10, //Allow comment pagination
                                                'reverse_top_level' => false //Show the latest comments at the top of the list
                                        ), $comments );
                                        ?>
                                    </ul>
                                    <?php comment_form(); ?>
                            </div>
                    <?php break; ?>
                    <?php case 'permalink': ?>
                            <?php 
 
                            $class = $style = array();
                            if(isset($data['custom_color']) && $data['custom_color']){
                                $style[] = 'background-color:'.$data['custom_color'].' !important;';
                            }
                            elseif(isset($data['color'])){
                                 $class[] = $data['color'];
                            }
                            if(isset($data['icon']) && $data['icon']){
                                $class[] = 'fa';
                                $class[] = $data['icon'];
                            }
                            if(isset($data['size']) && $data['size']){
                                $class[] = $data['size'];
                            }
                            if(isset($data['link_link']) && $data['link_link']=='lightbox'){
                                $class[] = 'ptb_open_lightbox';
                                $meta_data['post_url'] = admin_url('admin-ajax.php?id='.get_the_ID().'&action=ptb_single_lightbox');
                            }
                            if(isset($data['styles']) && !empty($data['styles'])){
                                $class[] = implode(' ',$data['styles']);
                            }
                            if(isset($data['text_color']) && $data['text_color']){
                                $style[] = 'color:'.$data['text_color'].' !important;';
                            }
                            ?>
                            <div class="<?php echo $this->plugin_name ?>_permalink">
                               <a  <?php if(!empty($style)):?>style="<?php echo esc_attr(implode(' ',$style)) ?>"<?php endif;?> class="ptb_link_button <?php if(!empty($class)):?>shortcode <?php echo esc_attr(implode(' ',$class))?><?php endif;?>" <?php if(isset($data['link_link']) && $data['link_link']=='new_window'):?>target="_blank"<?php endif;?>
                            href="<?php echo $meta_data['post_url'] ?>"><?php echo isset($data['text'][$lang])?$data['text'][$lang]:'' ?></a>    
                            </div>
                    <?php break; ?>
		<?php endswitch; ?>

	<?php
	}
        
        
       
}