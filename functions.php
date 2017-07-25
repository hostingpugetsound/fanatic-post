<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to X in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Enqueue Parent Stylesheet
//   02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );



// Additional Functions
// =============================================================================

function custom_excerpt_length( $length ) {
        return 20;
    }
    add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function format_beat_content($content, $title = FALSE)
{
    remove_filter('the_content', 'show_share_buttons');
    
    $content = apply_filters( 'the_content', $content );
    return str_replace( ']]>', ']]&gt;', $content );
}
    
//For Team Artciles
add_filter( 'gform_confirmation_1', 'confirm_publish_article', 10, 4 );

//For Player Articles
add_filter( 'gform_confirmation_2', 'confirm_publish_article', 10, 4 );

function confirm_publish_article( $confirmation, $form, $entry, $ajax )
{
    global $current_user;

    //Remove action, so post does not get duplicated.
    remove_action( 'save_post', 'set_articles_to_teams' );
    
    if(isset($_POST['submit']) && $_POST['submit'] == 'Preview')
    {
        // Unpublish article
        $my_post = array(
            'ID'           => $entry['post_id'],
            'post_status'   => 'draft'
        );

        wp_update_post( $my_post );
        
        echo get_permalink($entry['post_id']);
        
        wp_redirect(get_permalink($entry['post_id']). '&preview=1');
    } else
    {
        $prepay = get_option('articleprepayment' . $current_user->ID);
        
        // Publish article
        $my_post = array(
            'ID'           => $entry['post_id'],
            'post_status'   => $prepay? 'publish' : 'draft'
        );

        $updated = wp_update_post( $my_post );

        if($prepay && $updated)
        {
            delete_option('articleprepayment' . $current_user->ID);
            $confirmation = "Thank you, your post has been published.";
        } else
        {
            $confirmation = "Your post could not be published.";
        }
        
    }
    
    
    if(isset($_GET['pid']))
    {
        $people = get_post($_GET['pid']);

        if($people)
        {
            
        $people_url = get_site_url() . '/article/?p='.$people->post_name . '&ptype=' . $people->post_type;
        
        $confirmation .= "
                <br />
                <a href='". $people_url ."'>Return to " . $people->post_title ." Articles Page</a>

                <style>
                    .entry-header {
                        display:none;
                    }

                    .entry-wrap {
                        text-align:center;
                    }
                </style>
                ";
            
        }
    } elseif(isset($_GET['tid']))
    {
        $team = get_post($_GET['tid']);

        if($team)
        {
            
        $team_url = get_site_url() . '/article/?t='.$team->post_name;
        
        $confirmation .= "
                <br />
                <a href='". $team_url ."'>Return to " . $team->post_title ." Articles Page</a>

                <style>
                    .entry-header {
                        display:none;
                    }

                    .entry-wrap {
                        text-align:center;
                    }
                </style>
                ";
            
        }
    } 
 
    return $confirmation;
    
}

function deactivate_previous_beats($game_id, $team_id, $beat_type)
{
    $connectedBeats = new WP_Query(
        array(
                'connected_type' => 'game_beat_to_game',
                'connected_items' => $game_id,
                'nopaging' => true,
                'post_type' => 'game_beat',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
			'key'     => 'team-id',
			'value'   => $team_id,
			'compare' => '=',
                    ),
                    array(
			'key'     => 'game-id',
			'value'   => $game_id,
			'compare' => '=',
                    ),
                    array(
			'key'     => 'beat-type',
			'value'   => $beat_type,
			'compare' => 'LIKE',
                    )
                )
         )
    );
    
    foreach($connectedBeats->posts as $beat):

        if($beat->post_type == 'game_beat')
        {
            // Unpublish Beat
            wp_update_post( array(
                'ID'           => $beat->ID,
                'post_status'   => 'draft'
            ) );
        }

    endforeach;

}

