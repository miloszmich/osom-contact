<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Osom_Contact_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		OSOMCONTACT
 * @subpackage	Classes/Osom_Contact_Run
 * @author		Miłosz Michałkiewicz
 * @since		1.0.0
 */
class Osom_Contact_Run{

	/**
	 * Our Osom_Contact_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}


	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_shortcode( 'osom_cf', array( $this, 'add_shortcode_callback' ) );
		add_action( 'rest_api_init', array( $this, 'add_rest_api_endpoints' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ), 20 );
		add_action( 'admin_menu', array($this,'add_admin_menu'));

		register_activation_hook( OSOMCONTACT_PLUGIN_FILE, array( $this, 'activation_hook_callback' ) );
		register_uninstall_hook( OSOMCONTACT_PLUGIN_FILE,  'uninstall_hook_callback' );
	
	}


	/*
	 * This function is called on activation of the plugin
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function activation_hook_callback(){
		
		global $wpdb;

		$table_name = $wpdb->prefix . 'osom_contact';
		$charset_collate = $wpdb->get_charset_collate();

    $create_table_query = "
            CREATE TABLE IF NOT EXISTS `{$table_name}` (
							`ID` int(11) NOT NULL AUTO_INCREMENT,
							`first_name` varchar(255) NOT NULL,
							`last_name` varchar(255) NOT NULL,
							`login` varchar(255) NOT NULL,
							`email` varchar(255) NOT NULL,
							`city` varchar(255) NOT NULL,
							`date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							PRIMARY KEY (`ID`)
					) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $create_table_query );
		
	}

	/*
	 * This function is called on deactivation of the plugin
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function uninstall_hook_callback(){

		global $wpdb;

		$table_name = $wpdb->prefix . 'osom_contact';
    $wpdb->query( "DROP TABLE IF EXISTS `{$table_name}`" );
		
	}


	/**
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	array	$attr		Additional attributes you have added within the shortcode tag.
	 * @param	string	$content	The content you added between an opening and closing shortcode tag.
	 *
	 * @return	string	The customized content by the shortcode.
	 */
	public function add_shortcode_callback( $attr = array(), $content = '' ) {

		ob_start();
		$form = include( OSOMCONTACT_PLUGIN_DIR . 'core/includes/forms/osom-contact-form.php');

		return ob_get_clean();

	}

	/**
	 * Add the REST API endpoints for this plugin
	 *
	 * Accessibility:
	 * https://domain.com/wp-json/osomcontact/v1/demo/4
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function add_rest_api_endpoints() {

		if( ! class_exists( 'WP_REST_Server' ) ){
			return;
		}

		register_rest_route( 'osomcontact/v1', '/message', array(
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'prepare_rest_api_demo_response' ),
				'permission_callback' => function( $request ) {
					return true; //Change to limit access
				},
				'args' => array(
					'id' => array(
						'validate_callback' => function($param, $request, $key) {
							return is_numeric( $param );
						}
					),
				),
			),
			array(
        'methods'	=> WP_REST_Server::CREATABLE,
        'callback' => array( $this, 'create_message'),
        'args' => array(
					'email' => array (
							'required' => true,
							'sanitize_callback' => 'sanitize_text_field'
					),
				),
				'permission_callback' => array( $this, 'create_permissions_check' ),
			),
		));
	}

	/**
	 * The callback for the demo REST API endpoint
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	object|WP_REST_Request $request Full data about the request.
	 *
	 * @return	object|WP_REST_Response
	 */
	public function prepare_rest_api_demo_response( $request ){
		$response = array(
			'success' => false,
			'msg' => '',
		);

		$id = $request->get_param( 'id' );

		if( is_numeric( $id ) ){
			$response['success'] = true;
			$response['msg'] = __( 'The response was successful. The number you added:', 'osom-contact' ) . ' ' . intval( $id );
			return new WP_REST_Response( $response, 200 );
		}

		$response['msg'] = __( 'The given id is not a number.', 'osom-contact' );
		return new WP_REST_Response( $response, 500 );
	}

	protected function prepare_item_for_database( $request ) {
    return array();
  }

	public function create_permissions_check( $request ) {
		$nonce =  $request->get_header('X-WP-Nonce');
		
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			$now = current_time( 'mysql', false );
			return new WP_Error(500, 'Invalid token: ' . $nonce, $now); 
		} else {
			return true;
		}
  }

	public function create_message( $request ) {

			$now = current_time( 'mysql', false );

			$response = array(
				'code' => 400
			);

			if (
				(isset($request['first_name']) && !empty($request['first_name'])) &&
				(isset($request['last_name']) && !empty($request['last_name'])) &&
				(isset($request['login']) && !empty($request['login'])) &&
				(isset($request['email']) && !empty($request['email'])) &&
				(isset($request['city']) && !empty($request['city']))
			) : 
			
				$response['code'] = 200;
				
				global $wpdb;
				$table = $wpdb->prefix . 'osom_contact';
				$format = false;

				$data = array(
					'first_name' => sanitize_text_field($request['first_name']),
					'last_name' => sanitize_text_field($request['last_name']),
					'login' => sanitize_text_field($request['login']), 
					'email' => sanitize_text_field($request['email']),
					'city' => sanitize_text_field($request['city']),
					'date_add' => $now,
				);

				$wpdb->insert($table,$data,$format);

				return new WP_REST_Response( $response, 200 );

			else :

				return new WP_REST_Response( $response, 400 );

			endif;
  }


	function add_admin_menu() {
		add_menu_page( 'OSOM kontakt', 'OSOM kontakt', 'publish_pages', 'osom-contact', array($this,'osom_contact_page'), 'dashicons-email', 25  );
		add_submenu_page('osom-contact', 'Pomoc', 'Pomoc', 'publish_pages', 'osom-contact-help', array($this,'osom_contact_help'));

	}

	function osom_contact_help($id){
	?>
<div class="wrap">
  <h2>Pomoc</h2>
  <hr>
  <p>Formularz kontaktowy można umieścić na dowolnej podstronie za pomocą shortcode:</p>
  <code>[osom_cf]</code>
  <br class="clear">
</div>
<?php

	}

	function osom_contact_page(){
		$table_dir = include( OSOMCONTACT_PLUGIN_DIR . 'core/includes/forms/osom-contact-list.php');
		
		$table = new OsomContactList_Table();
		$table->prepare_items();
		?>

<div class="wrap">
  <h2>Wiadomości</h2>
  <form method="post">
    <?php $table->display(); ?>
  </form>
</div>

<?php
		}

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_scripts_and_styles() {
		wp_enqueue_style( 'osom-contact-styles', OSOMCONTACT_PLUGIN_URL . 'core/includes/assets/css/osom-contact.css', array(), OSOMCONTACT_VERSION, 'all' );

		wp_enqueue_script( 'osom-contact-scripts', OSOMCONTACT_PLUGIN_URL . 'core/includes/assets/js/osom-contact.js', array(), OSOMCONTACT_VERSION, false );
		wp_localize_script( 'osom-contact-scripts', 'osomContact', array(
			'plugin_name'   	=> __( OSOMCONTACT_NAME, 'osom-contact' ),
			'root'  => esc_url_raw( rest_url('osomcontact/v1/message') ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		));
	}

}