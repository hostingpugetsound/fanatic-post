<?php

// =============================================================================
// VIEWS/INTEGRITY/WP-SINGLE.PHP
// -----------------------------------------------------------------------------
// Single post output for Integrity.
// =============================================================================

$fullwidth = get_post_meta( get_the_ID(), '_x_post_layout', true );


  $pageData = array(
  'title' => 'xxx',
  'user_id' => get_current_user_id(),
  'post_id' => get_the_ID()
);


?>


<?php get_header(); ?>

<div class="x-container max width offset">
    
<div class="x-main left" role="main">
<?php

$people_exists = FALSE;

// Find connected pages
$connected = new WP_Query(array(
        'connected_type' => 'articles_to_player',
        'connected_items' => get_queried_object(),
        'nopaging' => true,
        'post_status' => 'publish'
    ));

if ($connected->post_count > 0) {
    $people_exists = TRUE;
} else {
    // Find connected pages
    $connected = new WP_Query(array(
        'connected_type' => 'articles_to_teams',
        'connected_items' => get_queried_object(),
        'nopaging' => true,
    ));
}

function get_sns_url($user_id, $snsname)
{
    $sns_url = false;
    
    $sns_meta_value = get_user_meta($user_id, $snsname, 1);
    
    if($sns_meta_value)
    {
        //check if it is already a url
        if(strpos($sns_meta_value, 'http') || strpos($sns_meta_value, 'wwww.'))
        {
            $sns_url = $sns_meta_value;
        } elseif($snsname == 'facebook')
        {
            $sns_url = "http://facebook.com/" . $sns_meta_value;
        } elseif($snsname == 'twitter')
        {
            $sns_url = "http://twitter.com/" . $sns_meta_value;
        } elseif($snsname == 'google_plus')
        {
            $sns_url = "http://plus.google.com/+" . $sns_meta_value;
        }
    }
    return $sns_url;
    
}

// Display connected pages
if ( $connected->have_posts() ) :
while ( $connected->have_posts() ) : $connected->the_post();
				// Find connected pages
			$articlesConnected = new WP_Query(
				array(
                                        'meta_key' => 'dm_post_popularity_value',
					'connected_type' => ($people_exists)? 'articles_to_player' : 'articles_to_teams',
					'connected_items' => $post,
					'nopaging' => true
				)
			);

		$articlesNum = $articlesConnected->post_count;



?>
      <header class="entry-header">
  		<div style="float:left; max-width:500px;">

  			<span class="teamHeading"><?php the_title(); ?></span>
                        <div><?php the_breadcrumb(get_the_ID());?></div>
  		</div>
  		<div style="clear:both;"></div>

                <?php if($people_exists):?>
                <ul id="navlist" style=" margin-bottom:20px">
                      <li><a href="<?php echo get_site_url(); ?>/player/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a></li>
                      <li><a href="<?php echo get_site_url(); ?>/article/?p=<?php echo $post->post_name; ?>">Articles <span class="circleNum"><?= $articlesNum; ?></span></a></li>
                      <li><a href="<?php echo get_site_url(); ?>/game/?p=<?php echo $post->post_name; ?>">The Beat  <span class="circleNum"><?= count_team_beats(get_the_ID()); ?></span></a></li>
                </ul>
                <?php else:?>
                <ul id="navlist" style=" margin-bottom:20px">
                      <li><a href="<?php echo get_site_url(); ?>/team/<?php echo $post->post_name; ?>">The Arena <?php comments_number( '', '<span class="circleNum">1</span>', '<span class="circleNum">%</span>' ); ?></a></li>
                      <li><a href="<?php echo get_site_url(); ?>/article/?t=<?php echo $post->post_name; ?>">Articles <span class="circleNum"><?= $articlesNum; ?></span></a></li>
                      <li><a href="<?php echo get_site_url(); ?>/game/?t=<?php echo $post->post_name; ?>">The Beat <span class="circleNum"><?= count_team_beats(get_the_ID()); ?></span></a></li>
                </ul>
                <?php endif;?>

      </header>


<?php
$parentTeam = $post->ID;

break;

endwhile; ?>


<?php
// Prevent weirdness
wp_reset_postdata();

endif;
?>

<script>
var pageType = 'article';
var parentTeam = '<?php echo $parentTeam; ?>';

