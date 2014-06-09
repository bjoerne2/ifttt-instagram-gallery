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
