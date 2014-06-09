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
    $this->plugin_slug = $plugin->get_plugin_slug();

    parent::__construct( 'ifttt-instagram-gallery', 'Instagram Gallery', array( 'description' => _x( 'Displays all instagram images', 'Widget description', $this->plugin_slug ) ) );
  }

  public function widget( $args, $instance ) {
    $title = _x( 'Instagram', 'Widget title', $this->plugin_slug );
    $title = apply_filters( 'widget_title', $title );
  // before and after widget arguments are defined by themes
    echo $args['before_widget'];
    if ( ! empty( $title ) ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    Ifttt_Instagram_Gallery::get_instance()->display_images(); // TODO options
    echo $args['after_widget'];
  }
}
