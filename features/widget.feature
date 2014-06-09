Feature: Display instragram images in widget
  In order to see instragram images in a widget
  As a administrator
  I need to be able to activate, configure and display the widget
  
  Scenario: See widget in widget list
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And I am logged as an administrator
    When I go to "http://localhost/wordpress-behat/wp-admin/widgets.php"
    Then I should see "Instagram Gallery"
  
  Scenario: Activate widget
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And I am logged as an administrator
    When I go to "http://localhost/wordpress-behat/wp-admin/widgets.php"
    And I activate the widget "ifttt-instagram-gallery"
    Then the widget "ifttt-instagram-gallery" should be activated
    And the option "widget_ifttt-instagram-gallery" should have the serialized value {"2":[],"_multiwidget":1}

  Scenario: Display images
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the widget "ifttt-instagram-gallery" is activated
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 9 times
    When I go to "/"
    Then I should see images with
      | number of images | 9 |
      | maximum per row  | 3 |
