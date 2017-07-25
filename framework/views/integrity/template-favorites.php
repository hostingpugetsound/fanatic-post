<?php

// =============================================================================

// Favorites are stored in the user meta table as individual items under the "favorites" meta_key with the post_id as the meta_value.
// =============================================================================

$user_id = get_current_user_id();
$favorites = get_user_meta($user_id, "favorites", false);

get_header(); ?>
  
  

  <div class="x-container max width mt20 inner-page-container">
    <div class="x-main full p40" role="main">
		<h3>Your Favorites</h3>
		
		<div class="favBtns">
		<?php 
        foreach ($favorites as $favorite) {
            
            $perma = get_permalink($favorite);
            $title = get_the_title($favorite);
            if ($title != 'Favorites') {
                echo "<a href='".$perma."' data-id='".$post_id."'>".$title."</a>";
            }
            
        }
        ?>
        </div>
	</div>
</div>

<?php get_footer(); ?>