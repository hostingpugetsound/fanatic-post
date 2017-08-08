<?php

if( !is_user_logged_in() ) {
    echo do_shortcode( '[userpro template=login]' );
}