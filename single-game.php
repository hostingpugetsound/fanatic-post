<?php

// =============================================================================
// single-game.PHP
// -----------------------------------------------------------------------------
// Handles output of a single game/beat
// =============================================================================

global $ref_team_id, $team_view_type;


$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

#$beatID = get_the_ID();
#$gameID = get_post_meta($beatID, 'game-id', 1);
$gameID = get_the_ID();

$homeTeamName = get_post_meta( $gameID, 'wpcf-home-team-name', 1 );
$awayTeamName = get_post_meta( $gameID, 'wpcf-away-team-name', 1 );

$homeTeamSlug = get_post_meta( $gameID, 'wpcf-home-team', 1 );
$awayTeamSlug = get_post_meta( $gameID, 'wpcf-away-team', 1 );


$gamedate = get_post_meta( $gameID, 'wpcf-game-date', 1 );
$date = date( "D F j, Y", $gamedate );

$homeScore = get_post_meta( $gameID, 'wpcf-home-team-score', 1 );
$awayScore = get_post_meta( $gameID, 'wpcf-away-team-score', 1 );

$gameType = get_post_meta( $gameID, 'wpcf-game-type', 1 );


$connectedBeats = new WP_Query(
    array(
        'connected_type' => 'game_beat_to_game',
        'connected_items' => get_post( $gameID ),
        'nopaging' => true,
        'post_type' => 'game_beat',
        'orderby' => 'date',
        'order' => 'asc',
        'post_status' => 'publish'
    )
);

$game_beats_parsed = array();

if( $connectedBeats->post_count > 0 ) {
    foreach( $connectedBeats->posts as $beat ) {
        $beat->team_id = get_post_meta( $beat->ID, 'team-id', 1 );
        $beat->beat_type = get_post_meta( $beat->ID, 'beat-type', 1 );

        $game_beats_parsed[$beat->team_id][$beat->beat_type] = $beat;
    }
}

// Find connected pages

$connectedTeams = new WP_Query(
    array(
        'connected_type' => 'games_to_teams',
        'connected_items' => get_post( $gameID ),
        'nopaging' => true
    )
);


$homeTeamID = get_team_id( $homeTeamName, $homeTeamSlug, $connectedTeams );
$awayTeamID = get_team_id( $awayTeamName, $awayTeamSlug, $connectedTeams );

$homeTeamLink = get_team_link( $homeTeamID );;
$awayTeamLink = get_team_link( $awayTeamID );

$homeTeamPreview = get_team_beat( $game_beats_parsed, $homeTeamID, 'preview' );
$homeTeamRecap = get_team_beat( $game_beats_parsed, $homeTeamID, 'recap' );

$awayTeamPreview = get_team_beat( $game_beats_parsed, $awayTeamID, 'preview' );
$awayTeamRecap = get_team_beat( $game_beats_parsed, $awayTeamID, 'recap' );

//Referrer team ID
$reverse_teams = (isset( $_GET['ref'] ) && $_GET['ref'] == $homeTeamID) ? FALSE : TRUE;


if( isset( $_GET['tid'] ) && $_GET['tid'] ) {
    $ref_team_id = $_GET['tid'];
} else {
    $ref_team_id = ($reverse_teams) ? $awayTeamID : $homeTeamID;
}

$team_view_type = (isset( $_GET['vtype'] ) && $_GET['vtype'] == 'recap') ? 'recap' : 'preview';


?>

<?php get_header(); ?>
<script>
    var pageType = 'team';
</script>

<div class="<?php x_main_content_class(); ?>" role="main">
    <div class="x-container max width offset">

        <div class="x-column x-sm x-1-2 content">
            <h2 class="red-header">The Beat</h2>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php #x_get_view( x_get_stack(), 'template', 'game' ); ?>
                <?php require dirname(__FILE__) . '/framework/views/integrity/template-game.php'; ?>
                <?php #x_get_view( 'integrity', 'content', 'page' ); ?>
            <?php endwhile; ?>
        </div>

        <div class="x-column x-sm x-1-2 last comments">
            <h2 class="red-header">The Arena</h2>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php x_get_view( 'global', '_comments-template' ); ?>
            <?php endwhile; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>
