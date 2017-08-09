<?php

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

$beatID = get_the_ID();
$gameID = get_post_meta($beatID, 'game-id', 1);

$homeTeamName 				= get_post_meta($gameID, 'wpcf-home-team-name', 1);
$awayTeamName 				= get_post_meta($gameID, 'wpcf-away-team-name', 1);

$homeTeamSlug 				= get_post_meta($gameID, 'wpcf-home-team', 1);
$awayTeamSlug 				= get_post_meta($gameID, 'wpcf-away-team', 1);


$gamedate             = get_post_meta($gameID, 'wpcf-game-date', 1);
$date = date("D F j, Y", $gamedate);

$homeScore = get_post_meta($gameID, 'wpcf-home-team-score', 1);
$awayScore = get_post_meta($gameID, 'wpcf-away-team-score', 1);

$gameType = get_post_meta($gameID, 'wpcf-game-type', 1);

global $current_user;

$connectedBeats = new WP_Query(
        array(
                'connected_type' => 'game_beat_to_game',
                'connected_items' => get_post($gameID),
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
                'connected_items' => get_post($gameID),
                'nopaging' => true
        )
);

//Class added to populate content to gravity form for updating beat
class Gform_Post_Body {

    public static $content = false;

    public static function set_content($content)
    {
        self::$content = $content;
    }

    public static function get_content()
    {
        return self::$content;
    }
}

$homeTeamID = get_team_id($homeTeamName, $homeTeamSlug, $connectedTeams);
$awayTeamID = get_team_id($awayTeamName, $awayTeamSlug, $connectedTeams);

$homeTeamLink = get_team_link($homeTeamID);;
$awayTeamLink = get_team_link($awayTeamID);

$homeTeamPreview = get_team_beat($game_beats_parsed, $homeTeamID, 'preview');


$homeTeamRecap   = get_team_beat($game_beats_parsed, $homeTeamID, 'recap');

$awayTeamPreview = get_team_beat($game_beats_parsed, $awayTeamID, 'preview');

$awayTeamRecap   = get_team_beat($game_beats_parsed, $awayTeamID, 'recap');

//Referrer team ID
$reverse_teams  = (isset($_GET['ref']) && $_GET['ref'] == $homeTeamID)? FALSE : TRUE;

global $ref_team_id, $team_view_type;

$ref_team_id    = get_post_meta($beatID, 'team-id', 1);
$team_view_type = get_post_meta($beatID, 'beat-type', 1);



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

                gravity_form( 3, false, false, false, array('tid' => $beat_team_id, 'gid' => $game_id, 'btype' => $tab_v_type, 'ref' => $_GET['ref'], 'ebid' => $game_beat->ID), true);
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

                <?php endif;?>

                <?php
                
                #x_get_view( 'global', '_comments-template' );
                ?>
  	</div>
    <?php
}

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>