</script>
<?php
if(isset($_GET['preview']) && $_GET['preview'])
{ ?>
    <div class="alert-danger">
        This article is in preview mode and is not published yet. <a href="<?php echo get_permalink().'&publisharticle=1'; ?>">Click here</a> to publish.
    </div>
    <?php
} elseif(isset($_GET['publisharticle']) && $_GET['publisharticle'] && $post->post_status != 'publish')
{
    global $current_user;
    //Remove action, so post does not get duplicated.
    remove_action( 'save_post', 'set_articles_to_teams' );
    
    $prepay = get_option('articleprepayment' . $current_user->ID);
        
    // Publish article
    $my_post = array(
        'ID'           => $post->ID,
        'post_status'   => $prepay? 'publish' : 'draft'
    );

    $updated = wp_update_post( $my_post );

    if($prepay && $updated)
    {
        delete_option('articleprepayment' . $current_user->ID);
        echo '<div class="alert-success">Your article has been published</div>';
    } else
    {
        echo '<div class="alert-danger">Your article could not be published.</div>';
    }
} ?>

      <?php while ( have_posts() ) : the_post(); ?>

      <?php x_get_view( 'integrity', 'content', get_post_format() ); ?>
        
      <?php 
        
        $this_post_type =  get_post_type();
        
        if($this_post_type == 'article'){
         
                $article_user_id = $post->post_author;
                
                $author_data = get_userdata($article_user_id);
         
                $beat_author_default_avatar = '<img width="60" height="60" class="modified avatar avatar-default" src="' . plugins_url(WC_Core::$PLUGIN_DIRECTORY . '/files/img/avatar_default.png') . '" alt=""/>';
                
                $author_data->profilepicture = get_user_meta($author_data->ID, 'profilepicture', 1);
                $author_data->description = get_user_meta($author_data->ID, 'description', 1);
                $author_data->facebook = get_sns_url($author_data->ID, 'facebook');
                $author_data->twitter = get_sns_url($author_data->ID, 'twitter');
                $author_data->google_plus = get_sns_url($author_data->ID, 'google_plus');
                $author_data->website_url = get_user_meta($author_data->ID, 'user_url', 1);
         
      ?>   
         
        <div class="beatwriter-bio-div">
                    <div class="beatwriter-bio-div-info">
  
                        <?php 
                            if ($author_data->profilepicture) {
                                echo get_avatar($author_data->ID, 60, '', '', array('class' => 'avatar avatar-60 photo'));
                            } else {
                                echo $beat_author_default_avatar;
                            }
                        ?>
                        
                        <h4>About <?php echo $author_data->display_name;?></h4>
                        <p class="beatwriter-bio-div-text">FANATICPOST Contributor</p>
                        <p class="beatwriter-bio-div-text"><?php echo $author_data->display_name;?> has written <?php echo number_format_i18n(count_user_posts($author_data->ID,'article'));?> article(s) on this website.</p>
                        <p class="beatwriter-bio-div-meta"><?php echo $author_data->description;?></p>
                        <ul>
                            <li class="first">
                                <a href="<?php echo get_site_url() . '/articles/' . $author_data->user_login;?>">
                                    View all articles by <?php echo $author_data->display_name;?> <span class="meta-nav">→</span>								
                                </a>
                            </li>
                            <li><a title="View <?php echo $author_data->display_name;?>'s profile" href="<?php echo get_site_url() . '/profile/' . $author_data->user_login;?>">Profile</a></li>
                            <?php if($author_data->twitter):?>
                            <li><a rel="external" title="Follow <?php echo $author_data->display_name;?> on Twitter" href="<?php echo $author_data->twitter;?>">Twitter</a></li>
                            <?php endif;?>
                            <?php if($author_data->facebook):?>
                            <li><a rel="external" title="Be <?php echo $author_data->display_name;?>'s friend on Facebook" href="<?php echo $author_data->facebook;?>">Facebook</a></li>                        
                            <?php endif;?>
                            <?php if($author_data->google_plus):?>
                            <li><a title="Add <?php echo $author_data->display_name;?> in your circle" rel="me" href="<?php echo $author_data->google_plus;?>">Google+</a></li>
                            <?php endif;?>
                            <?php if($author_data->website_url):?>
                            <li><a title="Visit <?php echo $author_data->display_name;?>'s website" rel="me" href="<?php echo $author_data->website_url;?>">Website</a></li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
         
    <?php }// end of if article condition ?>

<style>
    .beatwriter-bio-div {background:#F7F7F7; margin:20px 0px 0px 0px; padding:10px 10px 10px 0; border:1px solid #E6E6E6;}
    .beatwriter-bio-div h4 {margin:0 0 4px 90px; padding:0;}
    .beatwriter-bio-div p {margin:0 0 0 81px; padding:0;}
    .beatwriter-bio-div img {background: #FFF; float:left; margin:0 10px 0 10px; padding:3px; border:1px solid #CCC;}
    .beatwriter-bio-div ul {overflow:hidden; margin:0 0 0 81px; padding:0;}
    .beatwriter-bio-div ul li {list-style-type:none; float:left; margin:8px 6px 0 0; padding:0 0 0 6px; line-height:120%; border-left:1px solid #ccc;}
    .beatwriter-bio-div ul li.first {border:none; padding:0;}
</style>

    <?php if ( $fullwidth != 'on' ) : ?>
      <?php get_sidebar(); ?>
    <?php endif; ?>

    

        <?php x_get_view( 'global', '_comments-template' ); ?>
      <?php endwhile; ?>

    </div>
    <?php       
          
        $adClient       = "ca-pub-6614460239177654";
        $adSlot         = "9635782928";
        $sidebarAdSlot  = "9635782928";
    
      ?>
      
    <aside class="x-sidebar right" role="complementary">
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- TDF sidebar -->
      <ins class="adsbygoogle" style="display:inline-block;width:100%;height:280px;" data-ad-client="<?php echo $adClient;?>" data-ad-slot="<?php echo $adSlot;?>"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>


      <?php  x_get_view( x_get_stack(), 'sidebar', 'articles' ); ?>

    </aside>
  </div>
    

<?php get_footer(); ?>
