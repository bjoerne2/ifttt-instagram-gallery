<?php
/**
 * IFTTT Instagram Gallery
 *
 * @package   Ifttt_Instagram_Gallery_Admin
 * @author    Björn Weinbrenner <info@bjoerne.com>
 * @license   GPLv3
 * @link      http://bjoerne.com
 * @copyright 2014 bjoerne.com
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package Ifttt_Instagram_Gallery_Admin
 * @author  Björn Weinbrenner <info@bjoerne.com>
 */
class Ifttt_Instagram_Gallery_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = Ifttt_Instagram_Gallery::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_options_setting' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'IFTTT Instagram Gallery', $this->plugin_slug ),
			__( 'IFTTT Instagram Gallery', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		$this->options = get_option( 'ifttt_instagram_gallery_options', array() );
		include_once( 'views/admin.php' );
	}

	/**
	 * Registers the settings.
	 *
	 * @since    1.0.0
	 */
	public function register_options_setting() {
		register_setting( 'ifttt_instagram_gallery_options_group', 'ifttt_instagram_gallery_options', array( $this, 'validate_options' ) );
	}

	/**
	 * Validates the options.
	 *
	 * @since    1.0.0
	 */
	public function validate_options( $options ) {
		$keep_max_images = $options['keep_max_images'];
		if ( '' != $keep_max_images ) {
			if ( ! is_int( $keep_max_images ) ) {
				if ( ctype_digit( $keep_max_images ) ) {
					$keep_max_images = intval( $keep_max_images );
				} else {
					$keep_max_images = -1;
				}
			}
			if ( $keep_max_images <= 0 ) {
				$error_msg = _x( "Invalid value for '%s'. Must be a positive integer.", 'Error message', $this->plugin_slug );
				add_settings_error( '', esc_attr( 'settings_updated' ), sprintf( $error_msg, __( 'Maximum numbers of images to keep', $this->plugin_slug ) ), 'error' );
				unset( $options['keep_max_images'] );
			} else {
				$options['keep_max_images'] = $keep_max_images;
				Ifttt_Instagram_Gallery::get_instance()->remove_old_images( $keep_max_images );
			}
		}
		return $options;
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}
}