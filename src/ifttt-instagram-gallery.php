<?php
/**
 * @package   Ifttt_Instagram_Gallery
 * @author    Björn Weinbrenner <info@bjoerne.com>
 * @license   GPLv3
 * @link      http://bjoerne.com
 * @copyright 2014 bjoerne.com
 *
 * @wordpress-plugin
 * Plugin Name:       IFTTT Instagram Gallery
 * Plugin URI:        http://www.bjoerne.com
 * Description:       IFTTT Instagram Gallery is a highly configurable widget that automatically shares your latest Instagram pictures in an amazing looking photo grid on your WordPress blog.
 * Version:           1.0.5
 * Author:            Björn Weinbrenner
 * Author URI:        http://www.bjoerne.com/
 * Text Domain:       ifttt-instagram-gallery
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/bjoerne2/ifttt-instagram-gallery
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-ifttt-instagram-gallery.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-ifttt-instagram-gallery-widget.php' );

add_action( 'plugins_loaded', array( 'Ifttt_Instagram_Gallery', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-ifttt-instagram-gallery-admin.php' );
	add_action( 'plugins_loaded', array( 'Ifttt_Instagram_Gallery_Admin', 'get_instance' ) );
}

function ifttt_instagram_gallery( $options = array() ) {
	Ifttt_Instagram_Gallery::get_instance()->display_images( $options );
}