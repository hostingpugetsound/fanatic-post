<?php

// =============================================================================
// VIEWS/GLOBAL/_NAVBAR.PHP
// -----------------------------------------------------------------------------
// Outputs the navbar.
// =============================================================================

$navbar_position = x_get_navbar_positioning();
$logo_nav_layout = x_get_logo_navigation_layout();
$is_one_page_nav = x_is_one_page_navigation();

global $current_user;
//wp_get_current_user();

  $adClient       = "ca-pub-6614460239177654";
  $adSlot         = "9635782928";
  $sidebarAdSlot  = "9635782928";

?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">


  <div class="x-navbar-wrap" >
    <div class="<?php x_navbar_class(); ?> blackbg"  >

      <div class="x-navbar-inner">
        <div class="x-container max width">


			<div id="loginControls">

                        <?php
                        if(is_user_logged_in()):

                        $first_name = get_user_meta($current_user->ID, 'first_name', true);
                        $last_name = get_user_meta($current_user->ID, 'last_name', true);

                        ?>

                        <a class="profilenav_link" href="#" style="margin-right:8px;"><span class="profilenav_dname"><?php echo $current_user->display_name;?></span> <i class="fa fa-user fa-lg"></i></a>
                        <div id="profilenav_con" class="pop-over">

                            <div class="pop-over-header">
                                <span class="pop-over-header-title"><?php echo (!empty($first_name))? $first_name . ' ' . $last_name : $current_user->display_name;?></span>
                                <a href="#" class="pop-over-header-close-btn">x</a>
                            </div>

                            <div class="pop-over-content" style="max-height: 301px;">
                                <div>
                                    <ul class="pop-over-list">
                                        <li><a href="<?php echo get_site_url(); ?>/profile/">My Profile</a></li>
                                        <li><a href="/favorites">My Favorites</a></li>
                                        <li><a href="<?php echo wp_logout_url(apply_filters('the_permalink', get_permalink()));?>">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <script>

                            jQuery(document).ready(function(){
                                jQuery( ".profilenav_link" ).click(function() {
                                    jQuery(".pop-over").toggle("slow");
                                });

                                jQuery( ".pop-over-header-close-btn" ).click(function() {
                                    jQuery(".pop-over").hide("slow");
                                });

                                jQuery(document).click(function(e) {
                                    if (e.target.class != 'pop-over' && !jQuery('.pop-over').find(e.target).length) {
                                        jQuery(".pop-over").hide("slow");
                                    }
                                });
                            });

                        </script>
                        <?php
                        else:
                        ?><a class="profilenav_link" href="<?php echo get_site_url(); ?>/profile/" style="margin-right:8px;"><i class="fa fa-user fa-lg"></i></a><?php
                        endif;?>
                        <a href="<?php get_site_url(); ?>/search/" style="margin-right:8px;"><i class="fa fa-search fa-lg"></i></a>

                        <?php if($current_user->ID && get_option('articleprepayment' . $current_user->ID)): ?>

                        <br />Article Credit (1)

                        <?php endif;?>
		  </div>
            <?php x_get_view( 'global', '_nav', 'primary' ); ?>



  <div style="clear:both;"></div>

        </div>
      </div>
        <div class="x-navbar-inner whitebg">
            <div class="x-container max width">
                <?php x_get_view( 'global', '_nav', 'secondary' ); ?>
            </div>
        </div>
    </div>
  </div>
<?php if (!is_front_page() && !is_page('home') && !is_page('profile')  && !is_page('search') && !is_search() && !is_404()) { ?>
	<div class="adWrapper">
		<div class="adContainer">

			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- TDF Placeholder -->
				<ins class="adsbygoogle"
					style="display:block"
					data-ad-client="<?php echo $adClient; ?>"
					data-ad-slot="<?php echo $adSlot; ?>"
					data-ad-format="auto"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>

		</div>
	</div>
<?php } else if(!is_front_page()) { echo '<div style="display: block; height: 90px;"></div>';} ?>
