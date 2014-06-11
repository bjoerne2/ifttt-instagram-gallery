<?php
$full_request_uri_php = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$full_request_uri_jpg = str_replace( '.php', '.jpg', $full_request_uri_php );
header( "Location: $full_request_uri_jpg" );
