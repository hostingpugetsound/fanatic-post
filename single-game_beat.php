<?php

// =============================================================================
// sing-beat_game.PHP
// -----------------------------------------------------------------------------
// To show single beat
// =============================================================================

?>

<?php get_header(); ?>
<script>
    var pageType = 'game_beat';
</script>

<div class="<?php x_main_content_class(); ?>" role="main">
    <div class="x-container max width offset">

        <div class="x-column x-sm x-1-2 content">
            <h2 class="red-header">The Beat</h2>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php x_get_view( x_get_stack(), 'template', 'game-beat' ); ?>
                <?php #x_get_view( 'integrity', 'content', 'page' ); ?>
            <?php endwhile; ?>
        </div>

        <div class="x-column x-sm x-1-2 last comments">
            <h2 class="red-header">The Arena</h2>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php x_get_view( 'global', '_comments-template' ); ?>
            <?php endwhile; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>

