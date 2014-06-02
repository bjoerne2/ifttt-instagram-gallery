Feature: Load images triggered by IFTTT
  In order to display instagram images
  As a visitor
  The images must be loaded into WordPress
  
  Scenario: Load single image
    Given a fresh WordPress is installed
    And the plugin "ifttt-instagram-gallery" is installed and activated (from src)
    And the plugin "ifttt-instagram-gallery-testplugin" is installed and activated (from features/plugins/ifttt-instagram-gallery-testplugin.php)
    And the option "uploads_use_yearmonth_folders" has the value "0"
    And the option "ifttt_instagram_gallery_testplugin_content_struct" has the serialized content struct
      | Caption   | My Instagram image |
      | Url       | http://example.com |
      | Image     | ifttt_instagram_test_image.jpg |
    And the image "ifttt_instagram_test_image.jpg" is copied to the webserver
    When the admin post action "ifttt_instagram_gallery_testplugin_load_images" is invoked
    Then the image "ifttt_instagram_test_image.jpg" exists in the upload folder
    And a post exists with
      | post_content   | My Instagram image |
      | post_title     | ifttt_instagram_test_image.jpg |
      | post_name      | ifttt_instagram_test_image-jpg |
      | post_type      | attachment |
      | post_mime_type | image/jpeg |
      | post_status    | inherit |
      | metadata       | _ifttt_instagram => {"url":"http://example.com"} |
