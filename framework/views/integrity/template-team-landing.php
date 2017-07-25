<?php


$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

?>

<?php get_header(); ?>
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

		<div style="width:100%;">
                    <span class="teamHeading"><?php the_title(); ?><a class="pull-right" id="favThis" data-id="<?php echo the_ID(); ?>"><i class="fa fa-plus"></i> Add to Favorites</a></span>
                        <div><?php the_breadcrumb(get_the_ID());?></div>
		</div>


<?php
    $connected = new WP_Query( array(
        'meta_key' => 'dm_post_popularity_value',
        'connected_type' => 'articles_to_teams',
        'connected_items' => $post,
        'nopaging' => true
    ) );
$articlesNum = $connected->post_count;

?>


<div style="clear:both;"></div>
	  <ul id="navlist">
		  <li class="<?php if(strstr($_SERVER['REQUEST_URI'], 'team')) { echo "active"; } ?>">
                      <a href="<?php echo get_site_url();?>/team/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a>
      </li>
		  <li>
        <a href="<?php echo get_site_url();?>/article/?t=<?php echo $post->post_name; ?>">Articles <span class="circleNum"><?= $articlesNum; ?></span></a>
      </li>
		  <li>
        <a href="<?php echo get_site_url();?>/game/?t=<?php echo $post->post_name; ?>">The Beat <span class="circleNum"><?= count_team_beats(get_the_ID()); ?></span></a>
      </li>
	  </ul>

          </header>


<script>
jQuery(document).ready(function($) {
  $('.wc-reply').hide();
  $('.wc-toggle').text('Show Replies ∨');
    
    
    
    
    
})
</script>

				
				<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
				<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/favorites.js"></script>
