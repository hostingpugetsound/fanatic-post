<?php

// =============================================================================

// Favorites are stored in the user meta table as individual items under the "favorites" meta_key with the post_id as the meta_value.
// =============================================================================

$favorites = get_user_meta( get_current_user_id(), "favorites", false );

get_header();
?>

    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width offset">

            <div class="x-column x-sm x-1-2 suggested-teams">
                <div class="avatar-container">
                    <?php
                    echo do_shortcode( '[userpro template=card]' );
                    #echo do_shortcode( '[userpro template=view header_only=true]' );

                    #require dirname(__FILE__) . '/../../../userpro/view.php';


                    /*
                    global $userpro;
                    if( userpro_is_logged_in() ) {
                        // retrieves the user id of the user logged
                        $user_id = get_current_user_id();
                    } else {
                        // Get the id of the user Usier not connencte
                        $user_id = userpro_get_view_user( get_query_var(‘up_username’));
                    }
                    */

                    #userpro_get_view_user();
                    ?>
                </div>
                <div class="actualsuggested">
                    <h2 class="red-header">Suggested Teams</h2>
                    <div class="light-bg suggested-teams-container">
                        <?php
                        # @todo: pull suggested
                        foreach( $favorites as $team_id ) {
                            $title = get_the_title( $team_id );
                            if( $title != 'Favorites' )
                                echo fsu_team_circle( $team_id, true );
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="x-column x-sm x-1-2 last">
                <?php x_get_view( 'global', '_my-teams' ); ?>

        </div>
    </div>



<?php get_footer(); ?>