<?php if( !is_user_logged_in() ) { ?>
<div id="login">
    <a href="#" class="userpro-close-popup"><?php _e('X','userpro'); ?></a>
    <?php echo do_shortcode( '[userpro template=login]' ); ?>
</div>
<?php } ?>