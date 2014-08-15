@devonly
Feature: Setup fresh WordPress
  In order to update the database file
  As a behat developer
  I need to get a fresh WordPress

  Scenario: Get fresh WordPress
    Given a fresh WordPress is installed

  Scenario: Get WordPress with activated plugin
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed (from src)
    And the plugin "ifttt-instagram-gallery" is activated

  Scenario: Get German WordPress with activated plugin
    Given the blog language is "de_DE"
    And a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed (from src)
    And the plugin "ifttt-instagram-gallery" is activated

  Scenario: Get WordPress with activated IFTTT Instagram Gallery plugin and widget, EM Object Cache plugin
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "em-object-cache" is installed
    And the widget "ifttt-instagram-gallery" is activated
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 2 times
    And I am logged as an administrator
    And I go to "/wp-admin/plugins.php"
    And I activate the plugin "em-object-cache"
