<?php
/**
 * @package Ifttt_Instagram_Gallery_Testplugin
 * @version 0.0.0
 */
/*
Plugin Name: IFTTT Instagram Gallery Testplugin
Version: 0.0.0
*/

function ifttt_instagram_gallery_testplugin_load_images() {
	$content_struct = get_option( 'ifttt_instagram_gallery_testplugin_content_struct' );
	do_action( 'ifttt_wordpress_bridge', $content_struct );
}

add_action( 'admin_post_nopriv_ifttt_instagram_gallery_testplugin_load_images', 'ifttt_instagram_gallery_testplugin_load_images' );
