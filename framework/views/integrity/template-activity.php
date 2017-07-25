<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-1.PHP (Container | Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

$args = array(
    'author'        =>  $current_user->ID,
    'orderby'       =>  'post_date',
    'order'         =>  'ASC',
    'posts_per_page' => -1
    );


$current_user_posts = get_posts( $args );


?>

<?php get_header(); ?>

<header>

 

  <div class="x-container max width " style="margin-top:20px;">
    <div class="x-main full" role="main"  style="padding:40px;">
		<h3 style="display:block; text-align:center; padding:20px; margin-bottom:20px; display:block; ">Your Activity</h3>
			<div style="width:100%; height:40px;">&nbsp;</div>
<?php foreach ($current_user_posts as $post) {
    $link = get_permalink($post->ID);
    
    echo '<a href="/?id='.$link.'">Title</a>';
    echo "<br />";
}
        
        ?>
        
        
        
	</div>
</div>

<?php get_footer(); ?>