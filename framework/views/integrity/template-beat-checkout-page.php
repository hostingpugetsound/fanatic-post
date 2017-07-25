<?php

global $current_user;
$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

$monthfilter = (isset($_GET['month']) && !empty($_GET['month']))? $_GET['month'] : '';
$game_id = (isset($_GET['game_id']) && !empty($_GET['game_id']))? $_GET['game_id'] : 0;

function filter_posts($connected, $monthfilter)
{
    //global $monthfilter;

    $beat_entries = array();

    while ($connected->have_posts()) : $connected->the_post();

        $entry = get_post();

        //$entry->meta_game_season           = get_post_meta(get_the_ID(), 'wpcf-game-season', 1);

        $entry->meta_game_date = get_post_meta(get_the_ID(), 'wpcf-game-date', 1);
        
        //var_dump(date('M', $entry->meta_game_date)); die;
        
        if (strtolower(date('M', $entry->meta_game_date)) == strtolower($monthfilter) || empty($monthfilter)) {
            $entry->meta_game_type = get_post_meta(get_the_ID(), 'wpcf-game-type', 1);
            $entry->meta_home_team_name = get_post_meta(get_the_ID(), 'wpcf-home-team-name', 1);
            $entry->meta_away_team_name = get_post_meta(get_the_ID(), 'wpcf-away-team-name', 1);
            $entry->meta_home_team_score = get_post_meta(get_the_ID(), 'wpcf-home-team-score', 1);
            $entry->meta_away_team_score = get_post_meta(get_the_ID(), 'wpcf-away-team-score', 1);
            $entry->meta_home_team_slug = get_post_meta(get_the_ID(), 'wpcf-home-team', 1);
            $entry->meta_away_team_slug = get_post_meta(get_the_ID(), 'wpcf-away-team', 1);
            $beat_entries[] = $entry;
        }

    endwhile;

    return $beat_entries;
}

function is_active_team($teamName, $teamSlug, $team){
    
    if(strtolower(trim($teamSlug)) == strtolower(trim($team->post_name)))
    {
        return true;
    } else if(strtolower(trim($teamName)) == strtolower(trim($team->post_title)))
    {
        return true;
    }
    
    return false;
}


function currentUrl() {
    $protocol 		= strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     			= $_SERVER['HTTP_HOST'];
    $script   			= $_SERVER['SCRIPT_NAME'];
    $params   		= $_SERVER['QUERY_STRING'];

    return $protocol . '://' . $host . $script . '?' . $params;
}

// GET SORT AND FILTER PARAMS

if (isset($_GET['sort'])) {
	if( $_GET['sort'] == "replies" ){
		$orderby 	.= ' comment_count';
		$order 		 = 'DESC';
	}
	if ($_GET['sort'] == "popular") {
		$orderby 	.= 'meta_value_num';
		$order 		 = 'DESC';
	}

}

if (isset($_GET['sort_date'])) {

	if( $_GET['sort_date'] == "newest" ){
		$orderby 	.= ' date';
		$order 		 = 'DESC';
	}else if( $_GET['sort_date'] == "oldest" ){
		$orderby 	.= ' date';
		$order 		 = 'ASC';
	}
}

$no_filter = false;
if(empty($orderby)){
	$orderby = 'meta_value_num';
	$order = 'DESC';
	$no_filter = true;
}

function cmp($a, $b) {
    return $b->promoted - $a->promoted;
}

function sortarticles($posts) {


    $promoted_posts = array();

    foreach ($posts as $key => $post) {

        $promoted = get_post_meta($post->ID, 'promoted', true);

        if (!empty($promoted)) {

            $hours = (time() - $promoted) / 3600;

            if($hours > 72)
            {
                $post->promoted  = 0;
            } else
            {
                $post->promoted  = $promoted;
                unset($posts[$key]);
                $promoted_posts[] = $post;
            }

        } else {
            $post->promoted  = 0;
        }

    }


    usort($promoted_posts, 'cmp');

    return array_merge($promoted_posts, $posts);
}

?>

<?php get_header(); ?>

<?php


$t = $_GET['t'];


