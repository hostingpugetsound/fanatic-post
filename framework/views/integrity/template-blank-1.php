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


 <div class="entry-wrap" style="padding-top:0px;">

    <?php x_get_view( 'global', '_content' ); ?>
  </div> 
</article>

    </div>
  </div>

<?php get_footer(); ?>