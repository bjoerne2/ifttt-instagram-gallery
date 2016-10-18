Feature: Display instagram images in widget
  In order to see instagram images in a widget
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
    And the widget "widget_ifttt-instagram-gallery" should have the options
      | title          |           |
      | wrapper_width  |           |
      | images_per_row | 3         |
      | image_size     | thumbnail |
      | num_of_images  |           |
  
  Scenario: Configure widget
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And I am logged as an administrator
    When I go to "http://localhost/wordpress-behat/wp-admin/widgets.php"
    And I activate the widget "ifttt-instagram-gallery"
    And I fill in "widget-ifttt-instagram-gallery-2-title" with "My Instagram"
    And I fill in "widget-ifttt-instagram-gallery-2-wrapper_width" with "777px"
    And I fill in "widget-ifttt-instagram-gallery-2-images_per_row" with "5"
    And I fill in "widget-ifttt-instagram-gallery-2-image_size" with "large"
    And I check "widget-ifttt-instagram-gallery-2-random"
    And I fill in "widget-ifttt-instagram-gallery-2-num_of_images" with "11"
    And I press "widget-ifttt-instagram-gallery-2-savewidget"
    And I wait for 2 second
    And the widget "widget_ifttt-instagram-gallery" should have the options
      | title          | My Instagram |
      | wrapper_width  | 777px        |
      | images_per_row | 5            |
      | image_size     | large        |
      | random         | true         |
      | num_of_images  | 11           |

  Scenario: Display images
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the widget "ifttt-instagram-gallery" is activated
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 9 times
    When I go to "/"
    Then I should see images with
      | number of images | 9 |
      | maximum per row  | 3 |

  Scenario: See two images with titles and filenames
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | An Instagram image |
      | Url       | __webserver_url__  |
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | Another Instagram image |
      | Url       | __webserver_url__  |
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the widget "ifttt-instagram-gallery" is activated
    When I go to "/"
    Then I should see images with titles
      | Another Instagram image |
      | An Instagram image |
    And I should see images with
      | number of images | 2 |
    And I should see image files
      | ifttt_instagram_test_image-1-150x150.jpg |
      | ifttt_instagram_test_image-150x150.jpg |

  Scenario Outline: See maximum images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked <num_of_images> times
    And the widget "ifttt-instagram-gallery" is activated with the options
      | images_per_row | <images_per_row> |
    When I go to "/"
    Then I should see images with
      | number of images | <num_of_images> |
      | row width >=     | 158 |
      | row width <=     | 162 |
      | maximum per row  | <images_per_row> |
    Examples:
        | num_of_images | images_per_row |
        | 2             | 1              |
        | 3             | 2              |
        | 4             | 3              |
        | 5             | 4              |
        | 6             | 5              |
        | 7             | 6              |

  Scenario Outline: Display image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the widget "ifttt-instagram-gallery" is activated with the options
      | image_size | <image_size> |
    When I go to "/"
    Then I should see image file "<image_file>"
    Examples:
        | image_size | image_file                             |
        | thumbnail  | ifttt_instagram_test_image-150x150.jpg |
        | medium     | ifttt_instagram_test_image-300x300.jpg |
        | large      | ifttt_instagram_test_image.jpg         |
        | full       | ifttt_instagram_test_image.jpg         |

  Scenario: Display number of images
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 2 times
    And the widget "ifttt-instagram-gallery" is activated with the options
      | num_of_images | 1 |
    When I go to "/"
    Then I should see images with
      | number of images | 1 |

  Scenario: See random files
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 10 times
    And the widget "ifttt-instagram-gallery" is activated with the options
      | random | true |
    When I go to "/"
    And I should see images with
      | number of images | 10 |
    And I should not see image files
      | ifttt_instagram_test_image-9-150x150.jpg |
      | ifttt_instagram_test_image-8-150x150.jpg |
      | ifttt_instagram_test_image-7-150x150.jpg |
      | ifttt_instagram_test_image-6-150x150.jpg |
      | ifttt_instagram_test_image-5-150x150.jpg |
      | ifttt_instagram_test_image-4-150x150.jpg |
      | ifttt_instagram_test_image-3-150x150.jpg |
      | ifttt_instagram_test_image-2-150x150.jpg |
      | ifttt_instagram_test_image-1-150x150.jpg |
      | ifttt_instagram_test_image-150x150.jpg |

  Scenario: See 10 random file of 20
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 20 times
    And the widget "ifttt-instagram-gallery" is activated with the options
      | random        | true |
      | num_of_images | 10   |
    When I go to "/"
    And I should see images with
      | number of images | 10 |
