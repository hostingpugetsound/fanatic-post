<?php

// =============================================================================
// VIEWS/GLOBAL/_BANNER.PHP
// -----------------------------------------------------------------------------
// Outputs the h1 tags/banner.
// =============================================================================

$navbar_position = x_get_navbar_positioning();
$logo_nav_layout = x_get_logo_navigation_layout();
$is_one_page_nav = x_is_one_page_navigation();

global $current_user;
//wp_get_current_user();


# show banner of all teams if on league page
if ( is_singular('league') ) {
?>
<section class="x-main full banner-teams">
    <div class="x-content-band man">
        <div class="x-container max width">
        <?php


        $teams = new WP_Query( array(
            'connected_type' => 'team_to_league',
            'connected_items' => get_the_ID(),
            'order' => 'ASC',
            'orderby' => 'post_title',
            #'post_type' => 'team',
            'nopaging' => true,
        ) );
        $teams = $teams->get_posts();


        #$teams = fagf_get_teams(get_the_ID());

        foreach( $teams as $team )
            echo fsu_team_circle( $team->ID, false );
        ?>
        </div>
    </div>
</section>
<?php } ?>

<?php if ( is_front_page() ) { ?>
    <section class="homeSlider">
        <div class="sliderContainer">
            <div class="x-column x-sm x-1-3 fanBox animated fadeInLeft">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/framework/img/global/fan.jpg" />
            </div>

            <div class="x-column x-sm x-1-3 sliderText">
                <h1>BE THE BEAT</h1>
                <h2>RAISE YOUR VOICE, report on the game, <br />represent your team.</h2>
                <a href="/be-the-beat/" class="btn btn-primary">Find Games Now</a>

            </div>
            <div class="x-column x-sm x-1-3 foeBox animated fadeInRight">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/framework/img/global/foe.jpg" />
            </div>
        </div>
    </section>
<?php
} elseif( is_singular( 'game' ) ) {

    global $reverse_teams, $awayTeamNameLink, $awayTeamName, $awayTeamLink, $awayScore,
           $homeTeamNameLink, $homeTeamName, $homeTeamLink, $homeScore, $date, $gameType;

    if ( has_post_thumbnail( $post->ID ) ) {
        $style = sprintf( ' style="background-image: url(%s);"', get_the_post_thumbnail_url( $post->ID, 'banner-long' ) );
    } else {
        $style = '';
    }

    ?>
    <section class="x-main full banner game-banner" <?php echo $style; ?>>
        <div class="x-content-band man">
            <div class="x-container max width">
                <h1><?php
                    $awayScore2 = maybe_echo_score($awayScore) ? ' (' . maybe_echo_score($awayScore) . ') ' : '';
                    $homeScore2 = maybe_echo_score($homeScore) ? ' (' . maybe_echo_score($homeScore) . ') ' : '';
                    if(($reverse_teams) ) {
                        echo $awayTeamNameLink . $awayScore2 . ' at ' . $homeTeamNameLink . $homeScore2;
                    } else {
                        echo $homeTeamNameLink . $homeScore2 . ' vs ' . $awayTeamNameLink . $awayScore2;
                    }
                ?></h1>
                <h2><?php echo $date; echo !empty($gameType)? " - " . $gameType : ""; ?></h2>
            </div>
        </div>
    </section>

<?php } elseif ( is_page( 'profile' ) || is_page( 'my-teams' ) || is_page( 'write-a-beat' ) ) { ?>
    <section class="x-main full banner blank"></section>
<?php } else if ( is_page() || is_archive() || is_singular('league') || is_singular('team') ) { ?>
    <section class="x-main full banner">
        <div class="x-content-band man">
            <div class="x-container max width">
                <?php if( is_singular('team') ) : ?>
                    <span class="teamHeading"><a class="pull-right" id="favThis" data-id="<?php the_ID(); ?>"><i class="fa fa-plus"></i> Add to Favorites</a></span>
                <?php endif; ?>
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </section>

<?php } else { ?>
    <section class="x-main full banner blank"></section>
<?php } ?>
