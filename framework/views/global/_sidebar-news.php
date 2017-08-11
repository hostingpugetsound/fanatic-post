<?php
$default_rss = 'https://sports.yahoo.com/top/rss.xml';
if( is_singular( 'league' ) )
    $rss_url = get_post_meta( $post->ID, 'wpcf-league-rss-feed', true );
elseif( is_singular( 'team' ) )
    $rss_url = get_post_meta( $post->ID, 'wpcf-team-rss-feed', true );
else
    $rss_url = $default_rss;

if( empty($rss_url) )
    $rss_url = $default_rss;


include_once( ABSPATH . WPINC . '/feed.php' );

// Get a SimplePie feed object from the specified feed source.
$rss = fetch_feed( $rss_url );

$maxitems = 0;

if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

    // Figure out how many total items there are, but limit it to 5. 
    $maxitems = $rss->get_item_quantity( 2 );

    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items( 0, $maxitems );

endif;
?>



<div class="x-column x-sm x-1-4">

    <div class="news-sidebar">
        <h2 class="red-header">News</h2>
        <ul class="x-nav news-widget">

        <?php if( $rss_items) : foreach ( $rss_items as $item ) : #vard($item); ?>
            <li class="news-single">
                <a href="<?php echo esc_url( $item->get_permalink() ); ?>">
                    <!--<img src="//lorempixel.com/293/293" />-->
                    <!--<img src="<?php #echo esc_attr( $item->get_image_url( )); ?>" />-->
                </a>
                <h3><?php echo sprintf( '<a href="%s">%s</a>', esc_url( $item->get_permalink() ), esc_html( $item->get_title() ) ); ?></h3>
                <div class="author">
                    From <span class="source">Yahoo! News</span> - <time><?php echo $item->get_date('n/j/Y'); ?></time>
                </div>
                <div class="description"><?php echo $item->get_description(); ?></div>
            </li>
        <?php endforeach; endif; ?>
        </ul>
    </div>



    <?php if( !is_front_page() ) : ?>
    <div class="standings-sidebar">
        <h2 class="red-header">Standings</h2>
        <ul class="x-nav standings-nav">
            <li><a href="#">American</a></li>
            <li><a href="#">National</a></li>
            <li><a href="#">East</a></li>
            <li><a href="#">Central</a></li>
            <li><a href="#">West</a></li>
        </ul>

        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>WI</th>
                    <th>LO</th>
                    <th>%</th>
                    <th>G</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Team</td>
                    <td>00</td>
                    <td>00</td>
                    <td>00</td>
                    <td>00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php x_get_view( 'global', '_ad' ); ?>

    <?php endif; ?>




</div>