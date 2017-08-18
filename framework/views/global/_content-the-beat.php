<?php

// =============================================================================
// VIEWS/GLOBAL/_CONTENT-THE-BEAT.PHP
// -----------------------------------------------------------------------------
// Page for outputting "The Beat"
// =============================================================================


$args = array(
    #'connected_type' => 'game_beat_to_game',
    #'connected_items' => get_post($game_id),
    #'nopaging' => true,
    'post_type' => 'game_beat',
    'post_status' => 'publish',
    'posts_per_page' => 7,
    'orderby' => 'date',
    /*
    'meta_query' => array(
        array(
            'key'     => 'team-id',
            'value'   => $this_team_id,
            'compare' => '=',
        ),
        array(
            'key'     => 'beat-type',
            'value'   => 'preview',
            'compare' => '=',
        ),

    ),
    */
);



if( is_front_page() ) {
    $query = new WP_Query( $args );
} elseif( is_singular('league') ) {
    #$args['connected_type'] = 'games_to_teams';
    #$args['connected_items'] = get_the_ID();
    #$args['nopaging'] = true;


    $team_ids = [];
    $teams = fsu_get_teams_from_league( get_the_ID() );
    foreach( $teams as $team )
        $team_ids[] = $team->ID;

    $args['meta_query'] = array(
        array(
            'key'     => 'team-id',
            'value'   => $team_ids,
            'compare' => 'IN',
        ),
    );

    $query = new WP_Query( $args );
} elseif( is_singular('team') ) {
    /*
    $args['meta_query'] = array(
        array(
            'key'     => 'team-id',
            'value'   => get_the_ID(),
            'compare' => 'IN',
        ),
    );

    $query = new WP_Query( $args );
    */
} else {
    $query = $wp_query;
}
?>

    <h2 class="red-header">The Beat</h2>
<?php
#vard($query);
if ( isset($query) && $query->have_posts() ) :
    $i = 0;
    while ( $query->have_posts() ) :
        $query->the_post();

        $game = get_post( get_post_meta( $post->ID, 'game-id', true) );
        $i++;
        # adds the 1/2 columns and 'last' class
        if( $i > 1 ) {
            $class = 'x-column x-sm x-1-2';
            if( $i % 2 != 0 )
                $class .= ' last';
        } else {
            $class = '';
        }
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
            <?php
            #x_featured_image();
            $image_size = $i == 1 ? 'home-long' : 'home-short';
            if ( has_post_thumbnail( $post->ID ) ) {
                $style = sprintf( ' style="background-image: url(%s);"', get_the_post_thumbnail_url( $post->ID, $image_size ) );
            } else {
                $style = '';
                $image_size .= ' no-image';
            }
            ?>
            <div class="entry-featured <?php echo $image_size; ?>" <?php echo $style; ?>>
                <h3 class="entry-title">
                    <a href="<?php echo get_the_permalink( $game->ID ); ?>" title="<?php echo esc_attr( get_the_title( $game->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $game->ID ) ); ?>   
                    </a>
                    <time><?php echo get_post_meta( $game->ID, 'wpcf-game-info', true ); ?></time>
                </h3>
            </div>
            <div class="entry-wrap">
                <div class="author">
                    <?php
                    $user_id = get_the_author_meta( 'ID' );
                    $user = get_user_by( 'id', $user_id );
                    echo sprintf( 'Posted by <a href="%s">@%s</a> - %s',
                        home_url() . '/profile/' . $user->user_login,
                        $user->user_login,
                        date( 'm/d/Y', strtotime($post->post_date) )
                    );
                    ?>
                </div>
                <?php if ( is_singular() ) : ?>
                    <?php if ( $disable_page_title != 'on' ) { ?>
                        <header class="entry-header">
                        </header>
                    <?php } ?>
                <?php else : ?>
                    <header class="entry-header">
                        <h2 class="entry-title">
                        </h2>
                    </header>
                <?php endif; ?>
                <div class="entry-content content">
                    <?php echo get_the_excerpt(); ?>
                </div>
                <?php #x_get_view( 'global', '_content' ); ?>
            </div>
        </article>

    <?php

        # horizontal ad
        if( $i == 3 )
            x_get_view( 'global', '_ad' );

        # vertical ad
        /*
        if( $i == 4 ) {
            echo '<article class="x-column x-sm x-1-2 last">';
            x_get_view( 'global', '_ad' );
            echo '</article>';
            $i++;
        }
        */
    endwhile;
    ?>
    <?php wp_reset_postdata(); ?>


<?php elseif ( is_singular('team') ) : ?>
    <div class="team-banner">
        <h2>BE THE BEAT</h2>
        <h3>RAISE YOUR VOICE, report on the game, <br />represent your team.</h3>
        <a href="#" class="btn btn-primary">Find Games Now</a><br />

        <div class="x-column x-sm x-1-2 fanBox">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/framework/img/global/fan.jpg" />
        </div>
        <div class="x-column x-sm x-1-2 last foeBox">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/framework/img/global/foe.jpg" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="ad full">
        <?php x_get_view( 'global', '_ad' ); ?>
    </div>
    <div class="x-column x-sm x-1-2 ad">
        <?php x_get_view( 'global', '_ad' ); ?>
    </div>
    <div class="x-column x-sm x-1-2 last ad">
        <?php x_get_view( 'global', '_ad' ); ?>
    </div>
<?php endif; ?>
