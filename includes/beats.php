<?php

/**
 * functions pulled from template game/game-beat
 */
function get_team_beat($team_beats, $team_id, $type)
{
    if(isset($team_beats[$team_id][$type]))
    {
        return $team_beats[$team_id][$type];
    }

    return false;
}

$connectedBeats = new WP_Query(
    array(
        'connected_type' => 'game_beat_to_game',
        'connected_items' => get_post(),
        'nopaging' => true,
        'post_type' => 'game_beat',
        'orderby' => 'date',
        'order' => 'asc',
        'post_status' => 'publish'
    )
);

$game_beats_parsed = array();

if($connectedBeats->post_count > 0)
{
    foreach($connectedBeats->posts as $beat)
    {
        $beat->team_id   = get_post_meta($beat->ID, 'team-id', 1);
        $beat->beat_type = get_post_meta($beat->ID, 'beat-type', 1);

        $game_beats_parsed[$beat->team_id][$beat->beat_type] = $beat;
    }
}

// Find connected pages

$connectedTeams = new WP_Query(
    array(
        'connected_type' => 'games_to_teams',
        'connected_items' => get_post(),
        'nopaging' => true
    )
);

function get_team_link($id){

    if($id && !empty($id))
    {
        return get_permalink($id);;
    }

    return "javascript:void(0)";
}

function get_team_beat_page_link($id)
{
    return get_site_url() . '/game/?t=' . get_post($id)->post_name . '&tid=' . $id;
}

function get_team_id($teamName, $teamSlug, $connectedTeams){

    foreach($connectedTeams->posts as $team)
    {
        if(strtolower(trim($teamSlug)) == strtolower(trim($team->post_name)))
        {
            return $team->ID;
        } else if(strtolower(trim($teamName)) == strtolower(trim($team->post_title)))
        {
            return $team->ID;
        }
    }

    return false;
}
