<?php

// =============================================================================
// sing-team.PHP
// -----------------------------------------------------------------------------
// Handles output of teams landing pages
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
            <?php x_get_view( 'global', '_content', 'the-beat' ); ?>

            <?php #while( have_posts() ) : the_post(); ?>
                <?php #x_get_view( x_get_stack(), 'template', 'team-landing' ); ?>
            <?php #endwhile; ?>
        </div>

        <?php x_get_view( 'global', '_sidebar-be-the-beat' ); ?>

    </div>
    <div class="x-container max width">
        <?php x_get_view( 'global', '_comments-template' ); ?>
    </div>
</div>

<?php get_footer(); ?>