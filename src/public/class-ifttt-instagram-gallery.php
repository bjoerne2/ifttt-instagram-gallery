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
	const VERSION = '1.0.5';

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
		add_action( 'ifttt_bridge', array( $this, 'load_instagram_image' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_shortcode( 'ifttt_instagram_gallery', array( $this, 'ifttt_instagram_gallery_shortcode' ) );
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
		$title          = htmlspecialchars( $content_struct['title'] );
		$description    = $content_struct['description'];
		$description_decoded = json_decode( $description, true );
		$instagram_url  = $this->get_final_url( $description_decoded['Url'] );
		$image_url      = $this->get_final_url( $description_decoded['SourceUrl'] );
		$filename       = $this->get_filename( $image_url );
		$response       = wp_remote_get( $image_url );
		$bits = $response['body'];
		$this->upload_image( $filename, $title, $bits, $instagram_url );
		$this->remove_old_images();
	}

	/**
	 * Follows redirects and returns the final url.
	 *
	 * @since   1.0.0
	 */
	private function get_final_url( $url ) {
		for ( $i = 0; $i < 5; $i++ ) {
			$url = $this->get_url_without_query( $url );
			$response = wp_remote_head( $url, array( 'redirection' => 0 ) );
			$reponse_code = wp_remote_retrieve_response_code( $response );
			if ( preg_match( '/^30.$/', $reponse_code ) ) {
				$url = wp_remote_retrieve_header( $response, 'location' );
				if ( ! $url ) {
					throw new Exception( 'No redirect url found in Location header' );
				}
			} else {
				break;
			}
		}
		return $url;
	}

	/**
	 * Returns the URL without query part.
	 *
	 * @since   1.0.2
	 */
	private function get_url_without_query( $url ) {
		$url_parts = parse_url( $url );
		return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
	}

	/**
	 * Gets the filename from a url. It returns te part after the last slash.
	 *
	 * @since   1.0.0
	 */
	private function get_filename( $image_url ) {
		preg_match( '/[^\\/]*$/', $image_url, $matches );
		return $matches[0];
	}

	/**
	 * Uploads the image into WordPress.
	 *
	 * @since   1.0.0
	 */
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
		$options = get_option( 'ifttt_instagram_gallery_options', array() );
		if ( false !== @$options['load_css'] ) {
			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
		}
	}

	/**
	 * Shortcode function to display the instagram images.
	 *
	 * @since   1.0.0
	 */
	public function ifttt_instagram_gallery_shortcode( $attr, $content = null ) {
		if ( $attr ) {
			return $this->get_images( $attr );
		} else {
			return $this->get_images();
		}
	}


	/**
	 * Displays the instagram images.
	 *
	 * @since   1.0.0
	 */
	public function display_images( $options = array() ) {
		echo $this->get_images( $options );
	}

	/**
	 * Returns the instagram images html code.
	 *
	 * @since   1.0.0
	 */
	public function get_images( $options = array() ) {
		foreach ( $options as $key => $value ) {
			if ( 'true' == $value ) {
				$options[$key] = true;
			} elseif ( 'false' == $value ) {
				$options[$key] = false;
			}
		}
		$this->merge_default_display_options( $options );
		$query_args = array(
			'meta_key' => '_ifttt_instagram',
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
			'orderby' => 'ID DESC',
		);
		if ( $options['num_of_images'] && ! $options['random'] ) {
			$query_args['posts_per_page'] = $options['num_of_images'];
		}
		$query = new WP_Query( $query_args );
		if ( $options['random'] ) {
			if ( $options['num_of_images'] ) {
				$num_of_images = $options['num_of_images'];
			} else {
				$num_of_images = count( $query->posts );
			}
			$posts = array();
			$random_posts = array();
			for ( $i = 0; $i < count( $query->posts ); $i++ ) {
				$random_posts[$i] = $query->posts[$i];
			}
			for ( $i = 0; $i < $num_of_images; $i++ ) {
				$swap_idx = count( $query->posts ) - $i - 1;
				$random_idx = rand( 0, $swap_idx );
				$posts[] = $random_posts[$random_idx];
				$random_posts[$random_idx] = $random_posts[$swap_idx];
				unset( $random_posts[$swap_idx] );
			}
		} else {
			$posts = $query->posts;
		}
		$ids = array();
		foreach ( $posts as $post ) {
			$ids[] = $post->ID;
		}
		update_postmeta_cache( $ids );
		$images = array();
		foreach ( $posts as $post ) {
			$full_image_url = $post->guid;
			if ( 'full' == $options['image_size'] ) {
				$image_url = $full_image_url;
			} else {
				$attachment_metadata = wp_get_attachment_metadata( $post->ID );
				if ( array_key_exists( $options['image_size'] , $attachment_metadata['sizes'] ) ) {
					$image_url = substr_replace( $full_image_url, $attachment_metadata['sizes'][$options['image_size']]['file'], strrpos(  $full_image_url, '/' ) + 1 );
				} else {
					$image_url = $full_image_url;
				}
			}
			$custom_values = get_post_custom_values( '_ifttt_instagram', $post->ID );
			$custom_value  = unserialize( $custom_values[0] );
			$images[] = array(
				'instagram_url' => $custom_value['url'],
				'image_url' => $image_url,
				'title' => $post->post_content,
			);
		}
		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/images.php' );
		return ob_get_clean();
	}

	/**
	 * Merges default values in options.
	 *
	 * @since   1.0.0
	 */
	public function merge_default_display_options( &$options ) {
		$defaults = array(
			'wrapper_width' => false,
			'images_per_row' => 3,
			'image_size' => 'thumbnail',
			'random' => false,
			'num_of_images' => false,
		);
		$options = array_merge( $defaults, $options );
	}

	/**
	 * Registers the widget.
	 *
	 * @since   1.0.0
	 */
	public function register_widget() {
		register_widget( 'Ifttt_Instagram_Gallery_Widget' );    
	}
}