$args = array(
    'post_type' => 'team',
    'name' => $t
);
//$my_query = new WP_Query( $args );
$my_query = new WP_Query( 'post_type=team&name='.$t);




	if ( $my_query->have_posts() ) {

		while ($my_query->have_posts()) {
			$my_query->the_post();

                        
                       // print_r($post);
                        
            //Find Connected Leagues
            $connected_leagues = new WP_Query(array(
                'connected_type' => 'team_to_league',
                'connected_items' => $post,
                'nopaging' => true
            ));
            
            $default_season_type = '';
            $default_season = date('Y');
            if($connected_leagues->post_count > 0)
            {
                $default_season_type = get_post_meta($connected_leagues->posts[0]->ID, 'wpcf-default-season-type', 1);
                if($default_season_type == 'crossover')
                {
                    $default_season = (date('Y') . '-' . substr((date('Y') + 1), 2, 2));
                }
                
            }
            
            $season = (isset($_GET['season']) && !empty($_GET['season']))? $_GET['season'] : $default_season;

                        
                        
			// Find connected pages

                        $connected = new WP_Query(
                                array(
                                    'orderby'   => 'meta_value_num',
                                    'order'     => 'ASC',
                                    'meta_key'  => 'wpcf-game-date',


                                    'meta_query' => array(
                                        array(
                                                'key'     => 'wpcf-game-season',
                                                'value'   => $season,
                                                'compare' => '=',
                                        ),
                                    ),




                                        'connected_type' => 'games_to_teams',
                                        'connected_items' => $post,
                                        'nopaging' => true
                                )
                        );


                     
        $teamPost = $post;
        $teamID   = $post->ID;
//	$teamName = get_the_title();
//        $teamSlug = $post->post_name;
        
            // Find connected pages
            $articlesConnected = new WP_Query(
				array(
                                        'meta_key' => 'dm_post_popularity_value',
					'connected_type' => 'articles_to_teams',
					'connected_items' => $post,
					'nopaging' => true
				)
			);

            $articlesNum = $articlesConnected->post_count;
            
            
$pp_app_base_url = get_site_url();

$current_url = urlencode($pp_app_base_url . '/game/?t=' . $_GET['t'] . '&tid='.$_GET['tid'].'&season='.$season);

$notify_url = $pp_app_base_url . '/paypal.php?action=notify_beatwriter_for_game&password=7412581&current_url=' . $current_url;
$return_url =  $pp_app_base_url . '/paypal.php?action=success_beatwriter_for_game&password=741258&current_url=' . $current_url;
$cancel_url =  $pp_app_base_url . '/paypal.php?action=cancel_beatwriter_for_game&current_url=' . $current_url;

include_once ABSPATH . 'pp_config.php';
            

?>
<div class="x-container max width " style="width:100%;">
    <div class="x-main full" role="main">

            <?php if(isset($_SESSION['pp_success_message'])):?>
                <div class="alert-success">
                    <?php echo $_SESSION['pp_success_message']; unset($_SESSION['pp_success_message']); ?>
                </div>
            <?php endif;?>

            <?php if(isset($_SESSION['pp_failure_message'])):?>
                <div class="alert-danger">
                    <?php echo $_SESSION['pp_failure_message']; unset($_SESSION['pp_failure_message']); ?>
                </div>
            <?php endif;?>

            <header class="entry-header">
		<div style="float:left;">
			<span class="teamHeading">Beat Checkout | <?php the_title(); ?></span>
                        <div><?php the_breadcrumb($post->ID);?></div>
		</div>    
		<div style="clear:both;"></div>

	  <ul id="navlist" style=" margin-bottom:20px">
		<li><a href="<?php echo get_site_url();?>/team/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a></li>
		<li><a href="<?php echo get_site_url();?>/article/?t=<?php echo $post->post_name; ?>&tid=<?php the_ID(); ?>">Articles <span class="circleNum"><?= $articlesNum; ?></span></a></li>
		<li class="<?php if(strstr($_SERVER['REQUEST_URI'], 'game')) { echo "active"; } ?>"><a href="<?php echo get_site_url();?>/game/?t=<?php echo $post->post_name; ?>&tid=<?php the_ID(); ?>">The Beat <span class="circleNum"><?= count_team_beats(get_the_ID()); ?></a></li>
                
                <?php
                if(!empty($current_user->ID))
                { 
                    $form_action = PAYPAL_URL;
                    
//                    if(!get_user_meta( $current_user->ID, '_user_beats_agreement_acceptance', 1 ))
//                    {
//                        $form_action = get_site_url() . '/be-a-beat-writer';
//                    }
                ?>
                <div>
                <p style="float:left; margin-top: 3em;">*Select any or all games as the Beat Writer. Price will adjust accordingly.</p>
                <form id="pp_beatwriter_payment" action="<?php echo $form_action;?>" method="post" style="margin: 0; float:right">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="<?php echo PAYPAL_EMAIL;?>">
                    <input type="hidden" name="item_name" value="Beat Writer">
                    <input type="hidden" name="item_number" value="<?php the_ID(); ?>">
                    <input type="hidden" name="custom" class="pp_custom_data" value='<?php echo addslashes(json_encode(array('user_id' => $current_user->ID)));?>'>
                    <input type="hidden" name="amount" class="pp_amount" value="3">
                    <input type="hidden" name="tax" value="">
                    <input type="hidden" name="quantity" class="pp_quantity" value="1">
                    <input type="hidden" name="no_note" value="1">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="address_override" value="1">
                    <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
                    <input type="hidden" name="return" value="<?php echo $return_url; ?>">
                    <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
                    <input type="submit" class="x-btn  x-btn-round x-btn-x-small pay_now_btn" value="Pay Now" />
                </form>
                </div>
                <?php } ?>
	</ul>
         <div style="clear:both;"></div> 
    </header>
<?php


    $beat_entries = filter_posts($connected, $monthfilter);
    
    if (count($beat_entries) > 0): 
    
    
    ?>

  
    <div>
      <table>
        <tr>
          <th>
                <input type="checkbox" name="checkbox_all_games" class="checkbox_all_games" />
          </th>
          <th>
            <strong>Date</strong>
          </th>
          <th>
            Home Team
          </th>
          <th>
            Away Team
          </th>
        </tr>








<?php
    foreach($beat_entries as $entry):
        
      $beatwriter_user_id = get_post_meta($entry->ID, 'beatwriter_user_team'.$teamID, 1);
      $is_upcoming_date = (($entry->meta_game_date + 32400) > strtotime('-8 hours'));
      if(!empty($beatwriter_user_id) || !$is_upcoming_date ) { continue; }
?>
<tr>
  <td>
    <?php $checked = ($entry->ID == $game_id)? 'checked="checked"' : ''; ?>
      <input type="checkbox" <?php echo $checked;?> value="<?php echo $entry->ID;?>" name="checkbox_single_game" class="checkbox_single_game" data-beat-price="<?php echo get_game_price($entry->ID, $teamID); ?>" />
  </td>
  <td>
    <strong><a href='<?php echo get_permalink($entry->ID); ?>'><?php echo date("D, M j", $entry->meta_game_date); ?></a></strong>
  </td>
  <td>
    <?php 
    $is_active_team = is_active_team($entry->meta_home_team_name, $entry->meta_home_team_slug, $teamPost);
    if($is_active_team)
    {
        ?><strong><?php echo $entry->meta_home_team_name; ?></strong><?php 
    } else
    {
        echo $entry->meta_home_team_name;
    }
    ?>
    
  </td>
  <td>
    <?php 
    $is_active_team = is_active_team($entry->meta_away_team_name, $entry->meta_away_team_slug, $teamPost);
    if($is_active_team)
    {
        ?><strong><?php echo $entry->meta_away_team_name; ?></strong><?php 
    } else
    {
        echo $entry->meta_away_team_name;
    }
    ?>
  </td>
</tr>

<?php



    //endwhile;
endforeach;

  else:
    echo '<div style="clear:both;"></div> ';
    echo "There is no scheduled games in our archive for this team.";

    endif;

    wp_reset_postdata(); // set $post back to original post


?>

    </table>
        <script>
            
        function addslashes(str) {
  
            return (str + '')
              .replace(/[\\"']/g, '\\$&')
              .replace(/\u0000/g, '\\0');
        }
            
        function stripslashes(str) {

            return (str + '')
                .replace(/\\(.?)/g, function(s, n1) {
                  switch (n1) {
                  case '\\':
                    return '\\';
                  case '0':
                    return '\u0000';
                  case '':
                    return '';
                  default:
                    return n1;
                  }
                });
        }
        
        jQuery(document).ready(function(){
            
            jQuery('.checkbox_all_games').click(function(){
                
                if(jQuery('.checkbox_all_games').attr('checked') == 'checked' || jQuery('.checkbox_all_games').attr('checked') == true)
                {
                    jQuery('.checkbox_single_game').attr('checked', true);
                } else
                {
                    jQuery('.checkbox_single_game').attr('checked', false);
                }
                
                update_beat_total();
                
            });
            
            jQuery('.checkbox_all_games').click(function(){
                update_beat_total();
            });
            
            jQuery('.checkbox_single_game').click(function(){
                update_beat_total();
            });
        
            update_beat_total();

            jQuery('.pay_now_btn').click(function( event ){
                event.preventDefault();
            
                var size = jQuery('.checkbox_single_game:checked').size();
                
                if(size < 1)
                {
                    return;
                }
            
                var pp_custom_data = JSON.parse(stripslashes(jQuery('.pp_custom_data').val()));
                pp_custom_data.games = [];
                jQuery('.checkbox_single_game:checked').each(function(index, element){
                    pp_custom_data.games.push(jQuery(element).val());
                });
                
                jQuery('.pp_custom_data').val(addslashes(JSON.stringify(pp_custom_data)));
                
                jQuery('#pp_beatwriter_payment').submit();
                
            });

            
        
        });
        
        function update_beat_total()
        {
            var amount = 0;
            
            jQuery('.checkbox_single_game:checked').each(function( index, element ) {
                amount += parseInt(jQuery( element ).data('beat-price'));
            });
            
            //Update amount in PP form
            jQuery('.pp_amount').val(amount);
            
            //Update amount in PP button
            jQuery('.pay_now_btn').val('Pay Now $' + amount);
        }
        
        </script>
        
        
    </div>
</div>
  </div>
<?php get_footer(); ?>

<?php

}
} else {

	print_r($my_query);

	status_header(404);
	nocache_headers();
	include( get_404_template() );
	exit;

}


?>
