<?php
global $current_user;

add_filter( 'the_author', 'expert_to_upper');
function expert_to_upper($content)
{
    return strtoupper($content);
}

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

function currentUrl() {
    $protocol 		= strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     			= $_SERVER['HTTP_HOST'];
    $script   			= $_SERVER['SCRIPT_NAME'];
    $params   		= $_SERVER['QUERY_STRING'];

    return $protocol . '://' . $host . $script . '?' . $params;
}

function show_filter_active_class($input)
{

    if(isset($_GET['filter']) && strtolower($_GET['filter']) == strtolower($input))
    {
        echo ' active ';
    } else if(!isset($_GET['filter']) && strtolower($input) == 'all')
    {
            echo ' active ';
    }
}

function show_sort_active_class($input, $orderby = FALSE)
{
    $orderby = explode(" ", trim($orderby));
    $orderby = $orderby[0];

    if(trim($orderby) == "date" && $_GET['sort_date'] == "oldest" && $input == 'oldest')
    {
        echo ' active ';
    } else if(trim($orderby) == "date" && $_GET['sort_date'] == "newest" && $input == 'newest')
    {
        echo ' active ';
    } else if(trim($orderby) == "comment_count" && $input == 'replies')
    {
        echo ' active ';
    } else if(trim($orderby) == "meta_value_num"  && $input == 'popular')
    {
        echo ' active ';
    }

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
    return $a->promoted - $b->promoted;
}

function sortarticles($posts, &$promoted_count) {

    $promoted_count = 0;

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
                
                $promoted_count++;
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
    'post_type' => array('team','person'),
    'name' => $t
);

