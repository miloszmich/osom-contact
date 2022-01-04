<?php
/**
 * Osom contact
 *
 * @package       OSOMCONTACT
 * @author        Miłosz Michałkiewicz
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   OSOM contact
 * Plugin URI:    https://miloszmich.com
 * Description:   OSOM contact plugin
 * Version:       1.0.0
 * Author:        Miłosz Michałkiewicz
 * Author URI:    https://miloszmich.com
 * Text Domain:   osom-contact
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'OSOMCONTACT_NAME',			'Osom contact' );

// Plugin version
define( 'OSOMCONTACT_VERSION',		'1.0.0' );

// Plugin Root File
define( 'OSOMCONTACT_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'OSOMCONTACT_PLUGIN_BASE',	plugin_basename( OSOMCONTACT_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'OSOMCONTACT_PLUGIN_DIR',	plugin_dir_path( OSOMCONTACT_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'OSOMCONTACT_PLUGIN_URL',	plugin_dir_url( OSOMCONTACT_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once OSOMCONTACT_PLUGIN_DIR . 'core/class-osom-contact.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Miłosz Michałkiewicz
 * @since   1.0.0
 * @return  object|Osom_Contact
 */
function OSOMCONTACT() {
	return Osom_Contact::instance();
}

OSOMCONTACT();