//To customize Beatpost gravity forms confirmation
add_filter( 'gform_confirmation_3', 'gf_confirmation_for_beatpost', 10, 4 );
function gf_confirmation_for_beatpost( $confirmation, $form, $entry, $ajax )
{
    global $current_user;
    
    $beatwriter_game_id = get_post_meta($entry['post_id'], 'game-id', 1);
    $beatwriter_team_id = get_post_meta($entry['post_id'], 'team-id', 1);
    $beatwriter_user_id = get_post_meta($beatwriter_game_id, 'beatwriter_user_team'.$beatwriter_team_id, 1);
    $beat_type = get_post_meta($entry['post_id'], 'beat-type', 1);
    $ref_team_id = get_post_meta($entry['post_id'], 'ref-team-id', 1);
    $existing_beat_id = get_post_meta($entry['post_id'], 'existing-beat-id', 1);
    
    $beat_id = $entry['post_id'];
    
    //Remove action, so post does not get duplicated.
    //remove_action( 'save_post', 'set_articles_to_teams' );

    //Is user authorised to publish this beat?
    $user_authorised = ($beatwriter_user_id == $current_user->ID);
    
    if($user_authorised && !$existing_beat_id)
    {
        deactivate_previous_beats($beatwriter_game_id, $beatwriter_team_id, $beat_type);
        
        // Publish/Unpublish Beat
        $my_post = array(
            'ID'           => $entry['post_id'],
            'post_status'   => ($user_authorised)? 'publish' : 'draft'
        );
    } else if($user_authorised && $existing_beat_id)
    {
        $my_post = array(
            'ID'           => $existing_beat_id,
            'post_content'   => get_post_field( 'post_content', $entry['post_id'], 'raw' )
        );
        
        $beat_id = $existing_beat_id;
    }
    
    $updated = wp_update_post( $my_post );
    
    if( $user_authorised && $updated )
    {
        $beat_url = get_permalink($beat_id);
        
        if($ref_team_id) $beat_url .= "?ref=" . $ref_team_id;
        
        return $confirmation = array('redirect' => $beat_url);
   
    } else
    {
        $game_url = get_site_url() . '/game/' . $beatwriter_game_id . '?ref=' . $ref_team_id . '&vtype=' . $beat_type;
        
        return $confirmation = "
                Your beat could not be published.
                <br />
                <a href='". $game_url ."'> Go Back</a>

                <style>
                    .entry-header {
                        display:none;
                    }

                    .entry-wrap {
                        text-align:center;
                    }
                </style>
                ";
    }
}

function publish_this_article( $confirmation, $form, $entry, $ajax ) {
    
    // Unpublish article array
    $my_post = array(
        'ID'           => $entry['post_id'],
        'post_status'   => 'draft'
    );

    // Update the post into the database
    remove_action( 'save_post', 'set_articles_to_teams' );
    wp_update_post( $my_post );
    
    $pp_app_base_url = get_site_url();

    $current_url = $pp_app_base_url . '/article/?t=' . $_GET['t'];

    $notify_url = $pp_app_base_url . '/paypal.php?action=articlepublishnotify&password=7412581&current_url=' . $current_url;
    $return_url =  $pp_app_base_url . '/paypal.php?action=articlepublishsuccess&password=741258&current_url=' . $current_url;
    $cancel_url =  $pp_app_base_url . '/paypal.php?action=articlepublishcancel&current_url=' . $current_url;

    include_once ABSPATH . 'pp_config.php';
    
    $confirmation = "Thank you, your post has been received.";

    $confirmation .= '<form action="'.PAYPAL_URL.'" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="'.PAYPAL_EMAIL.'">
        <input type="hidden" name="item_name" value="Promote Comment">
        <input type="hidden" name="item_number" value="'.$entry['post_id'].'">
        <input type="hidden" name="amount" value="5">
        <input type="hidden" name="tax" value="">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="address_override" value="1">
        <input type="hidden" name="notify_url" value="'.$notify_url.'">
        <input type="hidden" name="return" value="'.$return_url.'">
        <input type="hidden" name="cancel_return" value="'.$cancel_url.'">
        <input type="submit" name="submit" value="Pay $5 to Publish this Post">
    </form>';
    
    return $confirmation;
}

//add_action( 'user_register', 'update_adsense_info', 10, 1 );

