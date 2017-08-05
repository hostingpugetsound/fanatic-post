<?php

// =============================================================================
// single-game.PHP
// -----------------------------------------------------------------------------
// Handles output of a single game/beat
// =============================================================================

?>


<?php get_header(); ?>
<script>
    var pageType = 'team';
</script>

<div class="<?php x_main_content_class(); ?>" role="main">
    <div class="x-container max width offset">

        <div class="x-column x-sm x-1-2 content">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php x_get_view( x_get_stack(), 'template', 'game' ); ?>
                <?php #x_get_view( 'integrity', 'content', 'page' ); ?>
            <?php endwhile; ?>
        </div>

        <div class="x-column x-sm x-1-2 last comments">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php x_get_view( 'global', '_comments-template' ); ?>
            <?php endwhile; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>
