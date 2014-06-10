<?php
echo $before_widget;
if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}
echo $images;
echo $after_widget;
