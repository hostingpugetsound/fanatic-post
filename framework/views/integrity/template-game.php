<?php
global $current_user, $gameID, $homeTeamID, $homeTeamName, $awayTeamID, $awayTeamName, $ref_team_id, $team_view_type;


$beat_types = [ 'preview', 'recap' ];




if( isset($_POST['submit']) ) {

    $beat_type = in_array( $_POST['beat_type'], $beat_types ) ? $_POST['beat_type'] : '';
    #$team_id = in_array( $_POST['team_id'], $team_ids ) ? $_POST['team_id'] : '';
    #$ref_team_id = null;


    $create_beat = create_beat( $beat_type, get_the_ID(), $_POST['team_id'], $ref_team_id );
}

include dirname(__FILE__) . '/beat-title.php';
?>


    <div class="container">

        <?php
        $homewriter_user_id = get_post_meta($gameID, 'beatwriter_user_team'.$homeTeamID, 1);
        $homewriter_authorised = (!empty($current_user->ID) && $homewriter_user_id == $current_user->ID);

        $awaywriter_user_id = get_post_meta($gameID, 'beatwriter_user_team'.$awayTeamID, 1);
        $awaywriter_authorised = (!empty($current_user->ID) && $awaywriter_user_id == $current_user->ID);
        ?>


        <?php
        include dirname(__FILE__) . '/beat-tabs.php';


        # print success/error messages
        if( isset($create_beat) && !is_wp_error($create_beat) ) {
            #echo 'Beat created!';
        } else {
            if( isset( $create_beat ) && is_wp_error( $create_beat ) )
                echo $create_beat->get_error_message();
        }

        ?>
        <div>
        <?php

        if( $homeTeamID == $ref_team_id ) {
            if( $team_view_type == 'preview' ) {
                $tab_class = 'homepregame';
                $tab_view_type = 'preview';
                $tab_beat_data = $homeTeamPreview;
            } else {
                $tab_class = 'homepostgame';
                $tab_view_type = 'recap';
                $tab_beat_data = $homeTeamRecap;
            }

            tab_content( $tab_class, $tab_view_type, $tab_beat_data, $homeTeamName, $homeTeamID, $gameID, $ref_team_id, $team_view_type, $homewriter_authorised );
        } else {
            if( $team_view_type == 'preview' ) {
                $tab_class = 'awaypregame';
                $tab_view_type = 'preview';
                $tab_beat_data = $awayTeamPreview;
            } else {
                $tab_class = 'awaypostgame';
                $tab_view_type = 'recap';
                $tab_beat_data = $awayTeamRecap;
            }

            tab_content( $tab_class, $tab_view_type, $tab_beat_data, $awayTeamName, $awayTeamID, $gameID, $ref_team_id, $team_view_type, $awaywriter_authorised );
        }

        ?>

        </div>
    </div>


<script type="text/javascript">
    jQuery('.lboxtrigger').on('click', function() {
        jQuery('body').toggleClass('popped');
        jQuery('.lboxbg-fp').toggleClass('popped');
    });

    jQuery('.close-lbox-fp').on('click', function() {
        jQuery('body').toggleClass('popped');
        jQuery('.lboxbg-fp').toggleClass('popped');
    });

</script>

