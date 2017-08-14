<?php



add_action( 'userpro_after_new_registration', 'fp_userpro_after_new_registration', 22, 1 );
/**
 * Adds _points to usermeta after user registration
 * @param $user_id
 */
function fp_userpro_after_new_registration( $user_id ) {

    #$user_id = get_current_user_id();
    $user_meta = get_userdata( $user_id );
    $user_roles = $user_meta->roles;

    $points = in_array( "fanatic-account", $user_roles ) ? 500 : 100;
    update_user_meta( $user_id, '_points', $points );

}