function update_adsense_info( $user_id ) {

    
    $adClient = get_user_meta($user_id, 'adClient', true);
    $adSlot = get_user_meta($user_id, 'adSlot', true);
    $sidebarAdSlot = get_user_meta($user_id, 'sidebarAdSlot', true);

    if ($adClient && $adSlot && $sidebarAdSlot) {
      return; //adsense values already exist, no need to add/update
    }
    
    $filename = ABSPATH . 'ad_slot_ids.txt';

    $ad_slot_txt = file_get_contents($filename);

    $ad_slot_ids = explode("\n", $ad_slot_txt);

    if(isset($ad_slot_ids[0]))
    {
        $ads_id = $ad_slot_ids[0];
        unset($ad_slot_ids[0]);

        file_put_contents($filename, implode("\n", $ad_slot_ids));

        update_user_meta($user_id, 'sidebarAdSlot', $ads_id);
        update_user_meta($user_id, 'adSlot', $ads_id);
        update_user_meta($user_id, 'adClient', 'pub-6614460239177654');
    }

}

function disp_get_next_bd_item($connection_name, &$post, &$found, &$breadcrums_arr)
{
        $connected_items = new WP_Query(array(
            'connected_type' => $connection_name,
            'connected_items' => $post,
            'posts_per_page' => 1
        ));
        
        if($connected_items->post_count > 0)
        {
            //if($found) { echo " / ";} else { $found = TRUE; }
            
            //$team_id = $connected_items->posts[0]->ID;
            $post = $connected_items->posts[0];
            
            //echo '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
            $breadcrums_arr[] = '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
            
            return $connected_items->posts[0]->ID;
        }
        return FALSE;
}

function the_breadcrumb($post_id) {
    
    $post = get_post($post_id);

    if($post->post_type == 'player')
    {
        $player_id = $post->ID;
    }
    else if($post->post_type == 'team')
    {
        $team_id = $post->ID;
    }
    else if($post->post_type == 'division')
    {
        $division_id = $post->ID;
    }
    else if ($post->post_type == 'conference' || $post->post_type == 'driver' || $post->post_type == 'golfer' || $post->post_type == 'event' || $post->post_type == 'ranking' 
            || $post->post_type == 'bout' || $post->post_type == 'tournament' || $post->post_type == 'race' || $post->post_type == 'match' )
    {
        $parent_of_league_id = $post->ID;;
    }
    
    $found = FALSE;
    $breadcrums_arr = array();
    
    if(isset($player_id) && $player_id)
    {
        //Display Team
        $team_id = disp_get_next_bd_item('players_to_team', $post, $found, $breadcrums_arr);
    }
    
    if(isset($team_id) && $team_id)
    {
        //Display Division
        $division_id = disp_get_next_bd_item('team_to_division', $post, $found, $breadcrums_arr);
    }
    
    if(isset($division_id) && $division_id)
    {
        //Display Conferences
        $conference_id = disp_get_next_bd_item('division_to_conference', $post, $found, $breadcrums_arr);
        
    } 
    else if(isset($team_id) && $team_id)
    {
        //Display Conferences
        $conference_id = disp_get_next_bd_item('team_to_conference', $post, $found, $breadcrums_arr);
    }
        
    if(isset($conference_id) && $conference_id)
    {
        //Display Leagues
        disp_get_next_bd_item('conference_to_league', $post, $found, $breadcrums_arr);
    }
    elseif(isset($player_id) && $player_id)
    {
        $parent_of_league_id = $player_id;
    }
    else if(isset($team_id) && $team_id)
    {
        //Display Conferences
        $parent_of_league_id = $team_id;
    }
    
    if(isset($parent_of_league_id) && $parent_of_league_id)
    {
        //Display Leagues
        disp_get_next_bd_item($post->post_type . '_to_league', $post, $found, $breadcrums_arr);
    }
    
    if( isset($player_id) || isset($team_id) || isset($division_id) || isset($parent_of_league_id) || $found) 
    {
        echo !empty($breadcrums_arr)? implode( " / ", array_reverse ($breadcrums_arr) ) : '';
        return;
    }
        
        
    
    //If post type does not have breadcumn connections    
    $parents = get_post_ancestors( $post_id );
    
    $count = 0;
    
    if(!empty($parents))
    {
        $parents = array_reverse($parents);
        
        foreach ($parents as $parent)
        {
            $post = get_post($parent);
            
            if($count != 0)
            {
                echo ' / ';
            }
            
            echo '<a href="' . get_permalink($post->ID). '">' . $post->post_title .'</a>';
            
            $count++;
        }
    }
        
    return;
    
}


