<?php

global $current_user;
$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

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

function get_connected_items_for_team($connection_name, $post)
{
    $connected_items = new WP_Query(array(
        'connected_type' => $connection_name,
        'connected_items' => $post,
        'posts_per_page' => 1
    ));

    if($connected_items->post_count > 0)
    {
        return $connected_items->posts[0];
    }
    return FALSE;
}

function get_league_season_type($post) {

    //GetLeague
    $league = get_connected_items_for_team('team_to_league', $post);

    if($league)
    {
        //Get Season Type
        return get_post_meta($league->ID, 'wpcf-default-season-type', 1);
    } else
    {
        //Get Division
        $division = get_connected_items_for_team('team_to_division', $post);

        if($division)
        {
            //Get Conference from division
            $conference = get_connected_items_for_team('division_to_conference', $division);
        } else
        {
            //Get Conference from team
            $conference = get_connected_items_for_team('team_to_conference', $post);
        }

        if($conference)
        {
            //Get League from conference
            $league = get_connected_items_for_team('conference_to_league', $conference);
        }

        if($league)
        {
            //Get Season Type
            return get_post_meta($league->ID, 'wpcf-default-season-type', 1);
        }
    }

    return FALSE;
}

function get_default_season_year($team_id)
{
    $league = get_team_league($team_id);

    if($league)
    {
        $league_season_start_date = get_post_meta($league->ID, 'wpcf-season-start-date', 1);

        if($league_season_start_date)
        {
            $current_year = date('Y');
            $season_start_date = $current_year . '-' . date('m-d', $league_season_start_date);

            if(time() > strtotime($season_start_date))
            {
                $current_year;
            } else
            {
                $current_year--;
            }

            return $current_year;
        }
    }

    return date('Y');
}

function get_default_month_status($team_id)
{
    $league = get_team_league($team_id);

    if($league)
    {
        $league_season_default_month = get_post_meta($league->ID, 'wpcf-season-default-month', 1);

        return !empty($league_season_default_month);
    }

    return FALSE;
}

function get_team_link($teamName, $teamSlug, $connectedTeams, $pageTeamID){


    foreach($connectedTeams->posts as $team)
    {
        $iteam = false;
        if(strtolower(trim($teamSlug)) == strtolower(trim($team->post_name)))
        {
            $iteam = $team;
        } else if(strtolower(trim($teamName)) == strtolower(trim($team->post_title)))
        {
            $iteam = $team;
        }

        if($iteam && $iteam->ID == $pageTeamID)
        {
            //if this is current page team, return team arena link
            return get_permalink($iteam->ID);
        } elseif($iteam)
        {
            //if this is not current page team, return beat page url
            return get_site_url() . '/game/?t=' . $iteam->post_name;
        }

    }

    return "javascript:void(0)";
}

function get_other_team_id($this_team_id, $connectedTeams){

    foreach($connectedTeams->posts as $team)
    {
        if($this_team_id != $team->ID)
        {
            return $team->ID;
        }
    }

    return false;
}

