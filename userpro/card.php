<div class="userpro userpro-nostyle userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>

	<a href="#" class="userpro-close-popup"><?php _e('Close','userpro'); ?></a>
	
	<div class="userpro-card">
		
		<div class="userpro-card-img">
			<a href="<?php echo $userpro->permalink( $user_id ); ?>"><?php echo get_avatar( $user_id, $card_img_width ); ?></a>
		</div>
		
		<div class="userpro-card-info">
			<div class="userpro-card-left">
                <a href="<?php echo $userpro->permalink( $user_id ); ?>" title="<?php _e('View Full Profile','userpro'); ?>"><?php echo userpro_profile_data('display_name', $user_id); ?></a><br />
                <!-- @todo: Premium Member status -->
                <a href="<?php echo $userpro->permalink( $user_id ); ?>" title="<?php _e('View Full Profile','userpro'); ?>">@<?php echo userpro_profile_data('user_login', $user_id); ?></a><br />
            </div>
			<div class="userpro-card-right"><?php echo userpro_show_badges( $user_id, true ); ?></div>
			<div class="userpro-clear"></div>
		</div>
		
		<?php if ($card_showbio && $userpro->shortbio($user_id)) : ?><div class="userpro-card-bio"><?php echo $userpro->shortbio( $user_id ); ?></div><?php endif; ?>
		
		<?php if (!isset($disable_name_hooks)){ /* display hooks after user name and badge */
		do_action('userpro_after_name_user_list', $user_id);
		} ?>
		
		<?php if ($card_showsocial) { ?>
		<div class="userpro-card-icons"><?php echo $userpro->show_social_bar( $args, $user_id ); ?></div>
		<?php } ?>
		
		<div class="userpro-clear"></div>
		
	</div>

    <hr />

    <div id="mes-comments-wrap">
    <?php

    $page_size = 20;
    $mes = new MesUserProExtraTabs();
    $user_comments = $mes->count_user_comments($user_id);
    $total_pages = ceil($user_comments / $page_size);
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

    $args = array(
        'status' => 'approve',
        'user_id' => $user_id,
        'offset' => ($paged - 1) * $page_size,
        'number' => $page_size,
    );

    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query($args);


    $base_URL = get_stylesheet_directory_uri();


    if($comments) {
    global $comment;
    $g_comment = $comment;

    foreach( $comments as $comment_data ) {
        $fanStatus = get_comment_meta( $comment_data->comment_ID, 'fan_or_foe', true );
        ?>
        <div class="mes-comment">
            <div class="mes-comment-content">
                <p>
                    <?php echo '<img src="' . $base_URL . '/framework/img/global/' . $fanStatus . '_flag.png">'; ?>
                    <?php echo get_comment_excerpt( $comment_data->comment_ID );//echo $comment->comment_content;?>
                </p>
            </div>
            <div class="mes-comment-meta">
                <?php
                $comment = $comment_data;
                printf( 'Posted on <a href="%1$s" class="x-comment-time">' . get_the_title( $comment->comment_post_ID ) . '</a> on <time datetime="%2$s">%3$s</time>',
                    esc_url( get_comment_link( $comment->comment_ID ) ),
                    get_comment_time( 'c' ),
                    sprintf( __( '%1$s at %2$s', '__x__' ),
                        get_comment_date(),
                        get_comment_time()
                    )
                );
                ?>
            </div>
        </div>
        <?php
    }

    $comment = $g_comment;

    } else {
    ?>
        <div class="mes-nodata">This user has not posted any comments yet.</div>
    <?php } ?>
    </div>


</div>