function get_c2c_connected_items($connection_name, $post, $post_type = FALSE)
{
    $args = array(
        'connected_type' => $connection_name,
        'connected_items' => $post,
        'posts_per_page' => 1
    );
    
    if($post_type) $args['post_type'] = $post_type;
    
    $connected_items = new WP_Query($args);

    if($connected_items->post_count > 0)
    {
        return $connected_items->posts[0];
    }
    return FALSE;
}

function get_game_price($game_id, $team_id) {

    $game_beat_price = get_post_meta($game_id, 'wpcf-beat-price', 1);
    
    if($game_beat_price) return $game_beat_price;
    
    $team_beat_price = get_post_meta($team_id, 'wpcf-beat-price', 1);
    
    if($team_beat_price) return $team_beat_price;
    
    //Get Division
    $division = get_c2c_connected_items('team_to_division', get_post($team_id), 'division');
    
    if($division)
    {
        $division_beat_price = get_post_meta($division->ID, 'wpcf-beat-price', 1);
        
        if($division_beat_price) return $division_beat_price;
        
        //Get Conference from division
        $conference = get_c2c_connected_items('division_to_conference', $division, 'conference');
    } else
    {
            //Get Conference from team
            $conference = get_c2c_connected_items('team_to_conference', get_post($team_id), 'conference');
    }
    
    if($conference)
    {
        $conference_beat_price = get_post_meta($conference->ID, 'wpcf-beat-price', 1);    
        if($conference_beat_price) return $conference_beat_price;
        
        //Get League from conference
        $league = get_c2c_connected_items('conference_to_league', $conference, 'league');
    } else
    {
        //GetLeague from team
        $league = get_c2c_connected_items('team_to_league', get_post($team_id), 'league');
    }
    
    if($league)
    {
        $league_beat_price = get_post_meta($league->ID, 'wpcf-beat-price', 1);    
        if($league_beat_price) return $league_beat_price;
    }
    
    //otherwise return default price of 3
    return 3;
}

//This will show the score, event if the value is zero
function show_zero_score( $column, $post_id ) {
    if($column == 'wpcf-home-team-score' || $column == 'wpcf-away-team-score')
    {
        $score_value = get_post_meta($post_id, $column, 1);
        if($score_value == '0') echo $score_value;
    }
}

add_filter( 'manage_game_posts_custom_column' , 'show_zero_score', 10, 2 );

require_once('common_helper.php');


function gform_add_preview_button($button, $form )
{
    $button_html  = "<input type='submit' id='gform_submit_button_1' class='gform_button button' name='submit' value='Submit' tabindex='9' onclick='if(window[\"gf_submitting_1\"]){return false;}  window[\"gf_submitting_1\"]=true;' />";
    $button_html .= "<span>&nbsp;</span><input type='submit' id='gform_submit_button_1_2' class='gform_button button' name='submit' value='Preview' tabindex='9' onclick='if(window[\"gf_submitting_1\"]){return false;}  window[\"gf_submitting_1\"]=true;' />";
    
    return $button_html;
}

add_filter( 'gform_submit_button_1', 'gform_add_preview_button', 10, 2 );
add_filter( 'gform_submit_button_2', 'gform_add_preview_button', 10, 2 );

$glob_path = 'framework/functions/global';
require_once( $glob_path . '/navbar.php' );


/**
*
* Include Carbon Fields
*
**/

