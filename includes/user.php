<?php

/**
 * Get user points by user ID
 * @param $user_id
 * @return int
 */
function fp_get_user_points( $user_id = null ) {
    if( !$user_id )
        $user_id = get_current_user_id();
    return intval( get_user_meta( $user_id, '_points', true ) );
}

