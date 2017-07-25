<?php

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

get_header(); 




if(isset($_SESSION['pp_success_message'])):?>
                <div class="alert-success">
                    <?php echo $_SESSION['pp_success_message']; unset($_SESSION['pp_success_message']); ?>
                </div>
            <?php endif;?>

            <?php if(isset($_SESSION['pp_failure_message'])):?>
            <div class="alert-danger">
                <?php echo $_SESSION['pp_failure_message']; unset($_SESSION['pp_failure_message']); ?>
            </div>
            <?php endif;?>



<?php
    $connected = new WP_Query( array(
        'meta_key' => 'dm_post_popularity_value',
        'connected_type' => 'articles_to_people',
        'connected_items' => $post,
        'nopaging' => true
    ) );
$articlesNum = $connected->post_count;


// Find connected pages
$connectedTeam = new WP_Query( array(
  'connected_type' => 'people_to_teams',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
) );

// Display connected pages
if ( $connectedTeam->have_posts() ) :
    while ( $connectedTeam->have_posts() ) : $connectedTeam->the_post(); 
        $teamName = get_the_title(); 
        $teamLink = get_permalink();
    endwhile; 

// Prevent weirdness
wp_reset_postdata();

endif;


?>

       <header class="entry-header">

		<div style="float:left; max-width:500px;">
			<span class="teamHeading"><?php the_title(); ?></span>
                        <a style="color:inherit" href="<?php echo $teamLink;?>" title="<?php echo $teamName; ?>"><span class="entry-date"><?php echo $teamName; ?></span></a>
		</div>     

<div style="clear:both;"></div>
	  <ul id="navlist">
		  <li class="<?php if(strstr($_SERVER['REQUEST_URI'], 'people')) { echo "active"; } ?>">
                      <a href="<?php echo get_site_url();?>/people/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a>
      </li>
		  <li>
        <a href="<?php echo get_site_url();?>/article/?p=<?php echo $post->post_name; ?>&ptype=people">Articles <span class="circleNum"><?= $articlesNum; ?></span></a>
      </li>

	  </ul>

          </header>


<script>
jQuery(document).ready(function($) {
  $('.wc-reply').hide();
  $('.wc-toggle').text('Show Replies ∨');
})
</script>
