<?php

// =============================================================================
// VIEWS/INTEGRITY/CONTENT.PHP
// -----------------------------------------------------------------------------
// Standard post output for Integrity.
// =============================================================================

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <div class="entry-wrap">
    <?php x_get_view( 'integrity', '_content', 'post-header' ); ?>
    <div class="entry-featured">
      <?php x_featured_image(); ?>
    </div>
    <?php x_get_view( 'global', '_content' ); ?>
  </div>
  <?php x_get_view( 'integrity', '_content', 'post-footer' ); ?>
</article>
