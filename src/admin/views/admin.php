<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Ifttt_Instagram_Gallery_Admin
 * @author    Björn Weinbrenner <info@bjoerne.com>
 * @license   GPLv3
 * @link      http://bjoerne.com
 * @copyright 2014 bjoerne.com
 */
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form method="post" action="options.php">
<?php
	settings_fields( 'ifttt_instagram_gallery_options_group' );
	do_settings_sections( 'ifttt_instagram_gallery_options_group' );
?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="ifttt_instagram_gallery_options_keep_max_images"><?php _ex( 'Maximum numbers of images to keep', $this->plugin_slug ); ?></label></th>
					<td><input name="ifttt_instagram_gallery_options[keep_max_images]" type="text" class="regular-text" id="ifttt_instagram_gallery_options_keep_max_images" value="<?php echo esc_attr( @$this->options['keep_max_images'] ); ?>">
						<p class="description"><?php _ex( 'If you configure this number and the number of Instagram images exceeds it, older images are deleted.', 'Form field description', $this->plugin_slug ); ?></p></td>
				</tr>
				<tr>
					<th scope="row"><?php _ex( 'Load plugin css', $this->plugin_slug ); ?></th>
					<td><label for="ifttt_instagram_gallery_options_load_css"><input name="ifttt_instagram_gallery_options[load_css]" type="checkbox" id="ifttt_instagram_gallery_options_load_css" value="1"<?php checked( '1', false !== @$this->options['load_css'] ); ?>>
						<?php _ex( "If you want take care of CSS in your WordPress theme, you can uncheck this option to skip the loading of the plugin's CSS file", 'Form field description', $this->plugin_slug ); ?></label></td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
</div>
