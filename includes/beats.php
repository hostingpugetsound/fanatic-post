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




if ( !function_exists('tab_content') ) :

function tab_content($tab_id, $tab_v_type, $game_beat, $beat_team_name, $beat_team_id, $game_id, $ref_team_id, $ref_view_type, $beatwriter_authorised, $is_mobile=FALSE)
{
    $current_class = (($beat_team_id == $ref_team_id) && ($tab_v_type == $ref_view_type))? 'current' : '';
    $beatwriter_user_id = get_post_meta($game_id, 'beatwriter_user_team'.$beat_team_id, 1);

    if($beatwriter_user_id)
    {
        $author_data = get_userdata($beatwriter_user_id);
    }
    ?>
    <script>
        document.title = '<?php echo htmlspecialchars($beat_team_name . ' ' . ucfirst($tab_v_type)); ?> ' + document.title;
    </script>
    <div id="<?php echo $tab_id; ?>" class="tab-content <?php echo $current_class;?>">

        <?php if($game_beat): ?>
            <div class="beat_content_<?php echo bt_class_postfix($beat_team_id, $tab_v_type)?>">
                <?php echo format_beat_content($game_beat->post_content, $game_beat->post_title);?>
                <?php if($beatwriter_authorised):?>
                    <a href="javascript:void(0)" class="btn update_link" >Update</a>
                <?php endif;?>
                <style>
                    .gform_container_<?php echo bt_class_postfix($beat_team_id, $tab_v_type)?>
                    {
                        display: none;
                    }
                </style>
            </div>
        <?php elseif(!$beatwriter_authorised):?>
            <div class="no_beat_message no_beat_message_<?php echo bt_class_postfix($beat_team_id, $tab_v_type)?>">No <?php echo $beat_team_name . ' ' . $tab_v_type;?> content yet available.
                <?php if($beatwriter_user_id):?>
                    Beat coming soon by <?php echo $author_data->user_login;?>
                <?php else:?>
                    Come back soon.
                <?php endif;?>
            </div>
        <?php endif;?>
        <div class="gform_container_<?php echo bt_class_postfix($beat_team_id, $tab_v_type)?>">
            <?php
            if($beatwriter_authorised):
                ?>
                <h4><?php echo $beat_team_name . ' ' . ucfirst($tab_v_type);?> Beat</h4>
                <?php
                if(isset($game_beat->post_content)) {
                    Gform_Post_Body::set_content($game_beat->post_content);
                    add_filter( 'gform_field_value_pbody', array( 'Gform_Post_Body', 'get_content' ) );
                }

                gravity_form( 3, false, false, false, array('tid' => $beat_team_id, 'gid' => $game_id, 'btype' => $tab_v_type, 'ref' => $_GET['ref']), true);
                remove_filter( 'gform_field_value_pbody', array( 'Gform_Post_Body', 'get_content' ) );
            endif;
            ?>
        </div>

        <?php
        if($beatwriter_user_id):

            $beat_author_default_avatar = '<img width="60" height="60" class="modified avatar avatar-default" src="' . plugins_url(WC_Core::$PLUGIN_DIRECTORY . '/files/img/avatar_default.png') . '" alt=""/>';

            $author_data->profilepicture = get_user_meta($author_data->ID, 'profilepicture', 1);
            $author_data->description = get_user_meta($author_data->ID, 'description', 1);
            $author_data->facebook = get_sns_url($author_data->ID, 'facebook');
            $author_data->twitter = get_sns_url($author_data->ID, 'twitter');
            $author_data->google_plus = get_sns_url($author_data->ID, 'google_plus');
            $author_data->website_url = get_user_meta($author_data->ID, 'user_url', 1);

            ?>

            <!--
                <div class="beatwriter-bio-div">
                    <div class="beatwriter-bio-div-info">

                        <?php
            if ($author_data->profilepicture) {
                echo get_avatar($author_data->ID, 60, '', '', array('class' => 'avatar avatar-60 photo'));
            } else {
                echo $beat_author_default_avatar;
            }
            ?>

                        <h4>About <?php echo $author_data->display_name;?></h4>
                        <p class="beatwriter-bio-div-text">FANATICPOST Beat Writer</p>
                        <p class="beatwriter-bio-div-text"><?php echo $author_data->display_name;?> has written <?php echo number_format_i18n(count_user_posts($author_data->ID,'game_beat'));?> beat(s) on this website.</p>
                        <p class="beatwriter-bio-div-meta"><?php echo $author_data->description;?></p>
                        <ul>
                            <li class="first">
                                <a href="<?php echo get_site_url() . '/beats/' . $author_data->user_login;?>">
                                    View all beats by <?php echo $author_data->display_name;?> <span class="meta-nav">→</span>
                                </a>
                            </li>
                            <li><a title="View <?php echo $author_data->display_name;?>'s profile" href="<?php echo get_site_url() . '/profile/' . $author_data->user_login;?>">Profile</a></li>
                            <?php if($author_data->twitter):?>
                            <li><a rel="external" title="Follow <?php echo $author_data->display_name;?> on Twitter" href="<?php echo $author_data->twitter;?>">Twitter</a></li>
                            <?php endif;?>
                            <?php if($author_data->facebook):?>
                            <li><a rel="external" title="Be <?php echo $author_data->display_name;?>'s friend on Facebook" href="<?php echo $author_data->facebook;?>">Facebook</a></li>
                            <?php endif;?>
                            <?php if($author_data->google_plus):?>
                            <li><a title="Add <?php echo $author_data->display_name;?> in your circle" rel="me" href="<?php echo $author_data->google_plus;?>">Google+</a></li>
                            <?php endif;?>
                            <?php if($author_data->website_url):?>
                            <li><a title="Visit <?php echo $author_data->display_name;?>'s website" rel="me" href="<?php echo $author_data->website_url;?>">Website</a></li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
                -->

        <?php endif;?>

        <?php

        if($game_beat)
        {
            #global $post;

            $post = get_post($game_beat->ID);

            setup_postdata( $post );

            #x_get_view( 'global', '_comments-template' );
        }
        ?>
    </div>
    <?php
}

endif;



function maybe_echo_score( $score ) {
    if( is_numeric($score) && isset($_GET['vtype']) ) {
        if( $_GET['vtype'] == 'recap' )
            return $score;
    }
    return '';
}


function create_beat( $beat_type, $game_id, $team_id, $ref_team_id = null ) {
    // @todo check for existing beat w/ same beat type, game id, team id
    // @todo: also check for total pts
    if( $ref_team_id == null )
        $ref_team_id = $team_id;


    $exists = false;
    if( !$exists ) {
        $insert = wp_insert_post( array(
            'post_content' => $_POST['body'],
            'post_status' => 'publish', #'draft',
            'post_type' => 'game_beat',
            'meta_input' => [
                'beat-type' => $beat_type,
                'game-id' => $game_id,
                'team-id' => $team_id,
                'ref-team-id' => $ref_team_id,
            ]
        ) );

        if( $insert ) {

            p2p_create_connection( 'game_beat_to_game',
                array(
                    'from' => $insert,
                    'to' => $game_id,
                    'meta' => array(
                        'date' => current_time('mysql')
                    )
                )
            );
        }

        return $insert;

    }


    return false;


    # todo: subtract points from user

}