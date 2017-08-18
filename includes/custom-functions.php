<?php

function fp_add_custom_roles() {
    $caps = array( 'read' => true, 'level_0' => true );
    #add_role( 'free-account', 'Free', $caps );
    add_role( 'trial-account', 'Trial account', $caps );
    add_role( 'fan-account', 'Fan', $caps );
    add_role( 'fanatic-account', 'Fanatic', $caps );
}

#fp_add_custom_roles();


add_image_size( 'home-long', 1100, 550, true );
add_image_size( 'home-short', 550, 550, true );
add_image_size( 'banner-long', 9999, 550, true );