<?php get_header();
include dirname(__FILE__) . '/beat-title.php';
?>


    <div class="container">

        <?php
        $homewriter_user_id = get_post_meta($gameID, 'beatwriter_user_team'.$homeTeamID, 1);
        $homewriter_authorised = (!empty($current_user->ID) && $homewriter_user_id == $current_user->ID);

        $awaywriter_user_id = get_post_meta($gameID, 'beatwriter_user_team'.$awayTeamID, 1);
        $awaywriter_authorised = (!empty($current_user->ID) && $awaywriter_user_id == $current_user->ID);
        ?>

        <div style="clear:both;"></div>
        <!--
        <div class="share_custom_class">
            <?php //echo do_shortcode('[ssba url="'.$actual_link.'" title="'.$homeTeamName . ' vs ' . $awayTeamName.' Beat Article"]'); ?>
            <div class="ssba ssba-wrap">
              <div style="text-align:center">
                  <a class="ssba_facebook_share" data-dm_post_id="<?php echo $beatID; ?>" href="http://www.facebook.com/sharer.php?u=<?php echo $actual_link; ?>" >
                  <img src="http://fanaticpost.com/wp-content/plugins/simple-share-buttons-adder/buttons/simple/facebook.png" title="Facebook" class="ssba ssba-img" alt="Share on Facebook" />
                </a>
                <a class="ssba_twitter_share" data-dm_post_id="<?php echo $beatID; ?>" href="http://twitter.com/share?url=<?php echo $actual_link; ?>&amp;text=<?php echo $homeTeamName . ' vs ' . $awayTeamName ?>" >
                  <img src="http://fanaticpost.com/wp-content/plugins/simple-share-buttons-adder/buttons/simple/twitter.png" title="Twitter" class="ssba ssba-img" alt="Tweet about this on Twitter" />
                </a>
                <a class="ssba_google_share" data-dm_post_id="<?php echo $beatID; ?>" href="https://plus.google.com/share?url=<?php echo $actual_link; ?>" >
                  <img src="http://fanaticpost.com/wp-content/plugins/simple-share-buttons-adder/buttons/simple/google.png" title="Google+" class="ssba ssba-img" alt="Share on Google+" />
                </a>
                <a class="ssba_email_share" href="mailto:?subject=<?php echo urlencode($homeTeamName . ' vs ' . $awayTeamName); ?>&amp;body=%20<?php echo urlencode($actual_link); ?>">
                  <img src="http://fanaticpost.com/wp-content/plugins/simple-share-buttons-adder/buttons/simple/email.png" title="Email" class="ssba ssba-img" alt="Email this to someone" />
                </a>
              </div>
            </div>
        </div>
        -->

  	<ul class="tabs">
                <li class="tab-link <?php echo get_active_class(($reverse_teams)? $awayTeamID : $homeTeamID, 'preview'); ?>" data-tab="<?php echo ($reverse_teams)? 'awaypregame':'homepregame';?>">
                    <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $awayTeamPreview : $homeTeamPreview, ($reverse_teams)? $awayTeamID : $homeTeamID, 'preview'); ?>">Preview</a>
                </li>
  		<li class="tab-link <?php echo get_active_class(($reverse_teams)? $awayTeamID : $homeTeamID, 'recap'); ?>" data-tab="<?php echo ($reverse_teams)? 'awaypostgame':'homepostgame'?>">
                    <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $awayTeamRecap : $homeTeamRecap, ($reverse_teams)? $awayTeamID : $homeTeamID, 'recap'); ?>">Recap</a>
                </li>

                <li class="tab-link <?php echo get_active_class(($reverse_teams)? $homeTeamID : $awayTeamID, 'recap'); ?>" data-tab="<?php echo ($reverse_teams)? 'homepostgame':'awaypostgame'?>" style="float:right">
                    <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $homeTeamRecap : $awayTeamRecap, ($reverse_teams)? $homeTeamID : $awayTeamID, 'recap'); ?>">Recap</a>
                </li>
                <li class="tab-link <?php echo get_active_class(($reverse_teams)? $homeTeamID : $awayTeamID, 'preview'); ?>" data-tab="<?php echo ($reverse_teams)? 'homepregame':'awaypregame'?>" style="float:right">
                    <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $homeTeamPreview : $awayTeamPreview, ($reverse_teams)? $homeTeamID : $awayTeamID, 'preview'); ?>">Preview</a>
                </li>
  	</ul>
        <div>
        <?php

        
        if($ref_team_id == $homeTeamID  && $team_view_type == 'preview')
        {
                $tab_class = 'homepregame';
                $tab_view_type = 'preview';
                $tab_beat_data = $homeTeamPreview;
                $tab_team_name = $homeTeamName;
                $tab_writer_authorised = $homewriter_authorised;
        }
        else if($ref_team_id == $homeTeamID  && $team_view_type == 'recap')
        {
                $tab_class = 'homepostgame';
                $tab_view_type = 'recap';
                $tab_beat_data = $homeTeamRecap;
                $tab_team_name = $homeTeamName;
                $tab_writer_authorised = $homewriter_authorised;
        }
        else if($ref_team_id == $awayTeamID  && $team_view_type == 'preview')
        {
                $tab_class = 'awaypregame';
                $tab_view_type = 'preview';
                $tab_beat_data = $awayTeamPreview;
                $tab_team_name = $awayTeamName;
                $tab_writer_authorised = $awaywriter_authorised;
        }
        else if($ref_team_id == $awayTeamID  && $team_view_type == 'recap')
        {
                  $tab_class = 'awaypostgame';
                  $tab_view_type = 'recap';
                  $tab_beat_data = $awayTeamRecap;
                  $tab_team_name = $awayTeamName;
                  $tab_writer_authorised = $awaywriter_authorised;
        }
        
        tab_content($tab_class, $tab_view_type, $tab_beat_data, $tab_team_name, $ref_team_id, $gameID, $ref_team_id, $team_view_type, $tab_writer_authorised);

        ?>

        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery(document).bind('gform_confirmation_loaded', function(event, formId){
                jQuery('#gforms_confirmation_message_' + formId).prev().hide()
            });

            jQuery('.update_link').click(function(){
                jQuery(this).parent().next().show();
                jQuery(this).parent().hide();
            });

        })
    </script>
