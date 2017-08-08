<?php

if( !userpro_is_logged_in() ) {
    echo do_shortcode( '[userpro template=login]' );
}