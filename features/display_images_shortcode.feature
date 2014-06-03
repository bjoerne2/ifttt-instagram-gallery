Feature: Display instragram images via shortcode
  In order to see instragram images anywhere in a post
  As a developer
  I need to be able to use a shortcode
  
  Scenario: See greeting
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
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
    And the hello world post has the content "[ifttt_instagram_gallery_images]"
    When I go to "/"
    Then I should see images with titles
      | Another Instagram image |
      | An Instagram image |
    And I should see images with
      | number of images | 2 |

  Scenario: See maximum 1 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 2 times
    And the hello world post has the content "[ifttt_instagram_gallery_images wrapper_width=600px images_per_row=1]"
    When I go to "/"
    Then I should see images with
      | number of images | 2 |
      | row width >=     | 588 |
      | row width <=     | 600 |
      | maximum per row  | 1 |

  Scenario: See maximum 2 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 3 times
    And the hello world post has the content "[ifttt_instagram_gallery_images wrapper_width=600px images_per_row=2]"
    When I go to "/"
    Then I should see images with
      | number of images | 3 |
      | row width >=     | 588 |
      | row width <=     | 600 |
      | maximum per row  | 2 |

  Scenario: See maximum 3 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 4 times
    And the hello world post has the content "[ifttt_instagram_gallery_images wrapper_width=600px images_per_row=3]"
    When I go to "/"
    Then I should see images with
      | number of images | 4 |
      | row width >=     | 588 |
      | row width <=     | 600 |
      | maximum per row  | 3 |

  Scenario: See maximum 8 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 9 times
    And the hello world post has the content "[ifttt_instagram_gallery_images wrapper_width=600px images_per_row=8]"
    When I go to "/"
    Then I should see images with
      | number of images | 9 |
      | row width >=     | 588 |
      | row width <=     | 600 |
      | maximum per row  | 8 |

  Scenario: See maximum 20 images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 21 times
    And the hello world post has the content "[ifttt_instagram_gallery_images wrapper_width=600px images_per_row=20]"
    When I go to "/"
    Then I should see images with
      | number of images | 21 |
      | row width >=     | 588 |
      | row width <=     | 600 |
      | maximum per row  | 20 |

  Scenario: Display default image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the hello world post has the content "[ifttt_instagram_gallery_images]"
    When I go to "/"
    Then I should see image file "ifttt_instagram_test_image-150x150.jpg"

  Scenario: Display medium image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the hello world post has the content "[ifttt_instagram_gallery_images image_size=medium]"
    When I go to "/"
    Then I should see image file "ifttt_instagram_test_image-300x300.jpg"

  Scenario: Display large image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the hello world post has the content "[ifttt_instagram_gallery_images image_size=large]"
    When I go to "/"
    Then I should see image file "ifttt_instagram_test_image.jpg"

  Scenario: Display full image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the hello world post has the content "[ifttt_instagram_gallery_images image_size=full]"
    When I go to "/"
    Then I should see image file "ifttt_instagram_test_image.jpg"
