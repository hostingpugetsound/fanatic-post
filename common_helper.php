<?php

/* 
This is to store general functions being used in website overall. This file is included in functions.php
 */

//Get League of Team
function get_team_league($team_id) {
    
    $team = get_post($team_id);
    //GetLeague
    $league = get_connected_items_for_team('team_to_league', $team);
    
    if(!$league)
    {
        //Get Division
        $division = get_connected_items_for_team('team_to_division', $team);
        
        if($division)
        {
            //Get Conference from division
            $conference = get_connected_items_for_team('division_to_conference', $division);
        } else
        {
            //Get Conference from team
            $conference = get_connected_items_for_team('team_to_conference', $team);
        }
        
        if($conference)
        {
            //Get League from conference
            $league = get_connected_items_for_team('conference_to_league', $conference);
        }
    }
    
    return $league;
}

function count_team_games($team_id) {

    $connected = new WP_Query(
            array(
                'connected_type' => 'games_to_teams',
                'connected_items' => get_post($team_id),
                'post_type' => 'game',
                'nopaging' => true
            )
    );

    return $connected->post_count;
}

//This function written to count beats for a team, it first gets all the bets of the team, then filters the distinct games and return counts
function count_team_beats($team_id) {
    
    $game_ids = array();
    
    $beats = new WP_Query(
            array(
                'nopaging' => true,
                'post_type' => 'game_beat',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'team-id',
                        'value' => $team_id,
                        'compare' => '=',
                    ),
                ),
            )
    );
    
    foreach($beats->posts as $beat){
        $game_id = get_post_meta($beat->ID, 'game-id', 1);

        if($game_id)
        {
            $game_ids[] = $game_id;
        }
    }
    
    return count(array_unique($game_ids));
}

//For admin games filter

function fagf_get_connected_items($connection_name, $po, $all = FALSE)
{
    $connected_items = new WP_Query(array(
        'connected_type' => $connection_name,
        'connected_items' => $po,
        'nopaging' => true
    ));

    if($connected_items->post_count > 0)
    {
        return ($all)? $connected_items->posts :  $connected_items->posts[0];
    }
    return FALSE;
}

function fagf_gather_teams_titles($teams)
{
    $team_names = array();
    foreach($teams as $team):
        $team_names[] = $team->post_title;
    endforeach;
    
    return array_unique($team_names);
}

function fagf_get_teams($id)
{
    
    $po = get_post($id);
    
    $teams_found = array();
    $team_names = array();
    $post_type = get_post_type($po);
    
    if($post_type == 'conference')
    {
        $conference = $po;
    } else if($post_type == 'league') {
        $league =  $po;
    }
    
    if(isset($league) && $league)
    {
        $conference = fagf_get_connected_items('conference_to_league', $league);
        $teams = fagf_get_connected_items('team_to_league', $league, TRUE);
        
        if($teams)
        {
            $teams_found = array_merge($teams_found, $teams);
        }
    }
    
    if(isset($conference) && $conference)
    { 
        $division = fagf_get_connected_items('division_to_conference', $conference);
        $teams = fagf_get_connected_items('team_to_conference', $conference, TRUE);
        
        if($teams)
        {
            $teams_found = array_merge($teams_found, $teams);
        }
    }
    
    if($division)
    {
        $teams = fagf_get_connected_items('team_to_division', $division, TRUE);
        
        if($teams)
        {
            $teams_found = array_merge($teams_found, $teams);
        }

    }
    
    return $teams_found;
}