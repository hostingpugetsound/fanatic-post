<?php


$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

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
    return $a->promoted - $b->promoted;
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

    return $promoted_posts;
}

?>

<?php


$t = $post->post_name;


$args = array(
    'post_type' => 'team',
    'name' => $t
);
//$my_query = new WP_Query( $args );
$my_query = new WP_Query( 'post_type=team&name='.$t);




	if ( $my_query->have_posts() ) {


		while ($my_query->have_posts()) {
			$my_query->the_post();

			// Find connected pages

				$connected = new WP_Query(
					array(
						'meta_key' => 'promoted',
                                                'orderby' => 'meta_value_num',
						'order' => 'DESC',
                                                'groupby' => 'ID',
						'connected_type' => 'articles_to_teams',
						'connected_items' => $post,
						'nopaging' => FALSE,
                                                'posts_per_page' => 3
					)
				);



$connected->posts = sortarticles($connected->posts);
  $teamName = get_the_title();

?>




<?php if(count($connected->posts) > 0):?>
<div class="articles-sidebar-container">
<?php
    while ( $connected->have_posts() ) : $connected->the_post();

    $promoted = get_post_meta($post->ID, 'promoted', true);

    if (!empty($promoted)) {

	$fanStatus = get_post_meta( get_the_ID(), 'wpcf-fan-status', true );


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

?>

		<div class="post articlesPost" style="height:inherit; width: 100%; margin:0;">

<?php
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
		/*
		If there is a thumbnail we are going to grab it and use the timthumb script to resize it.
		*/
			$rawthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
			$imgSrc = "/wp-content/plugins/userpro/lib/timthumb.php?src=".$rawthumb[0]."&h=169&w=300&zc=1";

		} else {
		/*
			Uh oh, we don't have a thumbnail. Good thing that we have a default image ready to go.
		*/
			$themeDirectory =  get_stylesheet_directory_uri();
			$imgSrc = $themeDirectory . "/framework/img/global/article-no-image.jpg";
		}

?>


<div id="container">

	<div>

		<a href="<?php the_permalink() ?>"><img src="<?php echo $imgSrc; ?>"></a>
	</div>

<span> <small style=""> <?php the_author() ?> </small><?php if (!is_null($fanStatus)) { echo ' / '. strtoupper($fanStatus); } ?></span>
<br />

</div>


<p><span style="font-size:18px; margin-top:15px;"><a class="articlesTitle" href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></span></p>
<p><?php the_excerpt(); ?></p>



</div>

	  <?php

	}

}
    endwhile;

    wp_reset_postdata(); // set $post back to original post


?>
    
</div>
<?php
endif;
}
}


?>