function get_beat_icon($game_id, $this_team_id, $other_team_id)
{
    $args = array(
            'connected_type' => 'game_beat_to_game',
            'connected_items' => get_post($game_id),
            'nopaging' => true,
            'post_type' => 'game_beat',
            'post_status' => 'publish',
            'meta_query' => array(
                                array(
                                    'key'     => 'team-id',
                                    'value'   => $this_team_id,
                                    'compare' => '=',
                                ),
                                array(
                                    'key'     => 'beat-type',
                                    'value'   => 'preview',
                                    'compare' => '=',
                                ),

                        ),
            );

    $connectedBeats_1 = new WP_Query(
        $args
    );

    $_1st = ($connectedBeats_1->post_count > 0)? 1 : 0;

    $args['meta_query'][0]['value'] = $other_team_id;
    $args['meta_query'][1]['value'] = 'preview';

    $connectedBeats_2 = new WP_Query(
        $args
    );

    $_2nd = ($connectedBeats_2->post_count > 0)? 1 : 0;

    $args['meta_query'][0]['value'] = $this_team_id;
    $args['meta_query'][1]['value'] = 'recap';

    $connectedBeats_3 = new WP_Query(
        $args
    );

    $_3rd = ($connectedBeats_3->post_count > 0)? 1 : 0;

    $args['meta_query'][0]['value'] = $other_team_id;
    $args['meta_query'][1]['value'] = 'recap';

    $connectedBeats_4 = new WP_Query(
        $args
    );

    $_4th = ($connectedBeats_4->post_count > 0)? 1 : 0;

    if($_1st == 0 && $_2nd == 0 && $_3rd == 0 && $_4th == 0)
    {
        return false;
    }

    return get_stylesheet_directory_uri().'/images/beat_icons/'.$_1st.$_2nd.$_3rd.$_4th.'.png';
}

function get_null_icon()
{
    return get_stylesheet_directory_uri().'/images/beat_icons/0000.png';
}

function get_full_icon()
{
    return get_stylesheet_directory_uri().'/images/beat_icons/1111.png';
}

function get_icons_combinations()
{
    $icons_array = array();
    for($i=0; $i<16; $i++)
    {
        $icons_array[] = str_pad(decbin($i), 4, "0", STR_PAD_LEFT);
    }

    return $icons_array;
}

function get_all_icons_details()
{
    $details = '';
    $icon_description_array = array(

        '1010-ex' => 'Preview article from the beat writer.<br />Recap article from the beat writer.',
        '0101-ex' => 'Preview article from the opposing team&#39;s beat writer.<br />Recap article from the opposing team&#39;s beat writer.'

    );

    foreach($icon_description_array as $key=>$value){
        $details .= '<span class="beat_icon_tool"><img class="beat_icon_tool" style="max-width:150px;" src="'.get_stylesheet_directory_uri().'/images/beat_icons/'.$key.'.png'.'" /><span class="beat_icon_tool_text">'.$value.'</span></span>';

    }

    return $details;
}

function get_beat_icon_detail($beat_icon, $this_team_name, $other_team_id)
{
    $other_team = get_post($other_team_id);
    $other_team_name = $other_team->post_title;

    $icon_name = pathinfo($beat_icon, PATHINFO_FILENAME);

    $details = '';
    if(strlen($icon_name) > 3)
    {
        $icon_name_array = str_split($icon_name);

        if($icon_name_array[0] == 0)
        {
            $details .= $this_team_name . ' preview is not available. ';
        } elseif($icon_name_array[0] == 1)
        {
            $details .= $this_team_name . ' preview is available. ';
        }

        if($icon_name_array[1] == 0)
        {
            $details .= $other_team_name . ' preview is not available. ';
        } elseif($icon_name_array[1] == 1)
        {
            $details .= $other_team_name . ' preview is available. ';
        }

        if($icon_name_array[2] == 0)
        {
            $details .= $this_team_name . ' recap is not available. ';
        } elseif($icon_name_array[2] == 1)
        {
            $details .= $this_team_name . ' recap is available. ';
        }

        if($icon_name_array[3] == 0)
        {
            $details .= $other_team_name . ' recap is not available. ';
        } elseif($icon_name_array[3] == 1)
        {
            $details .= $other_team_name . ' recap is available. ';
        }

    }

    return $details;
}