$my_query = new WP_Query( $args );




	if ( $my_query->have_posts() ) {

		while ($my_query->have_posts()) {
			$my_query->the_post();

			// Find connected pages
			if ((isset($_GET['sort']) && $_GET['sort'] == "popular") || $no_filter) {
				$connected = new WP_Query(
					array(
						'meta_key' => 'dm_post_popularity_value',
						'orderby' => $orderby,
						'order' => $order,
						'connected_type' => 'articles_to_teams',
						'connected_items' => $post,
						'nopaging' => true
					)
				);

			} else{
				$connected = new WP_Query(
					array(
						'meta_key' => 'dm_post_popularity_value',
                                                'orderby' => $orderby,
						'order' => $order,
						'connected_type' => 'articles_to_teams',
						'connected_items' => $post,
						'nopaging' => true
					)
				);
			}


                        $connected->posts = sortarticles($connected->posts, $promoted_count);
//
//                        foreach($connected->posts as $post):
//                            $post->promtoed = 0;
//                        endforeach;

//                                                echo "<pre>";
//                        print_r($connected->posts);

		$articlesNum = $connected->post_count;


/*		// Set up Division and Leagues
		$dterms = get_the_terms( $post->ID , 'division' );

		foreach ( $dterms as $dterm ) {
			$dlink = get_term_link( $dterm );
			$division =  $dterm->name;
		}


		$lterms = get_the_terms( $post->ID , 'league' );

		foreach ( $lterms as $lterm ) {
			$llink = get_term_link( $lterm );
			$league =  $lterm->name;
		}*/

	$teamName = get_the_title();

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
                    <div><?php the_breadcrumb(get_the_ID());?></div>
		</div>
		<div style="clear:both;"></div>

	  <ul id="navlist" style=" margin-bottom:20px">
		<li><a href="<?php echo get_site_url();?>/team/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a></li>
		<li class="<?php if(strstr($_SERVER['REQUEST_URI'], 'article')) { echo "active"; } ?>"><a href="<?php echo get_site_url();?>/article/?t=<?php echo $post->post_name; ?>&tid=<?php the_ID(); ?>">Articles <span class="circleNum"><?= $articlesNum; ?></span></a></li>
          <?php $postType = get_post_type( $post );
          if ($postType = "team") {           ?>
		<li><a href="<?php echo get_site_url();?>/game/?t=<?php echo $post->post_name; ?>&tid=<?php the_ID(); ?>">The Beat <span class="circleNum"><?= count_team_beats(get_the_ID()); ?></span></a></li>
          <?php } ?>
	</ul>

    </header>


<?php

/*           SET UP SORT FILTER LINKS       */


			$serverURI = preg_replace("#&sort=.*#", '', $_SERVER['REQUEST_URI']);
			$serverURI = preg_replace("#&filter=.*#", '', $serverURI);

			if (isset($_GET['filter'])) {
				$filter = $_GET['filter'];
			} else {
				$filter = 'all';
			}

			if (isset($_GET['sort'])) {
				$sort = $_GET['sort'];
			} else {
				$sort = 'newest';
			}

			if (strpos($serverURI,'?') == false) { // Determine if a ? already exists in the URL
				$varStart = '?';
			} else {
				$varStart = '&';
			}

			$popularLink 	= $serverURI . $varStart . "sort=popular&filter=".$filter;
			$newestLink 	= $serverURI . $varStart . "sort_date=newest&filter=".$filter;
			$oldestLink 		= $serverURI . $varStart . "sort_date=oldest&filter=".$filter;
			$repliesLink 	= $serverURI . $varStart . "sort=replies&filter=".$filter;

			$allLink 			= $serverURI . $varStart . "sort=".$sort."&filter=all";
			$fanLink 			= $serverURI . $varStart . "sort=".$sort."&filter=fan";
			$foeLink 			= $serverURI . $varStart . "sort=".$sort."&filter=foe";





/*  END OF SORT FILTER LINKS SETUP */

?>


  <div class="x-container max width " style="width:100%;">
    <div >

		<div style="float:left; display:inline-block; width:400px; padding-top:0px;">
			<div style="float:left;">
				<div style="padding-right:20px; margin-top:20px;">

				<a class="<?php show_filter_active_class('all');?>" href="<?php echo $allLink  ?>">All</a>  / <a class="<?php show_filter_active_class('fan');?>" href="<?php echo $fanLink;  ?>">Fan Only</a> / <a class="<?php show_filter_active_class('foe');?>" href="<?php echo $foeLink  ?>">Foe Only</a></div>
				<a class="<?php show_sort_active_class('popular', $orderby);?>" href="<?= $popularLink  ?>">Popular</a> / <a class="<?php show_sort_active_class('newest', $orderby);?>" href="<?= $newestLink  ?>">Newest</a> / <a class="<?php show_sort_active_class('oldest', $orderby);?>" href="<?= $oldestLink  ?>">Oldest</a> / <a class="<?php show_sort_active_class('replies', $orderby);?>" href="<?= $repliesLink  ?>">Most Replied</a>
			</div>
		</div>
		<div style="float:right;">
      <span class="saySomething">Have something to say about the <?php echo $teamName; ?>?</span><br />
			
            <?php
            $pp_be_sports_writer = FALSE;
            $articleprepayment = get_option('articleprepayment' . $current_user->ID);
            $user_agreement = get_user_meta( get_current_user_id(), '_user_article_agreement_acceptance', 1 );
            $agreement_url = get_site_url(). "/article-agreement/?t=".$_GET['t']."&tid=".get_the_ID();
            
            if(!is_user_logged_in())
            {
                $agreement_url = get_site_url(). "/profile/login?redirect_to=".urlencode($agreement_url);
            } else if( $articleprepayment && $user_agreement )
            {
                $agreement_url = get_site_url() . '/create-a-post/?t=' . $_GET['t'] . '&tid=' . get_the_ID();
            } else if($user_agreement)
            {
                $pp_be_sports_writer = TRUE;
                include_once ABSPATH . 'pp_config.php';
        
                $pp_app_base_url = get_site_url();

                $current_url = urlencode($pp_app_base_url . '/article/?t='.$_GET['t'].'&tid='.get_the_ID());
                
                $notify_url = $pp_app_base_url . '/paypal.php?action=articleprepaymentnotify&password=7412581&current_url=' . $current_url;
                $return_url =  $pp_app_base_url . '/paypal.php?action=articleprepaymentsuccess&password=741258&current_url=' . $current_url;
                $cancel_url =  $pp_app_base_url . '/paypal.php?action=articleprepaymentcancel&current_url=' . $current_url;

                ?>

                <form name="pp_form_be_sportswriter" action="<?php echo PAYPAL_URL;?>" method="post">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="<?php echo PAYPAL_EMAIL;?>">
                <input type="hidden" name="item_name" value="Post Article">
                <input type="hidden" name="item_number" value="<?php echo $current_user->ID;?>">
                <input type="hidden" name="amount" value="3">
                <input type="hidden" name="tax" value="">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="address_override" value="1">
                <input type="hidden" name="notify_url" value="<?php echo $notify_url;?>">
                <input type="hidden" name="return" value="<?php echo $return_url;?>">
                <input type="hidden" name="cancel_return" value="<?php echo $cancel_url;?>">
                </form>      
                <?php
            }
            
            if($pp_be_sports_writer)
            {
                ?><a href="javascript:void(0)" class="x-btn  x-btn-round x-btn-x-small" onclick="document.pp_form_be_sportswriter.submit()">Be a Sportswriter $3</a><?php
            } else
            {
                ?><a href="<?php echo $agreement_url; ?>" class="x-btn  x-btn-round x-btn-x-small"><?php echo ($current_user->ID && get_option('articleprepayment' . $current_user->ID))? 'Post Your Article (1)': 'Be a Sportswriter $3'; ?></a>  <?php
            }
                ?>
            &nbsp;&nbsp;&nbsp; <?php echo do_shortcode('[tooltip theme="tooltipster-noir" title="Be a Sportswriter" text="Think you have what it takes to be a trusted voice in sports? Look no further, we are looking for fanatics just like you. Post an article here, on this page, and share in the revenue that your individual article page produces."]<img class="be-info-icon" src="'.get_stylesheet_directory_uri().'/images/icon-info_30_x_30.png'.'" alt="" />[/tooltip]'); ?>
		</div>
		<div style="clear:both;">&nbsp;</div>

<?php
if($connected->have_posts())
{
    while ( $connected->have_posts() ) : $connected->the_post();

	$fanStatus = get_post_meta( get_the_ID(), 'wpcf-fan-status', true );
  $promoted = get_post_meta($post->ID, 'promoted', true);

  if (!empty($promoted)) {

      $hours = (time() - $promoted) / 3600;

      if($hours > 72)
      {
          $promoted = false;
      } else
      {
        $promoted = true;
      }
    }

	if (isset($_GET['filter'])) {
		$filter = $_GET['filter'];
		if ($filter == 'all') {
			$showcomment = true;
		} else if ($filter == $fanStatus) {

			$showcomment = true;
		} else {
			$showcomment = false; 

		}

	} else {
		$filter = 'all';
		$showcomment = true;
	}


  	if ($showcomment == true) {

      if ($promoted == true) {

          echo '<div class="post articlesPost promotedArticle">'      ;
          $imgWidth = 900;
          $imgHeight = 507;

      } else {

        echo '<div class="post articlesPost" style="position:relative">'      ;
        $imgWidth = 300;
        $imgHeight = 169;

      }

?>


<?php
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
		/*
		If there is a thumbnail we are going to grab it and use the timthumb script to resize it.
		*/
			$rawthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );



			$imgSrc = "/wp-content/plugins/userpro/lib/timthumb.php?src=".$rawthumb[0]."&h=".$imgHeight."&w=".$imgWidth."&zc=1";

		} else {
		/*
			Uh oh, we don't have a thumbnail. Good thing that we have a default image ready to go.
		*/
			$themeDirectory =  get_stylesheet_directory_uri();
			$imgSrc = "/wp-content/plugins/userpro/lib/timthumb.php?src=".$themeDirectory . "/framework/img/global/article-no-image.jpg"."&h=".$imgHeight."&w=".$imgWidth."&zc=1";
		}

?>

<div id="container">

	<div >

		<a href="<?php the_permalink() ?>"><img src="<?php echo $imgSrc; ?>"></a>
	</div>

<span class="avatarContainer"><?php // echo get_avatar( get_the_author_meta( 'ID' ), 64 ); ?> <small style=""> <?php the_author() ?> </small><?php if (!is_null($fanStatus)) { echo ' / '. strtoupper($fanStatus); } ?></span>
<br />

</div>



<p><span style="font-size:18px; margin-top:15px;"><a class="articlesTitle" href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></span><br /><?php //the_time('F jS, Y') ?></p>
<p><?php the_excerpt(); ?></p>

<?php /*	 <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="x-btn  x-btn-round x-btn-x-small">Read Article</a><span style="margin-left:10px;"> (<?php comments_number(); ?>)</span> */ ?>

         <?php





           $pp_app_base_url = get_site_url();

           $current_url = $pp_app_base_url . '/article/?t=' . $_GET['t'];

           $notify_url = $pp_app_base_url . '/paypal.php?action=articlenotify&password=7412581&current_url=' . $current_url;
           $return_url =  $pp_app_base_url . '/paypal.php?action=articlesuccess&password=741258&current_url=' . $current_url;
           $cancel_url =  $pp_app_base_url . '/paypal.php?action=articlecancel&current_url=' . $current_url;

           include_once ABSPATH . 'pp_config.php';
           
       ?>

         <?php if(!$post->promoted && $current_user->ID == $post->post_author && $promoted_count < 3): ?>
            <form action="<?php echo PAYPAL_URL;?>" method="post" style="position: absolute; bottom: -50px;">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="<?php echo PAYPAL_EMAIL;?>">
                <input type="hidden" name="item_name" value="Promote Article">
                <input type="hidden" name="item_number" value="<?php echo $post->ID;?>">
                <input type="hidden" name="amount" value="3">
                <input type="hidden" name="tax" value="">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="address_override" value="1">
                <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
                <input type="hidden" name="return" value="<?php echo $return_url; ?>">
                <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
                <input type="submit" class="promoteBtn" value="Promote this Article $3" />

                &nbsp;&nbsp;&nbsp; <?php echo do_shortcode('[tooltip theme="tooltipster-noir" title="Promote Your Article" text="By promoting your article, this article will appear above all other articles on this page, except other promoted article posts on this page, of which there can be a maximum total of 3 promoted article posts at any given time. We will also place a link to your promoted article on this teams Arena page. This promotion will last for 3 days (72 Hours), at which time, your article will be released back into the general population of articles specific to this team."]<img class="pr-info-icon" width="20" src="'.get_stylesheet_directory_uri().'/images/icon-info_30_x_30.png'.'" alt="" />[/tooltip]'); ?>
            </form>
         <?php endif; ?>

</div>

	  <?php

	}
    endwhile;

    wp_reset_postdata(); // set $post back to original post
} else
{
  ?>
      <div class="articles-not-found">&nbsp;</div>
  <?php    
}


?>


    </div>
  </div>

<?php

}
} else {

	//print_r($my_query);
//
//	status_header(404);
//	nocache_headers();
//	include( get_404_template() );
//	exit;

}


?>

<script>
    jQuery(document).ready(function($) {
        $('.tooltip').tooltipster();
    });
</script>
<style>
    .articles-not-found {
        margin: 20px;
        height: 100px;
    }
    .articlesPost .more-link {
        display: none;
    }
    
    img.be-info-icon{
        width: 30px;
    }
    
    img.pr-info-icon{
        width: 20px;
    }
</style>