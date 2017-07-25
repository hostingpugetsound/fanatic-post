<?php

// =============================================================================
// sing-team.PHP
// -----------------------------------------------------------------------------
// Handles output of teams landing pages
// =============================================================================

?>

<?php get_header(); ?>

  <div class="x-container max width offset">
    <div class="<?php x_main_content_class(); ?>" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
<?php  x_get_view( x_get_stack(), 'template', 'team-landing' ); ?>
<?php  x_get_view( 'global', '_comments-template' ); ?>



      <?php endwhile; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>
