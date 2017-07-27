<?php

// =============================================================================
// VIEWS/INTEGRITY/WP-PAGE.PHP
// -----------------------------------------------------------------------------
// Single page output for Integrity.
// =============================================================================

?>

<?php get_header(); ?>

    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width offset">

            <ul class="x-column x-sm x-1-3 single-sidebar">
                <li><a href="<?php echo home_url(); ?>/about-us/">About</a></li>
                <li><a href="<?php echo home_url(); ?>/#">How It Works</a></li>
                <li><a href="<?php echo home_url(); ?>/terms-2">Terms & Conditions</a></li>
                <li><a href="<?php echo home_url(); ?>/privacy">Privacy Policy</a></li>
                <li><a href="<?php echo home_url(); ?>/#">Contact</a></li>
            </ul>

            <div class="x-column x-sm x-2-3 last content">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php x_get_view( 'integrity', 'content', 'page' ); ?>
                    <?php x_get_view( 'global', '_comments-template' ); ?>
                <?php endwhile; ?>
            </div>

        </div>

    </div>

<?php get_footer(); ?>