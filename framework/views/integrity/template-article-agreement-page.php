<?php

// =============================================================================

// =============================================================================

$t = $tid = FALSE;

$p = $pid = FALSE;

if (isset($_GET['t'])) {
	$t = $_GET['t'];
}

if (isset($_GET['tid'])) {
	$tid = $_GET['tid'];
}

if (isset($_GET['p'])) {
	$p = $_GET['p'];
}

if (isset($_GET['pid'])) {
	$pid = $_GET['pid'];
}

if (isset($_GET['fan_status'])) {
	$fan_status = $_GET['fan_status'];
}

?>

<?php get_header(); ?>

<header>



  <div class="x-container max width ">
    <div class="x-main full" role="main">

<?php if(isset($_SESSION['pp_success_message'])):?>
    <div class="alert-success">
        <?php echo $_SESSION['pp_success_message']; unset($_SESSION['pp_success_message']); ?>
    </div>
<?php endif;?>

<?php if(isset($_SESSION['pp_failure_message'])):?>
    <div class="alert-danger">
        <?php echo $_SESSION['pp_failure_message']; unset($_SESSION['pp_failure_message']); ?>
    </div>
<?php endif;?>

<article id="post-22" class="post-22 page type-page status-publish hentry no-post-thumbnail">
  <div class="entry-featured">
      </div>
              <header class="entry-header">
	  <h2 style="display:block; text-align:center; padding:20px;">Post an Article</h2>
          </header>

 <div class="entry-wrap" style="">

	 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>

						 <?php endwhile; endif; ?>
        <?php
        global $current_user;
    
        $articleprepayment = get_option('articleprepayment' . $current_user->ID);
        
        if(!$articleprepayment):
        
        include_once ABSPATH . 'pp_config.php';
        
        $pp_app_base_url = get_site_url();

        
        if($pid)
        {
            $back_url = urlencode($pp_app_base_url . '/article/?p='.$p.'&pid='.$pid);
        } else
        {
            $back_url = urlencode($pp_app_base_url . '/article/?t='.$t.'&tid='.$tid);
        }
        
        $notify_url = $pp_app_base_url . '/paypal.php?action=articleprepaymentnotify&password=7412581&current_url=' . $back_url;
        $return_url =  $pp_app_base_url . '/paypal.php?action=articleprepaymentsuccess&password=741258&current_url=' . $back_url;
        $cancel_url =  $pp_app_base_url . '/paypal.php?action=articleprepaymentcancel&current_url=' . $back_url;
        
        ?>
        
        <form name="agree_form" action="<?php echo PAYPAL_URL;?>" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?php echo PAYPAL_EMAIL;?>">
        <input type="hidden" name="item_name" value="Post Article">
        <input type="hidden" name="item_number" value="<?php echo $current_user->ID;?>">
        <input type="hidden" name="amount" value="5">
        <input type="hidden" name="tax" value="">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="address_override" value="1">
        <input type="hidden" name="notify_url" value="<?php echo $notify_url;?>">
        <input type="hidden" name="return" value="<?php echo $return_url;?>">
        <input type="hidden" name="cancel_return" value="<?php echo $cancel_url;?>">
        <div class="post_article_button">
            <a class="x-btn  x-btn-round x-btn-x-large agree_button" style="margin-top:20px;" href="javascript:void(0)">Agree &amp; Pay $5 to Publish your Article</a>
        </div>
        </form>
        
        <?php else:?>
            <?php if($pid):?>
                <form name="agree_form" action="<?php echo get_site_url();?>/create-a-post-2/?p=<?php echo $p; ?>&pid=<?php echo $pid; ?>" method="post">
                </form>
                <div class="post_article_button">
                    <a class="x-btn  x-btn-round x-btn-x-large agree_button" style="margin-top:20px;" href="javascript:void(0)">Agree &amp; Continue</a>
                </div>
            <?php else:?>
                <form name="agree_form" action="<?php echo get_site_url();?>/create-a-post/?t=<?php echo $t; ?>&tid=<?php echo $tid; ?>" method="post">
                </form>
                <div class="post_article_button">
                    <a class="x-btn  x-btn-round x-btn-x-large agree_button" style="margin-top:20px;" href="javascript:void(0)">Agree &amp; Continue</a>
                </div>
            <?php endif;?>
        
        <?php endif;?>
  </div>
    <script>
    
    jQuery(document).ready(function(){
        
        jQuery('.agree_button').click(function(event){
            jQuery.ajax({
                method: "POST",
                url: "<?php echo admin_url( 'admin-ajax.php' );?>",
                data: { action: "user_article_agreement_acceptance"}
            })
            .done(function( response ) {
                document.agree_form.submit();
            });
            
            return false;
        });
    });
    
    </script>
</article>

    </div>
  </div>

<?php get_footer(); ?>
