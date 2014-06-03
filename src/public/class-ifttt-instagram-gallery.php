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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
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
		add_post_meta( $id, '_ifttt_instagram', array( 'url' => $instagram_url ) );
	}

	/**
	 * Removes older images if 'keep_max_images' is configured.
	 *
	 * @since    1.0.0
	 */
	public function remove_old_images( $keep_max_images = null ) {
		if ( null === $keep_max_images ) {
			$options = get_option( 'ifttt_instagram_gallery_options', array() );
			$keep_max_images = @$options['keep_max_images'];
		}
		if ( ! $keep_max_images ) {
			return;
		}
		$args = array(
			'meta_key' => '_ifttt_instagram',
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
		);
		$query = new WP_Query( $args );
		if ( $query->found_posts <= $keep_max_images ) {
			return;
		}
		for ( $i = $keep_max_images; $i < $query->found_posts; $i++ ) {
			$post = $query->posts[$i];
			wp_delete_post( $post->ID );
		}
		wp_reset_postdata();
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Displays the instagram images.
	 *
	 * @since   1.0.0
	 */
	public function display_images( $options = array() ) {
		$defaults = array(
			'wrapper_width' => false,
			'images_per_row' => 3,
		);
		$this->options = array_merge( $defaults, $options );
		$query_args = array(
			'meta_key' => '_ifttt_instagram',
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
			'orderby' => 'ID DESC',
		);
		$query = new WP_Query( $query_args );
		$ids   = array();
		foreach ( $query->posts as $post ) {
			$ids[] = $post->ID;
		}
		update_postmeta_cache( $ids );
		$this->images = array();
		foreach ( $query->posts as $post ) {
			$attachment_metadata = wp_get_attachment_metadata( $post->ID );
			$custom_values = get_post_custom_values( '_ifttt_instagram', $post->ID );
			$this->images[] = array(
				'instagram_url' => unserialize( $custom_values[0] )['url'],
				'image_url' => wp_upload_dir()['url'] . '/' . $attachment_metadata['sizes']['thumbnail']['file'],
				'title' => $post->post_content,
			);
		}
		include( 'views/images.php' );
	}
}

