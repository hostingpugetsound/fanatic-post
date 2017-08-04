<?php

// =============================================================================
// single-league.PHP
// -----------------------------------------------------------------------------
// Handles output of a league's landing pages
// =============================================================================

?>

<?php get_header(); ?>

<script>
    var pageType = '<?php echo get_post_type();?>';
</script>

<div class="<?php x_main_content_class(); ?>" role="main">
    <div class="x-container max width">

        <?php x_get_view( 'global', '_sidebar-news' ); ?>

        <div class="x-column x-sm x-2-4 content">
            <h2>The Beat</h2>
            <?php x_get_view( 'global', '_content', 'the-beat' ); ?>

            <?php while( have_posts() ) : the_post(); ?>
                <?php x_get_view( x_get_stack(), 'template', 'common' ); ?>
                <?php x_get_view( 'global', '_comments-template' ); ?>
            <?php endwhile; ?>
        </div>

        <?php x_get_view( 'global', '_sidebar-be-the-beat' ); ?>

    </div>
</div>

<?php get_footer(); ?>
