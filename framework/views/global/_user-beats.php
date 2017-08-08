<?php

$page_size = 20;
$user_id = userpro_get_view_user(get_query_var('up_username'));
$user_id = 32; #test user

$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;


$query_args = array(
    'posts_per_page' => $page_size,
    'paged' => $paged,
    'offset' => ($paged - 1) * $page_size,
    'post_type' => 'game_beat',
    'author' => $user_id,
    'post_status' => 'publish'
);

$post_query = new WP_Query($query_args);
#vard($post_query);

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
                ?>
                <div class="mes-article">

                    <div class="mes-article-img">
                        <a href="<?php echo get_permalink($post->ID) . '?ref=' .$team_id; ?>"><?php the_post_thumbnail('medium'); ?></a>
                    </div>
                    <div class="mes-article-content" >
                        <div class="mes-article-title">
                            <?php echo get_the_excerpt(); ?>
                        </div>

                        <div class="mes-team-name">
                            <?php
                            if (!empty($team_id)){
                                ?><a href="<?php echo get_permalink($post->ID) . '?ref=' .$team_id; ?>"><?php echo ucfirst($beat_type) . ' ' . get_the_title($team_id); ?></a><?php
                            }
                            ?>
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