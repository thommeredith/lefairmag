<?php

class PTB_Form_PTT_Single extends PTB_Form_PTT_Them {


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name
	 * @param string $version
	 * @param PTB_Options $options the plugin options instance
	 * @param string themplate_id
	 *
	 */
	public function __construct( $plugin_name, $version, PTB_Options $options, $themplate_id ) {
		parent::__construct( $plugin_name, $version, $options, $themplate_id );

	}

	/**
	 * Single layout parametrs
	 *
	 * @since 1.0.0
	 */

	public function add_fields( $data = array() ) {


	}
}