function get_beat_page_link($game_id, $team_id)
{
    $args = array(
        'connected_type' => 'game_beat_to_game',
        'connected_items' => get_post($game_id),
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
                                'key'     => 'beat-type',
                                'value'   => 'preview',
                                'compare' => '=',
                            ),

                    ),
    );

    $beats = new WP_Query( $args );
    
    if($beats->post_count > 0)
    {
        return get_permalink($beats->posts[0]->ID) . "?ref=" . $team_id;
    }
    
    return get_permalink($game_id) . '?ref=' . $team_id;    
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

            $default_season_year = get_default_season_year($post->ID);

            $default_season = $default_season_year;

            $default_season_type = get_league_season_type($post);

            if($default_season_type == 'crossover')
            {
                $default_season = ($default_season_year . '-' . substr(($default_season_year + 1), 2, 2));
            }

            $season = (isset($_GET['season']) && !empty($_GET['season']))? $_GET['season'] : $default_season;

            $default_season_month_status = get_default_month_status($team_id);
            $monthfilter = (isset($_GET['month']))? $_GET['month'] : (($default_season_month_status)? date('M') : '');

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

        $teamID = $post->ID;
	$teamName = get_the_title();

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
?>

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
								<div style="float:left; max-width:500px;">
									<span class="teamHeading"><?php the_title(); ?></span>
									<div>
										<?php the_breadcrumb($post->ID);?>
									</div>
								</div>
								<div style="clear:both;"></div>

								<ul id="navlist" style=" margin-bottom:20px">
									<li><a href="<?php echo get_site_url();?>/team/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a></li>
									<li><a href="<?php echo get_site_url();?>/article/?t=<?php echo $post->post_name; ?>&tid=<?php the_ID(); ?>">Articles <span class="circleNum"><?= $articlesNum; ?></span></a></li>
									<li class="<?php if(strstr($_SERVER['REQUEST_URI'], 'game')) { echo " active "; } ?>"><a href="<?php echo get_site_url();?>/game/?t=<?php echo $post->post_name; ?>&tid=<?php the_ID(); ?>">The Beat <span class="circleNum"><?= count_team_beats($teamID); ?></span></a></li>
								</ul>
								<div style="clear:both;"></div>
								<span>Season:</span> <a class="season_dropdown_link" href="#" style="margin-right:8px;"><span class="profilenav_dname"><?php echo $season;?></span> <i class="fa fa-caret-square-o-down"></i></a>
								<span>Month:</span> <a class="month_dropdown_link" href="#" style="margin-right:8px;"><span class="profilenav_dname"><?php echo (empty($monthfilter))? 'All' : $monthfilter;?></span> <i class="fa fa-caret-square-o-down"></i></a>
								<span style="float: right;" class="beat_writer">
             <span class="beat_writer_tooltip">Beat Writer</span>
								<img class="beat_icon beat_icon_tooltip" src="<?php echo get_stylesheet_directory_uri().'/images/beat_icons/0000.png'; ?>" alt="" />

								<script>
									jQuery(document).ready(function () {
										jQuery(".beat_icon_tooltip").tooltipster({
											content: jQuery('<div class="tooltipster_content"><strong><b>Beat Detail</b></strong><?php echo get_all_icons_details();?></div>'),
											animation: 'fade',
											position: 'left',
											theme: 'tooltipster-noir',
											touchDevices: true,
											trigger: 'hover',
											maxWidth: '505',
											speed: '350',
											contentAsHTML: true
										});

										jQuery(".beat_writer_tooltip").tooltipster({
											content: jQuery('<div class="tooltipster_content">Signup here to be a Beat Writer for your favorite team. Express your opinions about the game, the opponent, the players and the game&#39;s ultimate outcome. Build your brand and earn some money&excl;<br/>&ast;Beat Writers are required to write both a preview and recap article.</div>'),
											animation: 'fade',
											position: 'left',
											theme: 'tooltipster-noir',
											touchDevices: true,
											trigger: 'hover',
											maxWidth: '450',
											speed: '350',
											contentAsHTML: true
										});
									});
								</script>
								</span>

								<div id="season_dropdown_con" class="season_dropdown_pop_over">

									<div class="pop-over-content" style="max-height: 301px;">
										<div>
											<?php

                        $minyear = ($default_season_year - 1);
                        $maxyear = ($default_season_year + 0);

                        while($minyear <= $maxyear)
                        {
                            $seasonvalue= $minyear;
                            if($default_season_type != 'crossover')
                            {
                                ?>
												<a class="season_option <?php echo ($season == $seasonvalue)? 'active' : '';?>" href="<?php echo get_site_url(); ?>/game?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $seasonvalue;?>">
													<?php echo $seasonvalue;?>
												</a>
												<?php
                            } else
                            {
                                $seasonvalue = ($minyear . '-' . substr(($minyear + 1), 2, 2));
                                ?>
													<a class="season_option <?php echo ($season == $seasonvalue)? 'active' : '';?>" href="<?php echo get_site_url(); ?>/game?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $seasonvalue;?>">
														<?php echo $seasonvalue;?>
													</a>
													<?php
                            }
                            $minyear++;
                        }

                        ?>

										</div>
									</div>
								</div>


								<div id="month_dropdown_con" class="month_dropdown_pop_over">

									<div class="pop-over-content" style="max-height: 301px;">
										<div>
											<a class="season_option <?php echo (empty($monthfilter))? 'active' : '';?>" href="<?php echo get_site_url(); ?>/game?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $season;?>&month=">All</a>
											<?php

                        $mi = 1;

                        while($mi <= 12)
                        {
                            ?>
												<a class="season_option <?php echo (strtolower($monthfilter) == strtolower(date(" M ", mktime(0, 0, 0, $mi, 1))))? 'active' : '';?>" href="<?php echo get_site_url(); ?>/game?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $season;?>&month=<?php echo date(" M ", mktime(0, 0, 0, $mi, 1));?>">
													<?php echo date("M", mktime(0, 0, 0, $mi, 1));?>
												</a>
												<?php
                            $mi++;
                        }

                        ?>

										</div>
									</div>
								</div>

								<script>
									jQuery(document).ready(function () {
										jQuery(".season_dropdown_link").click(function () {
											jQuery(".season_dropdown_pop_over").toggle("slow");
										});

										jQuery(document).click(function (e) {
											if (e.target.class != 'season_dropdown_pop_over' && !jQuery('.season_dropdown_pop_over').find(e.target).length) {
												jQuery(".season_dropdown_pop_over").hide("slow");
											}
										});

										//For montths dropdown
										jQuery(".month_dropdown_link").click(function () {
											jQuery(".month_dropdown_pop_over").toggle("slow");
										});

										jQuery(document).click(function (e) {
											if (e.target.class != 'month_dropdown_pop_over' && !jQuery('.month_dropdown_pop_over').find(e.target).length) {
												jQuery(".month_dropdown_pop_over").hide("slow");
											}
										});


									});
								</script>

								<style>
									.season_dropdown_pop_over,
									.month_dropdown_pop_over {
										position: absolute;
										background: #FFF;
										display: none;
										width: 170px;
										border: 1px solid;
										margin-left: 1px;
										z-index: 1;
									}
									
									.month_dropdown_pop_over {
										margin-left: 60px;
									}
									
									.season_option {
										padding: 10px;
										float: left;
									}
									
									.season_option.active {
										color: #0f2d69;
										font-weight: bold;
									}
									
									.month_dropdown_pop_over .season_option {
										min-width: 48px;
									}
									
									img.beat_icon {
										height: 20px;
										margin-right: 1px;
									}
									
									img.sm_beat_icon {
										height: 35px;
										margin-right: 1px;
										width: auto;
									}
									
									img.beat_icon_tooltip {
										margin-bottom: 1px;
										height: 15px;
									}
									
									span.beat_icon {
										margin-left: 13px;
									}
									
									img.beat_icon_tool {
										height: 70px;
										float: left;
									}
									
									span.beat_icon_tool {
										float: left;
										max-width: 505px;
										margin-bottom: 20px;
										display: table;
									}
									
									img.info-icon {
										width: 20px;
									}
									
									.beat_icon_tool_text {
										vertical-align: middle;
										display: table-cell;
										line-height: 23px !important;
									}
									
									.beat-entries {
										min-height: 200px;
									}
									
									@media (max-width: 768px) {
										.entry-header {
											padding-left: 5px;
										}
										.beat-entries {
											padding-left: 5px;
										}
									}
								</style>

								<div style="clear:both;"></div>
							</header>
							<div class="beat-entries">
								<?php


    $beat_entries = filter_posts($connected, $monthfilter);

    if (count($beat_entries) > 0):


    ?>

									<div class="x-container max width " style="width:100%;">
										<div>
											<table>
												<?php
    foreach($beat_entries as $entry):

    $connectedTeams = new WP_Query(
        array(
                'connected_type' => 'games_to_teams',
                'connected_items' => $entry,
                'nopaging' => true
        )
    );
