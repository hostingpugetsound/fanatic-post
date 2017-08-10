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


function bt_class_postfix($team_id, $beat_type)
{
    return $beat_type . '_' . $team_id;
}


function get_sns_url($user_id, $snsname)
{
    $sns_url = false;

    $sns_meta_value = get_user_meta($user_id, $snsname, 1);

    if($sns_meta_value)
    {
        //check if it is already a url
        if(strpos($sns_meta_value, 'http') || strpos($sns_meta_value, 'wwww.'))
        {
            $sns_url = $sns_meta_value;
        } elseif($snsname == 'facebook')
        {
            $sns_url = "http://facebook.com/" . $sns_meta_value;
        } elseif($snsname == 'twitter')
        {
            $sns_url = "http://twitter.com/" . $sns_meta_value;
        } elseif($snsname == 'google_plus')
        {
            $sns_url = "http://plus.google.com/+" . $sns_meta_value;
        }
    }
    return $sns_url;

}

function get_beat_distinct_url($game_id, $beat, $ref_id, $vtype)
{
    return get_site_url() . '/game/' . $game_id . '/?ref='. $_GET['ref'] .'&tid=' . $ref_id . '&vtype=' . $vtype;

    /*
    if($beat)
    {
        $link = get_permalink($beat->ID);

        if(isset($_GET['ref']) && !empty($_GET['ref']))
        {
            $link .= '?ref='. $_GET['ref'];
        }

        return $link;
    } else
    {
        return get_site_url() . '/game/' . $game_id . '/?ref='. $_GET['ref'] .'&tid=' . $ref_id . '&vtype=' . $vtype;
    }
    */
}

function get_active_class($ref_id, $vtype)
{
    global $ref_team_id;
    global $team_view_type;

    if($team_view_type == $vtype && $ref_id == $ref_team_id)
    {
        return ' active current';
    }

    return false;
}
