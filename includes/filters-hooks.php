<?php



add_action( 'userpro_after_new_registration', 'fp_userpro_after_new_registration', 22, 1 );
/**
 * Adds _points to usermeta after user registration
 * @param $user_id
 */
function fp_userpro_after_new_registration( $user_id ) {

    #$user_id = get_current_user_id();
    $user = get_user_by( 'ID', $user_id );


    if( strtolower(get_user_meta( $user_id, 'coupon', true )) == 'fanmeetfoe' ) {
        $user->add_role( 'trial-account' );
        $user->remove_role( 'subscriber' );
        update_user_meta( $user_id, '_points', 10000 );
        update_user_meta( $user_id, '_trial-start-date', date( 'Y-m-d H:i:s' ) );
    }


    # give points based on account type
    /*
    $user_meta = get_userdata( $user_id );
    $user_roles = $user_meta->roles;

    $points = in_array( "trial-account", $user_roles ) ? 500 : 0;
    update_user_meta( $user_id, '_points', $points );
    */

}

/**
 * Redirects to login page after registration
 */
/*
add_filter('userpro_register_redirect', 'fp_userpro_register_redirect', 10);
function fp_userpro_register_redirect($arg){
    return home_url() . '/login';
}
*/