define('CRB_THEME_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);


add_action('wp_enqueue_scripts', 'crb_wp_enqueue_scripts');
function crb_wp_enqueue_scripts() {
    $template_dir = get_stylesheet_directory_uri();

    # Enqueue jQuery
    wp_enqueue_script('jquery');

    # Enqueue Custom JS files
    # @crb_enqueue_script attributes -- id, location, dependencies, in_footer = false
    crb_enqueue_script('theme-functions', $template_dir . '/js/functions.js', array('jquery'));

    # Enqueue Custom CSS files
    # @crb_enqueue_style attributes -- id, location, dependencies, media = all
    crb_enqueue_style('google-fonts', 'http://fonts.googleapis.com/css?family=Droid+Sans:400,700');
    // crb_enqueue_style('theme-styles', $template_dir . '/style.css');

    # Enqueue Comments JS file
    if (is_singular()) {
        wp_enqueue_script('comment-reply');
    }
}

# Load the debug functions early so they're available for all theme code
include_once(CRB_THEME_DIR . 'lib/debug.php');

# Attach Custom Post Types and Custom Taxonomies
add_action('init', 'crb_attach_post_types_and_taxonomies', 0);
function crb_attach_post_types_and_taxonomies() {
    # Attach Custom Post Types
    include_once(CRB_THEME_DIR . 'options/post-types.php');

    # Attach Custom Taxonomies
    include_once(CRB_THEME_DIR . 'options/taxonomies.php');
}

add_action('after_setup_theme', 'crb_setup_theme');

# To override theme setup process in a child theme, add your own crb_setup_theme() to your child theme's
# functions.php file.
if (!function_exists('crb_setup_theme')) {
    function crb_setup_theme() {
        # Make this theme available for translation.
        add_theme_support('menus');

        # Common libraries
        include_once(CRB_THEME_DIR . 'lib/common.php');
        include_once(CRB_THEME_DIR . 'lib/carbon-fields/carbon-fields.php');
        include_once(CRB_THEME_DIR . 'lib/carbon-validator/carbon-validator.php');
        include_once(CRB_THEME_DIR . 'lib/admin-column-manager/carbon-admin-columns-manager.php');

        # Attach Walkers
        include_once(CRB_THEME_DIR . 'options/walkers.php');

        # Attach custom shortcodes
        include_once(CRB_THEME_DIR . 'options/shortcodes.php');

        # Attach custom columns
        include_once(CRB_THEME_DIR . 'options/admin-columns.php');

        # Attach helper functions
        include_once(CRB_THEME_DIR . 'includes/helper-functions.php');
        
        # Add Actions
        add_action('carbon_register_fields', 'crb_attach_theme_options');
    }
}

function crb_attach_theme_options() {
    # Attach fields
    include_once(CRB_THEME_DIR . 'options/theme-options.php');
    include_once(CRB_THEME_DIR . 'options/custom-fields.php');
}

//This function will add connections for games, which dont have connections
function fsu_repair_games_connections()
{
    global $wpdb;
    
    $games = $wpdb->get_results("SELECT * FROM wp_posts LEFT JOIN wp_p2p ON wp_posts.ID=wp_p2p.p2p_from WHERE wp_posts.post_type = 'game' AND wp_posts.post_status = 'publish' AND wp_p2p.p2p_from is null", OBJECT);

    echo "<br />Total Games Found: " . sizeof($games);
    $fixed_count = 0;
    
    foreach($games as $game)
    {
        $home_team_slug = get_post_meta($game->ID, 'wpcf-home-team', true);
        $away_team_slug = get_post_meta($game->ID, 'wpcf-away-team', true);
        
        $home_team_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name LIKE '".$home_team_slug."' AND post_type='team'");
        $away_team_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name LIKE '".$away_team_slug."' AND post_type='team'");

        if(!$home_team_id || !$away_team_id)
        {
            $home_team_name = get_post_meta($game->ID, 'wpcf-home-team-name', true);
            $away_team_name = get_post_meta($game->ID, 'wpcf-away-team-name', true);
            
            $home_team_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_title LIKE '".$home_team_name."' AND post_type='team'");
            $away_team_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_title LIKE '".$away_team_name."' AND post_type='team'");
        }
        
        if($home_team_id && $away_team_id)
        {
            p2p_create_connection( 'games_to_teams',
                array(
                        'from' => $game->ID,
                        'to' => $home_team_id,
                        'meta' => array(
                                'date' => current_time('mysql')
                        )
                )
            );
        
            p2p_create_connection( 'games_to_teams',
                array(
                        'from' => $game->ID,
                        'to' => $away_team_id,
                        'meta' => array(
                                'date' => current_time('mysql')
                        )
                )
            );
            $fixed_count++;
            echo "<br />connections added for " . $game->ID;
        } else
        {
            echo "<br />connections failed for " . $game->ID;
        }
            
    }
    
    echo "<br />Total Games Fixed: " . $fixed_count;
    wp_die();
    
}

if(isset($_GET['repair_games_connections']))
{
    fsu_repair_games_connections();
}

function fsu_user_article_agreement_acceptance(){
    $user_id = get_current_user_id();
    
    if($user_id)
    {
        add_user_meta( $user_id, '_user_article_agreement_acceptance', 1, TRUE);
    }
    
    echo json_encode(array("success" => true));
    wp_die();
}

add_action( 'wp_ajax_user_article_agreement_acceptance', 'fsu_user_article_agreement_acceptance' );

function fsu_user_beats_agreement_acceptance(){
    $user_id = get_current_user_id();
    
    if($user_id)
    {
        add_user_meta( $user_id, '_user_beats_agreement_acceptance', 1, TRUE);
    }
    
    echo json_encode(array("success" => true));
    wp_die();
}

add_action( 'wp_ajax_user_beats_agreement_acceptance', 'fsu_user_beats_agreement_acceptance' );

register_nav_menus( array(
	'homepage' => 'homepage',
	
) );



add_action( 'wp_ajax_nopriv_favorite', 'favorite' );
add_action( 'wp_ajax_favorite', 'favorite' );

function favorite($action, $post_id) {
    	global $wpdb; // this is how you get access to the database

	$user_id = get_current_user_id();
    
    if ($user_id == 0) {
        echo json_encode(array(
        "success" => false,
        "message" => "Must be logged in to use favorites."
        ));
        exit();
    }
    
    $post_id = $_POST['post_id'];
    $favorites = get_user_meta($user_id, "favorites", false);

    if (!in_array($post_id, $favorites)) {
        add_user_meta($user_id, "favorites", $post_id);    
        echo json_encode(array(
            "success" => true,
            "user_id" => $user_id,
            "post_id" => $post_id,
            "status"  => true
        ));
            exit();

    } else {
        delete_user_meta($user_id, "favorites", $post_id);    
        echo json_encode(array(
            "success" => true,
            "user_id" => $user_id,
            "post_id" => $post_id,
            "status"  => false
        ));
            exit();

    }
    
}


add_action( 'wp_ajax_nopriv_fetch_favorite', 'fetch_favorite' );
add_action( 'wp_ajax_fetch_favorite', 'fetch_favorite' );

function fetch_favorite($action, $post_id) {
    	global $wpdb; // this is how you get access to the database

	   $user_id = get_current_user_id();
    
    if ($user_id == 0) {
        echo json_encode(array(
        "success" => false,
        "message" => "Must be logged in to use favorites."
        ));
        exit();
    }
    
    $post_id = $_POST['post_id'];
    $favorites = get_user_meta($user_id, "favorites", false);

    if (in_array($post_id, $favorites)) {
        echo json_encode(array(
            "success" => true,
            "user_id" => $user_id,
            "post_id" => $post_id,
            "status"  => true
        ));
        exit();

    } else {
        echo json_encode(array(
            "success" => true,
            "user_id" => $user_id,
            "post_id" => $post_id,
            "status"  => false
        ));
        exit();

    }    
  
    
    
}

add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

function fsu_get_post_by_name($name, $post_type) {
    $args = array(
        'name' => $name,
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => 1
    );

    $posts = get_posts($args);

    if ($posts) {
        return $posts[0];
    }

    return FALSE;
}

//insert FB meta tags for game_beat
function fsu_insert_fb_in_head()
{
    if(is_admin()) return;
    
    global $post;
    
    $pre_post_type = ($post)? $post->post_type : FALSE;
    
    $query_string = FALSE;
    
    if(isset($_GET['t']) && $_GET['t'])
    {
        $post = fsu_get_post_by_name($_GET['t'], 'team');
        
        if(!$post)
        {
            $post = fsu_get_post_by_name($_GET['p'], 'any');
        }
        
        $query_string = TRUE;
    } else if(isset($_GET['p']) && $_GET['p'])
    {
        $post = fsu_get_post_by_name($_GET['p'], 'player');
        
        if(!$post)
        {
            $temp_p_type = (isset($_GET['ptype']) && $_GET['ptype'])? $_GET['ptype'] : 'any';
            $post = fsu_get_post_by_name($_GET['p'], $temp_p_type);
        }
        
        $query_string = TRUE;
    }
    
    if(!$post) return;
    
    $og_title = htmlspecialchars($post->post_title);
    $og_type = $post->post_type;
    $og_url = get_permalink($post->ID);
    $og_image = 'http://fanaticpost.com/wp-content/themes/x-child/framework/img/global/fanaticPostLogo.png';
    $og_site_name = 'FanaticPost';
    $og_description = 'Fan, Meet Foe. Fanatics, fans and foes alike, can create a post, build their own brand and earn revenue with every successful post.';
    
    $thumb_id = get_post_thumbnail_id( $post->ID);
    
    if(!empty($thumb_id))
    {
        $image = wp_get_attachment_image_src( $thumb_id, 'full' );
        $og_image = isset( $image['0'] ) ? $image['0'] : $og_image;
    }
    
    $excerpt = get_the_excerpt( $post );
    
    if($excerpt) $og_description = htmlspecialchars ( substr($excerpt, 0, strpos($excerpt, '...')));
    
    if($post->post_type == 'game_beat' || $post->post_type == 'game')
    {
        if($post->post_type == 'game')
        {
            $gameID = $post->ID;
            $og_type = 'game';
            
        } else
        {
            $gameID = get_post_meta($post->ID, 'game-id', 1);
            $og_type = 'article';
        }
    
        $homeTeamName 				= get_post_meta($gameID, 'wpcf-home-team-name', 1);
        $awayTeamName 				= get_post_meta($gameID, 'wpcf-away-team-name', 1);
        
        $reverse_teams = (isset($_GET['ref']) && get_the_title($_GET['ref']) == $homeTeamName)? FALSE : TRUE;
        
        $gamedate             = get_post_meta($gameID, 'wpcf-game-date', 1);
        $date = date("D F j, Y", $gamedate);
        
        if($reverse_teams)
        {
            $og_title = $awayTeamName . ' at ' . $homeTeamName;
        } else
        {
            $og_title = $homeTeamName . ' vs ' . $awayTeamName;
        }
        
        $og_description = 'Read the beat writers take on the ' . $og_title . ' game on ' . $date;
        
        $og_url .= isset($_GET['ref'])? '?ref=' . $_GET['ref'] : '';
    }
    
    if($pre_post_type && $query_string)
    {
        $og_type = $pre_post_type . 's_list';
    }
    ?>
    <meta property="og:title" content="<?php echo $og_title; ?>" />
    <meta property="og:type" content="<?php echo $og_type; ?>" />
    <meta property="og:url" content="<?php echo $og_url; ?>" />
    <meta property="og:image" content="<?php echo $og_image; ?>" />
    <meta property="og:site_name" content="<?php echo $og_site_name; ?>" />
    <meta property="og:description" content="<?php echo $og_description; ?>" />    
    <?php    
}

add_action( 'fsu_insert_fb_in_head', 'fsu_insert_fb_in_head', 10 );

function fsu_admin_search_connected_teams($title, $team, $ctype)
{   
    if(!function_exists('get_c2c_connected_items'))
    {
        return $title;
    }
    
    $league = get_c2c_connected_items('team_to_league', get_post($team->ID), 'league');
    
    if($league)
    {
        $short_slug = get_post_meta($league->ID, '_wp_old_slug', 1);
        
        if($short_slug)
        {
            $title = $title . " - " . strtoupper($short_slug);
        } else
        {
            $title = $title . " - " . strtoupper($league->post_name);
        }
    }
    
    return $title;
}

//When teams are searched from connected text box, it append league to the team
if(isset($_REQUEST['direction'], $_REQUEST['p2p_type'], $_REQUEST['action'], $_REQUEST['subaction']) && $_REQUEST['direction'] == 'from' 
        && $_REQUEST['p2p_type'] == 'games_to_teams' && $_REQUEST['action'] == 'p2p_box' && $_REQUEST['subaction'] == 'search')
{
    add_filter( 'p2p_candidate_title', 'fsu_admin_search_connected_teams', 10, 3 );
}