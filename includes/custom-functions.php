<?php

function fp_add_custom_roles() {
    $caps = array( 'read' => true, 'level_0' => true );
    #add_role( 'free-account', 'Free', $caps );
    add_role( 'fan-account', 'Fan', $caps );
    add_role( 'fanatic-account', 'Fanatic', $caps );
}

#fp_add_custom_roles();