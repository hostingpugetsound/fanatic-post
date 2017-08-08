<?php

// =============================================================================
// TEMPLATE NAME: User Profile
// -----------------------------------------------------------------------------
// User profile page
// =============================================================================



get_header();
?>

<div class="<?php x_main_content_class(); ?>" role="main">
    <div class="x-container max width offset">

        <div class="x-column x-sm x-1-2 content">
            <h2 class="red-header"></h2>
            <?php echo do_shortcode( '[userpro template=card]' ); ?>
            <hr />
            <?php x_get_view( 'global', '_user-beats' ); ?>
        </div>

        <div class="x-column x-sm x-1-2 last">
            <?php x_get_view( 'global', '_my-teams' ); ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>
