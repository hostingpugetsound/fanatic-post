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

$game = new WP_Query( array(
    'p' => (int) $_GET['game'],
    'post_type' => 'game',
    'posts_per_page' => 1,
) );

    ?>
    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width">
            <div class="x-column x-sm x-1-1 content">

            <?php
            if( $game->have_posts() ) {
                while( $game->have_posts() ) : $game->the_post();
                    ?>
                    <h2><?php echo $post->post_title; ?></h2>
                    <h3>Write a Beat</h3>


                    <form action="" method="post" enctype="multipart/form-data">
                        <label for="beat_type">
                            Beat Type<br />
                            <select name="beat_type">
                                <option value="preview">Preview</option>
                                <option value="recap">Recap</option>

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

                <?php
                endwhile;
                wp_reset_postdata();
            } ?>
            </div>

        </div>
    </div>

<?php get_footer(); ?>
