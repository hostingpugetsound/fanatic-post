<?php

// =============================================================================
// archive-article.PHP
// -----------------------------------------------------------------------------
// Handles output of teams article pages
// =============================================================================



if (!isset($_GET['t'])) {

	status_header(404);
	nocache_headers();
	include( get_404_template() );
	exit;

}

?>

<?php get_header(); ?>

  <div class="x-container max width offset">
    <div class="<?php x_main_content_class(); ?>" role="main">
<?php x_get_view( x_get_stack(), 'template', 'team-games' ); ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>
