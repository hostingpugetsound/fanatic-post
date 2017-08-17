<?php

// =============================================================================
// TEMPLATE NAME: Be the Beat
// =============================================================================

global $wpdb;

get_header();



$date_format = 'Y-m-d';

$default_args = [
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

$games = new WP_Query( $default_args );

$games_with_beats_ids = $wpdb->get_col( "SELECT DISTINCT p2p_to FROM " . $wpdb->prefix . "p2p WHERE p2p_type = 'game_beat_to_game' " );



/* Recommended */
$favorites = get_user_meta( get_current_user_id(), "favorites", false );

#all games of favorite teams that do not have a beat
$games_favorites = $wpdb->get_col( sprintf(
    "SELECT DISTINCT p2p_from FROM " . $wpdb->prefix . "p2p WHERE p2p_type = 'games_to_teams' AND p2p_to IN (%s) AND p2p_from NOT IN (%s) ",
    implode( ', ', $favorites ),
    implode(', ', $games_with_beats_ids )
) );


$args = $default_args;
$args['post__in'] = $games_favorites;
$args['meta_query'][] = [
    'key'     => 'wpcf-game-date',
    'value'   => strtotime( date($date_format) ),
    'compare' => '>=',
];


$games_recommended = new WP_Query( $args );


/*  trending */
$args = $default_args;
$args['post__in'] = $games_with_beats_ids;

$games_trending = new WP_Query( $args );




function games_list_view( WP_Query $query ) {
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
                    $button = '<div class="beatholder"><a class="btn btn-primary right" href="' . home_url() . '/write-a-beat/?game=' . get_the_ID() . '">Be The Beat</a></div>';
                else
                    $button = '<div class="beatholder"><a class="btn btn-primary right" href="' . home_url() . '/join/">Sign Up</a></div>';

                echo sprintf(
                    '<div class="game"><div class="innergame"><a href="%s">%s @ %s</a> <time>%s</time> (500 pts)</div> %s</div>',
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

<script>
    jQuery(document).ready(function ($) {
        $("#tab-links a").click(function (e) {
            $("#tab-links a").removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
            var tab = $(this).data('tab');
            $('#tabs > div.tab:visible').fadeOut();
            $('#tabs #' + tab).fadeIn();
        });
    });
</script>
    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width">
            <div class="x-column x-sm x-1-1 content be-the-beat">
                <ul id="tab-links" class="inline">
                    <li><a href="#" data-tab="tabs-1" class="active">All</a></li>
                    <li><a href="#" data-tab="tabs-2">Recommended</a></li>
                    <li><a href="#" data-tab="tabs-3">Trending</a></li>
                </ul>
                <div id="tabs">
                    <div id="tabs-1" class="tab"><?php games_list_view( $games ); ?></div>
                    <div id="tabs-2" class="tab" style="display: none;"><?php games_list_view( $games_recommended); ?></div>
                    <div id="tabs-3" class="tab" style="display: none;"><?php games_list_view( $games_trending ); ?></div>

                </div>
            </div>

        </div>
    </div>

<?php get_footer(); ?>
