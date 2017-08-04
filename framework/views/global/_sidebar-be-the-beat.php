<?php
$date_format = 'Y-m-d';
$game_date_key = 'wpcf-game-date';
$game_time_key = 'wpcf-game-time';

$args = array(
    'post_type' => 'game',
    'posts_per_page' => 20,
    'order'      => 'ASC',
    'orderby' => 'meta_value_num',
    'meta_key' => $game_date_key,
    'meta_type' => 'DATE',
    'meta_query' => array(
        array(
            'key'     => $game_date_key,
            'value'   => strtotime( date($date_format) ),
            'compare' => '>=',
        ),
    ),
);

$query = new WP_Query( $args );

?>


<div class="x-column x-sm x-1-4 beats-sidebar">

    <h2>Be The Beat</h2>

    <section class="x-nav beat-widget">
        <?php
        $current_date = 0;
        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            $i = 0;
            $game_date = get_post_meta( get_the_ID(), $game_date_key, true );
            $game_time = get_post_meta( get_the_ID(), $game_time_key, true );

            if( $current_date != $game_date ) {
                $current_date = $game_date;
                echo sprintf( '<h3>%s</h3>', date('M j Y', $game_date) );
            }

            echo sprintf( '<div class="game">%s <time>%s</time></div>', get_the_title(), $game_time );
            ?>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </section>

    <p><a href="<?php echo home_url(); ?>/be-the-beat">See all games</a></p>


</div>