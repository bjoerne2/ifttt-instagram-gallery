=== IFTTT Instagram Gallery ===
Contributors: bjoerne
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XS98Y5ASSH5S4
Tags: ifttt, ifthisthenthat, instagram, gallery
Requires at least: 3.9
Tested up to: 4.4.2
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

IFTTT Instagram Gallery is a highly configurable plugin that automatically shares your Instagram pictures in an amazing looking photo grid.

== Description ==

IFTTT Instagram Gallery is a highly configurable plugin that will showcase your Instagram photos in an awesome tile pattern on your WordPress blog.

Whenever you add a new photo to your Instagram feed, it will automatically appear on your WordPress blog.

The difference to standard IFTTT WordPress channels is that your Instagram photos will not be shown as individual posts, but as part of an amazing looking mosaic photo gallery.

*Your advantages:*

* Display Instagram photos as widget, via shortcode in your articles or pages or via PHP function in your customized theme
* Full control over image sizes
* Fast load times
* Highly customizable gallery layout
* Ability to define number of images and rows
* Option to randomise order of Instagram images
* Link to original Instagram images
* Images are stored locally in your WordPress Media Library
* Fully responsive

*What is IFTTT?*

[IFTTT](http://www.ifttt.com/), which stands for "If This Then That", is a service that enables users to connect web applications like Instagram and WordPress together through simple conditional statements known as "Recipes". There is a public recipe to be used in combination with this plugin:

[If I create a Instagram photo then create the photo in my IFTTT Instagram Gallery via IFTTT Bridge](https://ifttt.com/recipes/195858-if-i-create-a-instagram-photo-then-create-the-photo-in-my-ifttt-instagram-gallery-via-ifttt-bridge](https://ifttt.com/recipes/195858-if-i-create-a-instagram-photo-then-create-the-photo-in-my-ifttt-instagram-gallery-via-ifttt-bridge)
 
*What do I have to do in order to use this plugin?*

1. Install and activate the plugin [IFTTT Bridge for WordPress](https://wordpress.org/plugins/ifttt-bridge/)
2. Register with [www.ifttt.com](http://www.ifttt.com/)
3. Install the IFTTT Instagram Gallery (installation instructions can be found under the "Installations" tab)
4. Create a IFTTT recipe. You can use the shared recipe [https://ifttt.com/recipes/195858-if-i-create-a-instagram-photo-then-create-the-photo-in-my-ifttt-instagram-gallery-via-ifttt-bridge](https://ifttt.com/recipes/195858-if-i-create-a-instagram-photo-then-create-the-photo-in-my-ifttt-instagram-gallery-via-ifttt-bridge) 
5. Display the gallery as widget, via shortcode or via PHP function

*Shortcode*

    [ifttt_instagram_gallery]

See Options below. If you want to learn more about short codes check out the official documentation: [http://codex.wordpress.org/Shortcode](http://codex.wordpress.org/Shortcode)

*PHP function*

    ifttt_instagram_gallery()

See Options below. You can use the PHP function in your customized theme.

*Options for Shortcode or PHP function*

The options are consistent with the widget options:

* wrapper_width
* images_per\_row
* image_size (thumbnail|medium|large|full)
* num_of_images
* random (true|false)

If you need help, don’t hesitate to contact me! In addition this [German blog article](http://www.bjoerne.com/instagram-bilder-mit-ifttt-den-eigenen-wordpress-blog-einbinden/) may help you.

If you like this plugin, please rate it.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'IFTTT Instagram Gallery'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ifttt-instagram-gallery.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `ifttt-instagram-gallery.zip`
2. Extract the `ifttt-instagram-gallery` directory to your computer
3. Upload the `ifttt-instagram-gallery` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Screenshots ==

1. Use the shared recipe on IFTTT to use this plugin
2. Or you create your own recipe
3. Configure and use the gallery widget
4. Plugin in action (Screenshot of [http://travel.bjoerne.com/](http://travel.bjoerne.com/))
5. Plugin options

== Changelog ==

= 1.0.1 =
* Compatibility to PHP 5.3

= 1.0.2 =
* Bugfix regarding compatibility to PHP 5.3
* Bugfix regarding customized widget title

= 1.0.3 =
* Bugfix: Special characters led to broken title attributes of Instagram images and invalid html. Title attributes of images are now escaped with htmlspecialchars().

= 1.0.4 =
* Bugfix after changed instagram URLs. Crop query part, e.g. invoke https://scontent-frt3-1.cdninstagram.com/t51.2885-15/e35/1208218_821804957925622_1457586436_n.jpg instead of https://scontent-frt3-1.cdninstagram.com/t51.2885-15/e35/1208218_821804957925622_1457586436_n.jpg?ig_cache_key=MTIwNDU4NDU1NzU3MjMxODAxNg%3D%3D.2
