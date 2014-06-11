Feature: Remove old images
  In order to limit the number of images
  As an administrator
  I need to be able to configure the number of images so that old images are deleted
  
  Scenario: Configure maximum number of images
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 2 times
    And I am logged as an administrator
    When I go to "/wp-admin/options-general.php?page=ifttt-instagram-gallery.php"
    And I fill in "ifttt_instagram_gallery_options_keep_max_images" with "1"
    And I press "submit"
    Then I should see the message "Settings saved"
    And the option "ifttt_instagram_gallery_options" should be serialized and contain {"keep_max_images":1}
    When I go to "/"
    Then I should see images with
      | number of images | 1 |

  Scenario: Configure invalid maximum number of images
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And I am logged as an administrator
    When I go to "/wp-admin/options-general.php?page=ifttt-instagram-gallery.php"
    And I fill in "ifttt_instagram_gallery_options_keep_max_images" with "XXX"
    And I press "submit"
    Then I should see the error message "Invalid value for 'Maximum numbers of images to keep'. Must be a positive integer."

  Scenario: Preset max number of images
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the option "ifttt_instagram_gallery_options" has the serialized value {"keep_max_images":7}
    And I am logged as an administrator
    When I go to "/wp-admin/options-general.php?page=ifttt-instagram-gallery.php"
    Then the "ifttt_instagram_gallery_options_keep_max_images" field should contain "7"

  Scenario: Remove old images when adding a new one
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the option "ifttt_instagram_gallery_options" has the serialized value {"keep_max_images":1}
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 2 times
    And I am logged as an administrator
    When I go to "/"
    Then I should see images with
      | number of images | 1 |
