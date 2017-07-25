<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-1.PHP (Container | Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

?>

<?php get_header(); ?>

<header>



  <div class="x-container max width ">
    <div class="x-main full" role="main">


<article id="post-22" class="post-22 page type-page status-publish hentry no-post-thumbnail">
  <div class="entry-featured">
      </div>
              <header class="entry-header">
	  <h2 style="display:block; text-align:center; padding:20px;"><?php the_title(); ?></h2>
          </header>

 <div class="entry-wrap" >

   <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>

             <?php endwhile; endif; ?>
  </div>
</article>

    </div>
  </div>

<?php get_footer(); ?>
