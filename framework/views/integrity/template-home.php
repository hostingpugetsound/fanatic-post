<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-1.PHP (Container | Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );
  
?>  

<?php get_header(); ?>

    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width">

            <?php x_get_view( 'global', '_sidebar-news' ); ?>

            <div class="x-column x-sm x-2-4 content">
                <h2>The Beat</h2>
                <?php x_get_view( 'global', '_content', 'the-beat' ); ?>

                <?php #while ( have_posts() ) : the_post(); ?>
                    <?php #x_get_view( 'integrity', 'content', 'page' ); ?>
                <?php #endwhile; ?>
            </div>

            <?php x_get_view( 'global', '_sidebar-be-the-beat' ); ?>

        </div>
    </div>

<?php get_footer(); ?>