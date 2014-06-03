<!DOCTYPE html>
<html>
<head>
  <title>IFTTT Instagram Gallery Test Theme</title>
  <?php wp_head(); ?>
  <script src="<?php echo get_template_directory_uri(); ?>/jquery-2.1.1.min.js" type="text/javascript"></script>
</head>
<body>
<div>
<h1>ifttt_instagram_gallery_images()</h1>
<?php ifttt_instagram_gallery_images() ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>1))</h1>
<?php ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>1)) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>2))</h1>
<?php ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>2)) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>3))</h1>
<?php ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>3)) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>8))</h1>
<?php ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>8)) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>20))</h1>
<?php ifttt_instagram_gallery_images(array('wrapper_width'=>'800px','images_per_row'=>20)) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('image_size'=>'medium'))</h1>
<?php ifttt_instagram_gallery_images(array('image_size'=>'medium')) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('image_size'=>'large'))</h1>
<?php ifttt_instagram_gallery_images(array('image_size'=>'large')) ?>
</div>
<div>
<h1>ifttt_instagram_gallery_images(array('image_size'=>'full'))</h1>
<?php ifttt_instagram_gallery_images(array('image_size'=>'full')) ?>
</div>
</body>
</html>