?>
													<tr>
														<td style="width:15%;">
															<?php
      $otherTeamID = get_other_team_id($teamID, $connectedTeams);
      $beat_icon = get_beat_icon($entry->ID, $teamID, $otherTeamID);
      ?>

																<a href='<?php echo get_beat_page_link($entry->ID, $teamID); ?>'><?php echo date("D,", $entry->meta_game_date); ?><br class="hidden-md-up" />
      <?php echo date("M j", $entry->meta_game_date); ?></a>
														</td>

														<td style="width:28%;">

															<?php
    $is_home_team = (get_team_id($entry->meta_home_team_name, $entry->meta_home_team_slug, $connectedTeams, $teamID) == $teamID);

    if(!$is_home_team)
    {
        ?>
																at <a href="<?php echo get_team_link($entry->meta_home_team_name, $entry->meta_home_team_slug, $connectedTeams, $teamID) .'&season=' . $season . '&month=' . $monthfilter;?>"><?php echo $entry->meta_home_team_name; ?></a>
																<?php

    } else
    {
        ?>
																	vs <a href="<?php echo get_team_link($entry->meta_away_team_name, $entry->meta_away_team_slug, $connectedTeams, $teamID) . '&season=' . $season . '&month=' . $monthfilter;?>"><?php echo $entry->meta_away_team_name; ?></a>
																	<?php
    }
    ?>
														</td>

														<td style="text-align:center; width: 28%;">

															<?php

   $is_past_game = (trim($entry->meta_home_team_score)!='' && trim($entry->meta_away_team_score)!='');

   if($is_past_game && $is_home_team && $entry->meta_home_team_score > $entry->meta_away_team_score)
   {
       echo 'W ';
   }
   else if($is_past_game && !$is_home_team && $entry->meta_away_team_score > $entry->meta_home_team_score)
   {
       echo 'W ';
   } 
   else if($is_past_game && !empty($entry->meta_away_team_score) && !empty ($entry->meta_home_team_score) && ($entry->meta_home_team_score == $entry->meta_away_team_score) )
   {    
       echo 'D ';
       
   } else if ($is_past_game && $entry->meta_home_team_score == '0' && $entry->meta_away_team_score == '0'){
       
       echo 'D ';
       
   }else if(empty($entry->meta_away_team_score) && empty ($entry->meta_home_team_score)){
//       echo ' - ';
   }
   else if($is_past_game)
   {
       echo 'L ';
   }

   if($is_past_game)
   {
   ?>
																<span style='font-weight:bold; color:#333333;'><?php echo max($entry->meta_home_team_score, $entry->meta_away_team_score); ?></span>
																<?php echo " - "; ?>
																	<span><?php echo min($entry->meta_home_team_score, $entry->meta_away_team_score); ?></span>
																	<br />
																	<?php
    }
   ?>
																		<?php echo $entry->meta_game_type;?>
														</td>

														<td style="text-align:center; width: 20%;">

															<?php
      $beatwriter_user_id = get_post_meta($entry->ID, 'beatwriter_user_team'.$teamID, 1);
      $beat_price = get_game_price($entry->ID, $teamID);

      $is_upcoming_date = (($entry->meta_game_date + 32400) > strtotime('-8 hours'));

      
        $connectedBeats = new WP_Query(
            array(
                'connected_type' => 'game_beat_to_game',
                'connected_items' => get_post($entry->ID),
                'nopaging' => true,
                'post_type' => 'game_beat',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key'     => 'team-id',
                        'value'   => $teamID,
                        'compare' => '=',
                    ),
                ),
            )
        );
        
      if(!empty($current_user->ID) && $beatwriter_user_id == $current_user->ID)
      {
        $post_beat_text = 'Post Beats';
        if($connectedBeats->post_count == 2) $post_beat_text = '<img class="sm_beat_icon" src="'.$beat_icon.'" alt="" />';
        elseif($connectedBeats->post_count == 1) $post_beat_text = 'Post Beat';
          
        ?>
																<a href="<?php echo get_beat_page_link($entry->ID, $teamID); ?>">
																	<?php echo $post_beat_text;?>
																</a>
																<?php
      } elseif( !empty($beatwriter_user_id) )
      {
        
        if($connectedBeats->post_count == 0)
        {
            $beatwriter_info = get_userdata($beatwriter_user_id);
            ?><a href="<?php echo get_site_url(). " /profile/ ".$beatwriter_info->user_login; ?>"><?php echo $beatwriter_info->user_login;?></a>
																	<?php
        }
        else
        {
            ?><a href="<?php echo get_beat_page_link($entry->ID, $teamID); ?>"><?php echo '<img class="sm_beat_icon" src="'.$beat_icon.'" alt="" />';?></a>
																		<?php
        }
      } elseif(!empty($current_user->ID) && $is_upcoming_date)
      { ?>
																			<!--<a href="<?php echo get_site_url(); ?>/beat-checkout/?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $season;?>&month=<?php echo $monthfilter;?>&game_id=<?php echo $entry->ID;?>">Be the Beat $<?php echo $beat_price;?></a>-->
																			<a href="<?php echo get_site_url(); ?>/be-a-beat-writer/?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $season;?>&month=<?php echo $monthfilter;?>&game_id=<?php echo $entry->ID;?>">Be the Beat $<?php echo $beat_price;?></a>
																			<?php } elseif(empty($current_user->ID) && $is_upcoming_date) {
            $return_url = get_site_url() . '/be-a-beat-writer/?t=' . $_GET['t'] . '&tid=' . $_GET['tid'] . '&season=' . $season . '&month=' . $monthfilter . '&game_id=' . $entry->ID;
            $login_url = get_site_url(). "/profile/login?redirect_to=".urlencode($return_url);
            ?>
																				<a href="<?php echo $login_url;?>">Be the Beat $<?php echo $beat_price;?></a>
																				<?php
      } else
      {
          if($beat_icon)
          {
              ?><a href="<?php echo get_beat_page_link($entry->ID, $teamID); ?>"><?php echo '<img class="sm_beat_icon" src="'.$beat_icon.'" alt="" />';?></a>
																					<?php
          } else
          {
              echo "<strong>-</strong>";
          }
      }
      ?>
														</td>

													</tr>
													<?php



    //endwhile;
endforeach;

  else:

    if(!empty($monthfilter) && !isset($_GET['month'])){
        $url = get_site_url() . '/game/?t=' . $_GET['t'] . '&season=' . $season . '&month=';
        ?>
														<script>
															window.location = '<?php echo $url;?>';
														</script>
														<?php
    }

    echo '<div style="clear:both;"></div> ';
    echo "There are no scheduled games in our archive for this team.";

    endif;

    wp_reset_postdata(); // set $post back to original post


?>
											</table>
										</div>
									</div>

							</div>


							<?php

}
} else {

	status_header(404);
	nocache_headers();
	include( get_404_template() );
	exit;

}


?>