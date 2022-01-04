<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Osom_Contact' ) ) :

	/**
	 * Main Osom_Contact Class.
	 *
	 * @package		OSOMCONTACT
	 * @subpackage	Classes/Osom_Contact
	 * @since		1.0.0
	 * @author		Miłosz Michałkiewicz
	 */
	final class Osom_Contact {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Osom_Contact
		 */
		private static $instance;

		/**
		 * OSOMCONTACT helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Osom_Contact_Helpers
		 */
		public $helpers;

		/**
		 * OSOMCONTACT settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Osom_Contact_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'osom-contact' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'osom-contact' ), '1.0.0' );
		}

		/**
		 * Main Osom_Contact Instance.
		 *
		 * Insures that only one instance of Osom_Contact exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Osom_Contact	The one true Osom_Contact
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Osom_Contact ) ) {
				self::$instance					= new Osom_Contact;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Osom_Contact_Helpers();
				self::$instance->settings		= new Osom_Contact_Settings();

				//Fire the plugin logic
				new Osom_Contact_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'OSOMCONTACT/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once OSOMCONTACT_PLUGIN_DIR . 'core/includes/classes/class-osom-contact-helpers.php';
			require_once OSOMCONTACT_PLUGIN_DIR . 'core/includes/classes/class-osom-contact-settings.php';

			require_once OSOMCONTACT_PLUGIN_DIR . 'core/includes/classes/class-osom-contact-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'osom-contact', FALSE, dirname( plugin_basename( OSOMCONTACT_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.