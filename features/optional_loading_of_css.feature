Feature: Skip loading of css
  In order to take full responsibility of css
  As an administrator
  I need to be able to skip the loading of the plugin css
  
  Scenario: Load css
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    When I go to "/"
    Then the css file "/wp-content/plugins/ifttt-instagram-gallery/public/assets/css/public.css" should be loaded
  
  Scenario: Skip loading
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the option "ifttt_instagram_gallery_options" has the serialized value {"load_css":false}
    When I go to "/"
    Then the css file "/wp-content/plugins/ifttt-instagram-gallery/public/assets/css/public.css" should not be loaded

  Scenario: Preset load css
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And I am logged as an administrator
    When I go to "/wp-admin/options-general.php?page=ifttt-instagram-gallery.php"
    Then the checkbox "ifttt_instagram_gallery_options_load_css" should be checked

  Scenario: Configure load css
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And I am logged as an administrator
    When I go to "/wp-admin/options-general.php?page=ifttt-instagram-gallery.php"
    And I check "ifttt_instagram_gallery_options_load_css"
    And I press "submit"
    Then the option "ifttt_instagram_gallery_options" should be serialized and contain "load_css":false

