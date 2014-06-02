<?php
/**
 * IFTTT Instagram Gallery
 *
 * @package   Ifttt_Instagram_Gallery
 * @author    Björn Weinbrenner <info@bjoerne.com>
 * @license   GPLv3
 * @link      http://bjoerne.com
 * @copyright 2014 bjoerne.com
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @package Ifttt_Instagram_Gallery
 * @author  Björn Weinbrenner <info@bjoerne.com>
 */
class Ifttt_Instagram_Gallery {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $plugin_slug = 'ifttt-instagram-gallery';

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 *
	 * @var     object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since   1.0.0
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'ifttt_wordpress_bridge', array( $this, 'load_instagram_image' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since   1.0.0
	 *
	 * @return  Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since   1.0.0
	 *
	 * @return  object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Loads an instagram images based on the received data from IFTTT.
	 *
	 * @since   1.0.0
	 */
	public function load_instagram_image( $content_struct ) {
		// Ingredients: Caption, Url, SourceUrl, CreatedAt, EmbedCode
		$title          = $content_struct['title'];
		$description    = $content_struct['description'];
		$description_decoded = json_decode( $description, true ); 
		$instagram_url = $description_decoded['Url'];
		$image_url     = $description_decoded['SourceUrl'];
		$filename      = $this->get_filename( $image_url );
		$bits = $this->get_remote_file_content( $image_url );
		$this->upload_image( $filename, $title, $bits, $instagram_url );
		$this->remove_old_images();
	}

	private function get_filename( $image_url ) {
		preg_match( '/[^\\/]*$/', $image_url, $matches );
		return $matches[0];
	}

	private function get_remote_file_content( $image_url ) {
		$response = wp_remote_get( $image_url );
		return $response['body'];
	}

	private function upload_image( $name, $content, $bits, $instagram_url ) {
		// Copied and modified from /wp-includes/class-wp-xmlrpc-server.php
		$upload     = wp_upload_bits( $name, null, $bits );
		$attachment = array(
			'post_title' => $name,
			'post_content' => $content,
			'post_type' => 'attachment',
			'post_parent' => 0,
			'post_mime_type' => 'image/jpeg',
			'guid' => $upload[ 'url' ],
		);
		// Save the data
		$id = wp_insert_attachment( $attachment, $upload[ 'file' ] );
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
		add_post_meta( $id, '_instagram', $instagram_url );
	}

	private function remove_old_images() {
		$args = array(
			'meta_key' => '_instagram',
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
		);
		$query = new WP_Query( $args );
		if ( $query->found_posts <= 12 ) {
			return;
		}
		for ( $i = 12; $i < $query->found_posts; $i++ ) {
			$post = $query->posts[$i];
			wp_delete_post( $post->ID );
		}
		wp_reset_postdata();
	}
}

