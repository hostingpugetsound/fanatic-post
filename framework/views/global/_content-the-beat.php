<?php

// =============================================================================
// VIEWS/GLOBAL/_CONTENT-THE-BEAT.PHP
// -----------------------------------------------------------------------------
// Page for outputting "The Beat"
// =============================================================================

$args = array(
    'post_type' => 'game',
    'posts_per_page' => 10
);



if( is_front_page() )
    $query = new WP_Query( $args );
else
    $query = $wp_query;
#vard($query);

if ( $query->have_posts() ) :
    $i = 0;
?>

    <?php
    while ( $query->have_posts() ) :
        $query->the_post();
        $i++;
        # adds the 1/2 columns and 'last' class
        if( $i > 1 ) {
            $class = 'x-column x-sm x-1-2';
            if( $i % 2 == 0 )
                $class .= ' last';
        } else {
            $class = '';
        }
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
            <div class="entry-featured">

                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to: "%s"', '__x__' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php x_featured_image(); ?></a>
                <h3 class="entry-title"><?php the_title(); ?></h3>
            </div>
            <div class="entry-wrap">
                <div>
                    Posted by <a href="#">@username</a> - 6/21/17
                </div>
                <?php if ( is_singular() ) : ?>
                    <?php if ( $disable_page_title != 'on' ) { ?>
                        <header class="entry-header">
                        </header>
                    <?php } ?>
                <?php else : ?>
                    <header class="entry-header">
                        <h2 class="entry-title">
                        </h2>
                    </header>
                <?php endif; ?>
                <?php x_get_view( 'global', '_content' ); ?>
            </div>
        </article>

    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

<?php endif; ?>
