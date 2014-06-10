<p>
  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php printf( _x( 'Title (default: %s):', 'Widget option', $this->widget_slug ), _x( 'Instagram', 'Default widget title', $this->widget_slug ) ); ?>
  </label> 
  <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'wrapper_width' ); ?>"><?php _ex( 'Wrapper width:', 'Widget option', $this->widget_slug ); ?></label> 
  <input class="widefat" id="<?php echo $this->get_field_id( 'wrapper_width' ); ?>" name="<?php echo $this->get_field_name( 'wrapper_width' ); ?>" type="text" value="<?php echo esc_attr( $wrapper_width ); ?>">
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'images_per_row' ); ?>"><?php _ex( 'Images per row:', 'Widget option', $this->widget_slug ); ?></label> 
  <input class="widefat" id="<?php echo $this->get_field_id( 'images_per_row' ); ?>" name="<?php echo $this->get_field_name( 'images_per_row' ); ?>" type="text" value="<?php echo esc_attr( $images_per_row ); ?>">
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _ex( 'Image size:', 'Widget option', $this->widget_slug ); ?></label> 
  <input class="widefat" id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>" type="text" value="<?php echo esc_attr( $image_size ); ?>">
</p>
<p>
  <input class="checkbox" type="checkbox"<?php checked( 'true' == $random ) ?> id="<?php echo $this->get_field_id('random'); ?>" name="<?php echo $this->get_field_name('random'); ?>" value="true" /> <label for="<?php echo $this->get_field_id('random'); ?>"><?php _ex( 'Random:', 'Widget option', $this->widget_slug ); ?></label>
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'num_of_images' ); ?>"><?php _ex( 'Number of images:', 'Widget option', $this->widget_slug ); ?></label> 
  <input class="widefat" id="<?php echo $this->get_field_id( 'num_of_images' ); ?>" name="<?php echo $this->get_field_name( 'num_of_images' ); ?>" type="text" value="<?php echo esc_attr( $num_of_images ); ?>">
</p>
