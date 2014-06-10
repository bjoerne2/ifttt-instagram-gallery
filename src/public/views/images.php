<div class="ifttt-instagram-images ifttt-instagram-<?php echo $options['images_per_row']; ?>-per-row"<?php echo $options['wrapper_width'] ? ' style="width: ' . $options['wrapper_width'] .'"' : '' ?>>
<ul>
<?php foreach ( $images as $image ): ?>
  <li><a href="<?php echo $image['instagram_url'] ?>"><img src="<?php echo $image['image_url'] ?>" alt="<?php echo $image['title'] ?>" title="<?php echo $image['title'] ?>" /></a></li>
<?php endforeach ?>
</ul></div><div style="clear: both;"></div>
