<?php

// =============================================================================
// TEMPLATE NAME: Write a Beat
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
//
// Content is output based on which Stack has been selected in the Customizer.
// To view and/or edit the markup of your Stack's index, first go to "views"
// inside the "framework" subdirectory. Once inside, find your Stack's folder
// and look for a file called "template-blank-1.php," where you'll be able to
// find the appropriate output.
// =============================================================================


get_header();

$query = new WP_Query( array(
    'p' => (int) $_GET['game'],
    'post_type' => 'game',
    'posts_per_page' => 1,
) );
$beat_types = [ 'preview', 'recap' ];
$game = $query->get_posts();
$game = $game[0];


# get teams so we can make sure the ID is correct
$teams = fsu_get_teams_from_game( $game->ID );

$team_ids = [];
foreach( $teams as $team )
    $team_ids[] = $team->ID;



if( isset($_POST['submit']) && $query->have_posts() ) {

    $beat_type = in_array( $_POST['beat_type'], $beat_types ) ? $_POST['beat_type'] : '';
    $team_id = in_array( $_POST['team_id'], $team_ids ) ? $_POST['team_id'] : '';
    $ref_team_id = null;


    $create_beat = create_beat( $beat_type, $game->ID, $team_id, $ref_team_id );

}
    ?>
    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width">
            <div class="x-column x-sm x-1-1 content">

            <?php
            if( $query->have_posts() ) {
                while( $query->have_posts() ) : $query->the_post();
                    ?>
                    <h2><?php echo $post->post_title; ?></h2>
                    <h3>Write a Beat</h3>

                    <?php

                    if( isset($create_beat) && !is_wp_error($create_beat) ) {
                        echo 'Beat created!';
                    } else {
                        if( isset($create_beat) && is_wp_error($create_beat) )
                            echo $create_beat->get_error_message();
                    ?>


                    <form action="" method="post" enctype="multipart/form-data">
                        <label for="beat_type">
                            Beat Type<br />
                            <select name="beat_type">
                                <?php
                                foreach( $beat_types as $beat_type )
                                    echo sprintf( '<option value="%s">%s</option>', $beat_type, ucwords($beat_type) );
                                ?>
                            </select>
                        </label>
                        <label for="team">
                            Team<br />
                            <select name="team_id">
                                <?php
                                foreach( $teams as $team )
                                    echo sprintf( '<option value="%s">%s</option>', $team->ID, $team->post_title );
                                ?>
                            </select>
                        </label>

                        <label for="body">
                            Body<br />
                            <textarea name="body"></textarea>
                        </label>


                        <div class="x-column x-sm x-1-2">
                            <label for="body">
                                Image<br />
                                <input type="file" name="image" />
                            </label>
                        </div>
                        <div class="x-column x-sm x-1-2">
                            <input type="submit" name="submit" value="POST" />
                        </div>
                    </form>

                    <?php } ?>

                <?php
                endwhile;
                wp_reset_postdata();
            } ?>
            </div>

        </div>
    </div>

<?php get_footer(); ?>
