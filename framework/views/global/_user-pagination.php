<?php



    $paged = fsu_get( 'paged' );

    $big = 999999999; // need an unlikely integer
    $links = paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => $paged,
        'total' => $total_pages,
        'type'               => 'list',
        'prev_text'          => __('« Prev'),
        'next_text'          => __('Next »'),
        'mid_size'           => 2,
    ) );
    if ($links!=false){
?>
    <div class="mes-pagination">
        <?php echo $links; ?>
    </div>
<?php
    }
?>