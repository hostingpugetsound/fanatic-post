<?php


$user_id = fsu_get( 'user_id' );
$user = get_user_by( 'id', $user_id );
$paged = fsu_get( 'paged' );
$page_size = fsu_get( 'page_size' );


$query_args = array(
    'posts_per_page' => $page_size,
    'paged' => $paged,
    'offset' => ($paged - 1) * $page_size,
    'post_type' => 'game_beat',
    'author' => $user_id,
    'post_status' => 'publish'
);

$post_query = new WP_Query($query_args);

if ($post_query->have_posts() ) {
    $base_URL = get_stylesheet_directory_uri();
    ?>
    <div id="mes-articles-wrap">

        <div class="mes-articles">
            <?php while ($post_query->have_posts()) { $post_query->the_post();
                global $post;
                $team_id = get_post_meta($post->ID, 'team-id', true);
                $game_id = get_post_meta($post->ID, 'game-id', true);
                $beat_type = get_post_meta($post->ID, 'beat-type', true);

                $game = get_post( $game_id );
                ?>
                <div class="mes-article">
                    <?php if (!empty($team_id)){ ?>
                    <h3>
                        <a href="<?php echo get_permalink($game->ID); ?>">
                        <?php echo sprintf( '%s <span class=type">%s</span>', get_the_title($team_id), ucfirst($beat_type) ); ?>
                        </a>
                    </h3>
                    <?php } ?>
                    <div class="author">
                        <?php
                        echo sprintf( 'Posted by <a href="%s">@%s</a> - %s',
                            home_url() . '/profile/' . $user->user_login,
                            $user->user_login,
                            date( 'm/d/Y', strtotime($post->post_date))
                        );
                        ?>
                    </div>
                    <div class="mes-article-img">
                        <a href="<?php echo get_permalink($post->ID) . '?ref=' .$team_id; ?>"><?php the_post_thumbnail('medium'); ?></a>
                    </div>
                    <div class="mes-article-content" >
                        <div class="mes-article-title">
                            <?php echo get_the_excerpt(); ?>
                        </div>

                    </div>
                    <div class="userpro-clear"></div>
                </div>

            <?php }
            $total_pages = $post_query->max_num_pages;

            ?>

        </div>
    </div>
<?php } else { // no results ?>
    <div class="mes-nodata">This user has not posted any beats yet.</div>
<?php } ?>


<div class="userpro-clear"></div>


<?php wp_reset_query(); ?>