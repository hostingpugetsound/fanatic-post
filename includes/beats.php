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

/*
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
*/
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
    <div id="<?php echo $tab_id; ?>" class="tab-content <?php echo 'current'; #echo $current_class;?>">

        <?php if($game_beat): ?>
            <div class="beat_content_<?php echo bt_class_postfix($beat_team_id, $tab_v_type)?>">
                <?php if($beatwriter_authorised):?>
                    <a href="javascript:void(0)" class="btn lboxtrigger" >Update</a>
                <?php endif;?>

                <?php #include dirname(__FILE__) . '/../framework/views/global/_write-beat.php'; ?>
                <div class="lboxbg-fp">
                    <div class="lbox-fp">
                        <div class="box-fp clearfix">
                            <div class="close-lbox-fp">X</div>

                            <h1><?php echo get_the_title( $game_id ) ?></h1>
                            <h2>Edit <?php echo $ref_view_type; ?></h2>

                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="beat_type" value="<?php echo get_post_meta( $game_beat->ID, 'beat-type', true); ?>" />
                                <input type="hidden" name="team_id" value="<?php echo get_post_meta( $game_beat->ID, 'team-id', true); ?>" />
                                <label for="body">
                                    Body<br />
                                    <textarea name="body"><?php echo $game_beat->post_content; ?></textarea>
                                </label>

                                <!--
                                <div class="x-column x-sm x-1-2">
                                    <label for="body">
                                        Image<br />
                                        <input type="file" name="image" />
                                    </label>
                                </div>
                                -->

                                <div class="x-column x-sm x-1-2">
                                    <input type="submit" name="submit" value="POST" />
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <?php echo format_beat_content($game_beat->post_content, $game_beat->post_title);?>
            </div>
        <?php else : ?>
            <a href="javascript:void(0)" class="btn lboxtrigger" >Write a beat</a>

            <div class="lboxbg-fp">
                <div class="lbox-fp">
                    <div class="box-fp clearfix">
                        <div class="close-lbox-fp">X</div>

                        <h1><?php echo get_the_title( $game_id ) ?></h1>
                        <h2>Write a <?php echo $ref_view_type; ?></h2>

                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="beat_type" value="<?php echo $ref_view_type; ?>" />
                            <input type="hidden" name="team_id" value="<?php echo $beat_team_id; ?>" />
                            <label for="body">
                                Body<br />
                                <textarea name="body"></textarea>
                            </label>

                            <!--
                            <div class="x-column x-sm x-1-2">
                                <label for="body">
                                    Image<br />
                                    <input type="file" name="image" />
                                </label>
                            </div>
                            -->

                            <div class="x-column x-sm x-1-2">
                                <input type="submit" name="submit" value="POST" />
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        <?php endif; ?>

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



function get_connected_beats( $game_id = null ) {

    if( !$game_id )
        $game_id = get_post();


    $connectedBeats = new WP_Query(
        array(
            'connected_type' => 'game_beat_to_game',
            'connected_items' => $game_id,
            'nopaging' => true,
            'post_type' => 'game_beat',
            'orderby' => 'date',
            'order' => 'asc',
            'post_status' => 'publish'
        )
    );

    return $connectedBeats;


    /*
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

    return $game_beats_parsed;
    */
}

function beat_exists( $beat_type, $game_id, $team_id, $ref_team_id ) {

    $beats = get_connected_beats( $game_id );
    foreach( $beats->get_posts() as $beat ) {
        $bt = get_post_meta( $beat->ID, 'beat-type', true );
        $tid = get_post_meta( $beat->ID, 'team-id', true );

        if( $bt == $beat_type ) {
            if( $tid == $team_id )
                return true;
        }
        #return true;

    }

    return false;
}


function can_user_create_beat( $beat_type, $game_id, $team_id, $ref_team_id ) {

    $beats = get_connected_beats( $game_id );

    foreach( $beats->get_posts() as $beat ) {
        # if beat exists, return false
        if( get_post_meta( $beat->ID, 'team-id', true ) == $team_id ) {
            #echo $beat->ID . ' - author: ' . $beat->post_author;
            if( get_post_meta( $beat->ID, 'beat-type', true ) == $beat_type ) {
                if( $beat->post_author != get_current_user_id() )
                    return new WP_Error( 'error', 'Beat already exists!' );
                else
                    return $beat->ID;
            } else {
                # check if author = current user
                if( $beat->post_author != get_current_user_id() )
                    return new WP_Error( 'error', 'This team\'s beat is owned by another user!' );

            }
        }

    }

    return true;


}


function create_beat( $beat_type, $game_id, $team_id, $ref_team_id = null ) {
    // @todo check for existing beat w/ same beat type, game id, team id
    // @todo: also check for total pts
    if( $ref_team_id == null )
        $ref_team_id = $team_id;


    $can_create = can_user_create_beat( $beat_type, $game_id, $team_id, $ref_team_id );

    if( is_wp_error($can_create) ) {
        return $can_create;
    } else {

        $user_id = get_current_user_id();
        $points = fp_get_user_points( $user_id );
        $required_points = 50; # todo: pull from post meta later

        # if user has enough points
        if( $points >= $required_points ) {
            $args = array(
                'post_content' => $_POST['body'],
                'post_status' => 'publish', #'draft',
                'post_type' => 'game_beat',
                'meta_input' => [
                    'beat-type' => $beat_type,
                    'game-id' => $game_id,
                    'team-id' => $team_id,
                    'ref-team-id' => $ref_team_id,
                ]
            );
            #update existing post if an ID was returned
            if( is_int($can_create) ) {
                $args['ID'] = $can_create;
                $insert = wp_update_post( $args );
            } else {
                $insert = wp_insert_post( $args );
            }


            if( $insert ) {
                p2p_create_connection( 'game_beat_to_game',
                    array(
                        'from' => $insert,
                        'to' => $game_id,
                        'meta' => array(
                            'date' => current_time( 'mysql' )
                        )
                    )
                );

                $new_points = $points - $required_points;
                update_user_meta( $user_id, '_points', $new_points );

                update_post_meta( $game_id, 'beatwriter_user_team' . $team_id, $user_id );
            }

            return $insert;
        } else {
            return new WP_Error( 'error', sprintf('Your account does not have enough points to write this beat! %d points needed', $required_points) );
        }

    }

}