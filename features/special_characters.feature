Feature: Display instagram images with special characters in title
  In order to see instagram images anywhere in a WordPress theme
  As a developer
  I need to be able to call a PHP function from the theme
  
  Scenario: See images in theme ordered from new to old
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | \"An Instagram image with <special characters> & umläüte\" |
      | Url       | __webserver_url__  |
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | Another Instagram image |
      | Url       | __webserver_url__       |
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    When I go to "/"
    Then I should see images with titles
      | Another Instagram image |
      | "An Instagram image with <special characters> & umläüte" |
    And I should see images with
      | number of images | 2 |
