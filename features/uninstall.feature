Feature: Uninstall plugin
  In order to clean up
  As an administrator
  I need to be able to uninstall the plugin without a footprint

  Scenario: Uninstall plugin
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the option "ifttt_instagram_gallery_options" has the serialized value {"load_css":false}
    And I am logged as an administrator
    When I go to "/wp-admin/plugins.php"
    And I deactivate the plugin "ifttt-instagram-gallery"
    And I uninstall the plugin "ifttt-instagram-gallery"
    Then I should see the message "The selected plugins have been deleted"
    And the option "ifttt_instagram_gallery_options" should not exist
