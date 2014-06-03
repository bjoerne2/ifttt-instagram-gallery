Feature: Display instragram images via PHP function
  In order to see instragram images anywhere in a WordPress theme
  As a developer
  I need to be able to call a PHP function from the theme
  
  Scenario: See images in theme ordered from new to old
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | An Instagram image |
      | Url       | http://example.com |
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | Another Instagram image |
      | Url       | http://example.com |
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images()" with titles
      | Another Instagram image |
      | An Instagram image |
    And I should see images in section "ifttt_instagram_gallery_images()" with
      | number of images | 2 |
  
  Scenario: See maximum 1 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 2 times
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>1))" with
      | number of images | 2 |
      | row width >=     | 784 |
      | row width <=     | 800 |
      | maximum per row  | 1 |

  Scenario: See maximum 2 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 3 times
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>2))" with
      | number of images | 3 |
      | row width >=     | 784 |
      | row width <=     | 800 |
      | maximum per row  | 2 |

  Scenario: See maximum 3 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 4 times
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>3))" with
      | number of images | 4 |
      | row width >=     | 784 |
      | row width <=     | 800 |
      | maximum per row  | 3 |

  Scenario: See maximum 8 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 9 times
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>8))" with
      | number of images | 9 |
      | row width >=     | 784 |
      | row width <=     | 800 |
      | maximum per row  | 8 |

  Scenario: See maximum 20 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the theme "ifttt-instagram-gallery-testtheme" is installed and activated (from features/themes/ifttt-instagram-gallery-testtheme)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 21 times
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>20))" with
      | number of images | 21 |
      | row width >=     | 784 |
      | row width <=     | 800 |
      | maximum per row  | 20 |
