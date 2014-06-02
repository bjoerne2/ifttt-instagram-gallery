<ul class="ifttt-instagram-image-list">
<?php foreach ( $this->images as $image ): ?>
  <li><a href="<?php echo $image['instagram_url'] ?>"><img src="<?php echo $image['image_url'] ?>" alt="<?php echo $image['title'] ?>" title="<?php echo $image['title'] ?>" /></a></li>
<?php endforeach ?>
</ul><div style="clear: both;"></div>
