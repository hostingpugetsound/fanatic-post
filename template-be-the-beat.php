<?php

// =============================================================================
// TEMPLATE NAME: Be the Beat
// =============================================================================

global $wpdb;

get_header();



$date_format = 'Y-m-d';

$args = [
    'post_type' => 'game',
    'posts_per_page' => 20,
    'order'      => 'ASC',
    'orderby' => 'meta_value_num',
    'meta_key' => 'wpcf-game-date',
    'meta_type' => 'DATE',
    'meta_query' => array(
        array(
            'key'     => 'wpcf-game-date',
            'value'   => strtotime( date($date_format) ),
            'compare' => '>=',
        ),
    ),
];

$games = new WP_Query( $args );

$games_with_beats_ids = $wpdb->get_col( "SELECT DISTINCT p2p_to FROM " . $wpdb->prefix . "p2p WHERE p2p_type = 'game_beat_to_game' " );



/* Recommended */
$favorites = get_user_meta( get_current_user_id(), "favorites", false );

#all games of favorite teams that do not have a beat
$games_favorites = $wpdb->get_col( sprintf(
    "SELECT DISTINCT p2p_from FROM " . $wpdb->prefix . "p2p WHERE p2p_type = 'games_to_teams' AND p2p_to IN (%s) AND p2p_from NOT IN (%s) ",
    implode( ', ', $favorites ),
    implode(', ', $games_with_beats_ids )
) );

#vard($games_favorites);
#die();

$args2 = $args;
$args2['post__in'] = $games_favorites;
$args2['meta_query'][] = [
    'key'     => 'wpcf-game-date',
    'value'   => strtotime( date($date_format) ),
    'compare' => '>=',
];


# get
#$sql = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "p2p WHERE p2p_from IN (%s) ", implode($favorites) );
/*
$games = $wpdb->get_results( $wpdb->prepare(
    "SELECT * FROM $wpdb->posts WHERE post_type = 'game' && ID IN (%s)",
    implode($favorites)
) );
*/
$games_recommended = new WP_Query( $args2 );
#vard($games_recommended->get_posts() );
#die();


/*  trending */
$args2 = $args;
$args2['post__in'] = $games_with_beats_ids;

$games_trending = new WP_Query( $args2 );
#vard( $args2 );
vard( $wpdb->last_query);
vard($games_trending->get_posts());



function games_list_view( WP_Query $query ) {
    $seperator = is_page_template('template-be-the-beat.php') ? ' - ' : '<br />';
    ?>
    <section class="beat-widget">
        <?php
        $current_date = 0;
        if( $query->have_posts() ) :
            while( $query->have_posts() ) : $query->the_post();
                $i = 0;
                $game_date = get_post_meta( get_the_ID(), 'wpcf-game-date', true );
                $game_time = get_post_meta( get_the_ID(), 'wpcf-game-type', true );
                $away_team = get_post_meta( get_the_ID(), 'wpcf-away-team-name', true );
                $home_team = get_post_meta( get_the_ID(), 'wpcf-home-team-name', true );

                if( $current_date != $game_date ) {
                    $current_date = $game_date;
                    echo sprintf( '<h3>%s</h3>', date('M j Y', $game_date) );
                }


                if( is_user_logged_in() )
                    $button = '<a class="btn btn-primary right" href="' . home_url() . '/write-a-beat/?game=' . get_the_ID() . '">Be The Beat</a>';
                else
                    $button = '<a class="btn btn-primary right" href="' . home_url() . '/create-a-account">Sign Up</a>';

                echo sprintf(
                    '<div class="game"><a href="%s">%s @ %s</a> <time>%s</time> (500 pts) %s</div>',
                    get_the_permalink(),
                    $away_team,
                    $home_team,
                    $game_time,
                    $button

                );

            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </section>
    <?php
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css" />

    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width">
            <div class="x-column x-sm x-1-1 content be-the-beat">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">All</a></li>
                        <li><a href="#tabs-2">Recommended</a></li>
                        <li><a href="#tabs-3">Trending</a></li>
                    </ul>
                    <div id="tabs-1"><?php games_list_view( $games ); ?></div>
                    <div id="tabs-2"><?php games_list_view( $games_recommended); ?></div>
                    <div id="tabs-3"><?php games_list_view( $games_trending ); ?></div>

                </div>
            </div>

        </div>
    </div>

<?php get_footer(); ?>
