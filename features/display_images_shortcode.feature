Feature: Display instragram images via shortcode
  In order to see instragram images anywhere in a post
  As a developer
  I need to be able to use a shortcode
  
  Scenario: See two images with titles and filenames
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
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
    And the hello world post has the content "[ifttt_instagram_gallery]"
    When I go to "/"
    Then I should see images with titles
      | Another Instagram image |
      | An Instagram image |
    And I should see images with
      | number of images | 2 |
    And I should see image files
      | ifttt_instagram_test_image1-150x150.jpg |
      | ifttt_instagram_test_image-150x150.jpg |

  Scenario Outline: See maximum images per row
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked <num_of_images> times
    And the hello world post has the content "[ifttt_instagram_gallery wrapper_width=600px images_per_row=<images_per_row>]"
    When I go to "/"
    Then I should see images with
      | number of images | <num_of_images> |
      | row width >=     | 582 |
      | row width <=     | 600 |
      | maximum per row  | <images_per_row> |
    Examples:
        | num_of_images | images_per_row |
        | 2             | 1              |
        | 3             | 2              |
        | 4             | 3              |
        | 5             | 4              |
        | 6             | 5              |
        | 7             | 6              |
        | 8             | 7              |
        | 9             | 8              |
        | 10            | 9              |
        | 11            | 10             |
        | 12            | 11             |
        | 13            | 12             |
        | 14            | 13             |
        | 15            | 14             |
        | 16            | 15             |
        | 17            | 16             |
        | 18            | 17             |
        | 19            | 18             |
        | 20            | 19             |
        | 21            | 20             |

  Scenario: Display default image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the hello world post has the content "[ifttt_instagram_gallery]"
    When I go to "/"
    Then I should see image file "ifttt_instagram_test_image-150x150.jpg"

  Scenario Outline: Display image size
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    And the hello world post has the content "[ifttt_instagram_gallery image_size=<image_size>]"
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
    And the hello world post has the content "[ifttt_instagram_gallery num_of_images=1]"
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
    And the hello world post has the content "[ifttt_instagram_gallery random=true]"
    When I go to "/"
    And I should see images with
      | number of images | 10 |
    And I should not see image files
      | ifttt_instagram_test_image9-150x150.jpg |
      | ifttt_instagram_test_image8-150x150.jpg |
      | ifttt_instagram_test_image7-150x150.jpg |
      | ifttt_instagram_test_image6-150x150.jpg |
      | ifttt_instagram_test_image5-150x150.jpg |
      | ifttt_instagram_test_image4-150x150.jpg |
      | ifttt_instagram_test_image3-150x150.jpg |
      | ifttt_instagram_test_image2-150x150.jpg |
      | ifttt_instagram_test_image1-150x150.jpg |
      | ifttt_instagram_test_image-150x150.jpg |

  Scenario: See 10 random file of 20
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the file "ifttt_instagram_test_image.jpg" is copied to the webserver
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Image     | ifttt_instagram_test_image.jpg |
    And the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked 20 times
    And the hello world post has the content "[ifttt_instagram_gallery random=true num_of_images=10]"
    When I go to "/"
    And I should see images with
      | number of images | 10 |
