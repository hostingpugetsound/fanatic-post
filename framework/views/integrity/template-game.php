<?php
global $current_user, $gameID, $homeTeamID, $homeTeamName, $awayTeamID, $awayTeamName, $ref_team_id, $team_view_type;

//Class added to populate content to gravity form for updating beat
class Gform_Post_Body
{

    public static $content = false;

    public static function set_content( $content ) {
        self::$content = $content;
    }

    public static function get_content() {
        return self::$content;
    }
}
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



include dirname(__FILE__) . '/beat-title.php';
?>


    <div class="container">

        <?php
        $homewriter_user_id = get_post_meta($gameID, 'beatwriter_user_team'.$homeTeamID, 1);
        $homewriter_authorised = (!empty($current_user->ID) && $homewriter_user_id == $current_user->ID);

        $awaywriter_user_id = get_post_meta($gameID, 'beatwriter_user_team'.$awayTeamID, 1);
        $awaywriter_authorised = (!empty($current_user->ID) && $awaywriter_user_id == $current_user->ID);
        ?>

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


    <?php include dirname(__FILE__) . '/beat-tabs.php'; ?>
        <div>
        <?php

        if( $homeTeamID == $ref_team_id ) {
            if( $team_view_type == 'preview' ) {
                $tab_class = 'homepregame';
                $tab_view_type = 'preview';
                $tab_beat_data = $homeTeamPreview;
            } else {
                $tab_class = 'homepostgame';
                $tab_view_type = 'recap';
                $tab_beat_data = $homeTeamRecap;
            }

            tab_content( $tab_class, $tab_view_type, $tab_beat_data, $homeTeamName, $homeTeamID, $gameID, $ref_team_id, $team_view_type, $homewriter_authorised );
        } else {
            if( $team_view_type == 'preview' ) {
                $tab_class = 'awaypregame';
                $tab_view_type = 'preview';
                $tab_beat_data = $awayTeamPreview;
            } else {
                $tab_class = 'awaypostgame';
                $tab_view_type = 'recap';
                $tab_beat_data = $awayTeamRecap;
            }

            tab_content( $tab_class, $tab_view_type, $tab_beat_data, $awayTeamName, $awayTeamID, $gameID, $ref_team_id, $team_view_type, $awaywriter_authorised );
        }

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
