<?php

// =============================================================================
// TEMPLATE NAME: Move Teams
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
//
// Content is output based on which Stack has been selected in the Customizer.
// To view and/or edit the markup of your Stack's index, first go to "views"
// inside the "framework" subdirectory. Once inside, find your Stack's folder
// and look for a file called "template-blank-1.php," where you'll be able to
// find the appropriate output.
// =============================================================================


 $offset = $_GET['offset'];

$type = 'team';
$args=array(
  'post_type' => $type,
  'post_status' => 'publish',
  'posts_per_page' => 50,
  'caller_get_posts'=> 1,
  'offset'=> $offset
  );

$my_query = null;
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {
  while ($my_query->have_posts()) : $my_query->the_post(); 
  

// get the parent meta name

$parentName = get_post_meta($post->ID, 'parent_page', 1);

$parent = get_page_by_title($parentName, OBJECT, 'team');

if ($parent->ID) {
		$my_post = array(
			'ID' => $post->ID,
			'post_parent' => $parent->ID
		);
		
		  wp_update_post( $my_post );

}

echo $parentName . '( '.$parent->ID.' ) / ' . $post->ID . '<br />';

// look for another team with that name

// for each match get the idea of parent

// update parent of initial team

// clear all params

endwhile;
}

?>

