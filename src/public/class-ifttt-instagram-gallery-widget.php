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
 *
 * @package Ifttt_Instagram_Gallery
 * @author  Björn Weinbrenner <info@bjoerne.com>
 */
class Ifttt_Instagram_Gallery_Widget extends WP_Widget {

	public function __construct() {
		$plugin = Ifttt_Instagram_Gallery::get_instance();
		$this->widget_slug = $plugin->get_plugin_slug();

		parent::__construct( $this->widget_slug, 'IFTTT Instagram Gallery', array( 'description' => _x( 'Displays instagram images imported via IFTTT', 'Widget description', $this->widget_slug ) ) );
		
		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	public function widget( $args, $instance ) {
		$cache = wp_cache_get( $this->widget_slug, 'widget' );
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}
		$widget_id = array_key_exists( 'widget_id', $args ) ? $args['widget_id'] : $this->id;
		if ( array_key_exists( $widget_id, $cache ) ) {
			return print $cache[ $widget_id ];
		}
		$title  = @$instance['title'] ? @$instance['title'] : _x( 'Instagram', 'Widget title', $this->widget_slug );
		$title  = apply_filters( 'widget_title', $title );
		$images = Ifttt_Instagram_Gallery::get_instance()->get_images( $instance );
		extract( $args, EXTR_SKIP );

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$output = ob_get_clean();

		$cache[$widget_id] = $output;
		wp_cache_set( $this->widget_slug, $cache, 'widget' );

		echo $output;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		Ifttt_Instagram_Gallery::get_instance()->merge_default_display_options( $instance );
		$instance['title'] = @$instance['title'] ? @$instance['title'] : '';
		extract( $instance, EXTR_SKIP );
		$sizes = array();
		$sizes[] = array(
			'value' => 'thumbnail',
			'text'  => __( 'Thumbnail' ) . ' - ' . get_option( 'thumbnail_size_w' ) . ' x ' .get_option( 'thumbnail_size_h' ),
		);
		$sizes[] = array(
			'value' => 'medium',
			'text'  => __( 'Medium' ) . ' - ' . get_option( 'medium_size_w' ) . ' x ' . get_option( 'medium_size_h' ),
		);
		$sizes[] = array(
			'value' => 'large',
			'text'  => __( 'Large' ) . ' - ' . get_option( 'large_size_w' ) . ' x ' . get_option( 'large_size_h' ),
		);
		$sizes[] = array(
			'value' => 'full',
			'text'  => __( 'Full Size' ),
		);
		include( plugin_dir_path( __FILE__ ) . 'views/widget-form.php' );
	}

	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}
}
