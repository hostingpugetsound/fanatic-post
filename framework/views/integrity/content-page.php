<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT-PAGE.PHP
// -----------------------------------------------------------------------------
// Standard page output for Integrity.
// =============================================================================

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="entry-featured">
    <?php x_featured_image(); ?>
  </div>
        <?php if ( $disable_page_title != 'on' ) : ?>
	
<?php // get meta values 

$post_id = get_the_ID();

$headline = get_post_meta( $post_id, 'wpcf-headline', true );
$description = get_post_meta( $post_id, 'wpcf-description', true );
$show_breadcrumbs = get_post_meta( $post_id, 'wpcf-hide-breadcrumbs', true );

?>
	
      <header class="entry-header">

	  
<?php
// Breadcrumbs		
		if ($hide_breadcrumbs != true) { 
			if ( function_exists('yoast_breadcrumb') ) {
				yoast_breadcrumb('<div id="breadcrumbs">','</div>');
			}
		}
?>

      </header>
		

      <?php endif; ?>
	  <?php if (is_front_page()) { ?>
 <div class="entry-wrap"  style="padding-top:0px !important;">
    <?php //x_get_view( 'global', '_content' ); ?>
	<?php

$defaults = array(
	'theme_location'  => 'homepage',
	'menu'            => '',
	'container'       => 'div',
	'container_class' => '',
	'container_id'    => '',
	'menu_class'      => 'menu',
	'menu_id'         => '',
	'echo'            => true,
	'fallback_cb'     => 'wp_page_menu',
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => ''
);

//wp_nav_menu( $defaults );

x_get_view( 'global', '_content' ); 

?>
  </div> 
	<?php } ?>
	  <?php if (is_page('login') || is_page('profile') || is_page('register') || is_page('arena') || is_page('articles') || is_page('beats')) { ?>
 <div class="entry-wrap" >
    <?php x_get_view( 'global', '_content' ); ?>
  </div> 
	<?php } ?>	
</article>