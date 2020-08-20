<?php
/**
 * Admin Interface Class
 *
 * @package bcmsr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin Interface Class
 *
 * Sets up Admin interface in WordPress
 */
class BCMSR_Admin_Interface {
	protected static $instance = null;
	protected $page_id         = null;

	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook wp_loaded
	 * @return  object of this class
	 */
	public static function get_instance() {
		null === self::$instance and self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Register menu pages
	 */
	public function register() {
		add_action( 'network_admin_menu', array( $this, 'add_menu' ) );
	}

	public function add_menu() {
		$page_id = add_menu_page(
			'Multisite Site Report',
			'Site Report',
			'manage_network_options',
			'bcmsr',
			array( $this, 'render_options_page' )
		);
		add_action( "load-$page_id", array( $this, 'parse_message' ) );
	}

	public function parse_message() {
		if ( ! isset( $_GET['msg'] ) ) {
			return;
		}

		$text = false;

		if ( 'updated' === $_GET['msg'] ) {
			$this->msg_text = 'Updated!';
		}

		if ( 'deleted' === $_GET['msg'] ) {
			$this->msg_text = 'Deleted!';
		}

		if ( $this->msg_text ) {
			add_action( 'admin_notices', array( $this, 'render_msg' ) );
		}
	}

	public function render_msg() {
		echo '<div class="' . esc_attr( $_GET['msg'] ) . '"><p>'
			. $this->msg_text . '</p></div>';
	}

	/*
	/**
	 * Display content of network options page
	 */
	public function render_options_page() {

		$redirect = urlencode( remove_query_arg( 'msg', $_SERVER['REQUEST_URI'] ) );
		$redirect = urlencode( $_SERVER['REQUEST_URI'] ); ?>

		<div class="wrap">
			<h1><?php echo esc_attr( $GLOBALS['title'] ); ?></h1>
			<p>Select a role or multiple roles to generate a list of all users with that role, along with the sites to which they are assigned. <strong>This plugin uses stupidtables.js for sorting; click on a column header to sort that column.</strong></p>

			<?php
			$site_list = new BCMSR_Site_List();
			$site_list->load_sites();
			echo wp_kses_post( $site_list->output() );
			?>
		</div>
		<?php
	}
}
