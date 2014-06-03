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
    And the hello world post has the content "<div><h1>ifttt_instagram_gallery_images()</h1>[ifttt_instagram_gallery_images]</div>"
    When I go to "/"
    Then I should see images in section "ifttt_instagram_gallery_images()" with titles
      | Another Instagram image |
      | An Instagram image |
    And I should see images in section "ifttt_instagram_gallery_images()" with
      | number of images | 2 |
