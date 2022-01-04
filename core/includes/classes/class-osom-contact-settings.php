<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Osom_Contact_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		OSOMCONTACT
 * @subpackage	Classes/Osom_Contact_Settings
 * @author		Miłosz Michałkiewicz
 * @since		1.0.0
 */
class Osom_Contact_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;

	/**
	 * Our Osom_Contact_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = OSOMCONTACT_NAME;
	}

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'OSOMCONTACT/settings/get_plugin_name', $this->plugin_name );
	}
}
