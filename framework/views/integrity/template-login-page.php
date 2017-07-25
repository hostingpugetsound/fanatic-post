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

 

  <div class="x-container max width " style="margin-top:20px;">
    <div class="x-main full" role="main">

              


 

    <?php x_get_view( 'global', '_content' ); ?>
  </div> 


 
  </div>

<?php get_footer(); ?>