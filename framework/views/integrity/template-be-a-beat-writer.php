<?php
// =============================================================================
// =============================================================================

$monthfilter = (isset($_GET['month']) && !empty($_GET['month']))? $_GET['month'] : '';
$game_id = (isset($_GET['game_id']) && !empty($_GET['game_id']))? $_GET['game_id'] : 0;
$season = (isset($_GET['season']) && !empty($_GET['season']))? $_GET['season'] : '';

?>

<?php get_header(); ?>

<header>

    <div class="x-container max width ">
        <div class="x-main full" role="main">

            <?php if (isset($_SESSION['pp_success_message'])): ?>
                <div class="alert-success">
                <?php echo $_SESSION['pp_success_message'];
                unset($_SESSION['pp_success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['pp_failure_message'])): ?>
                <div class="alert-danger">
                    <?php echo $_SESSION['pp_failure_message'];
                    unset($_SESSION['pp_failure_message']); ?>
                </div>
            <?php endif; ?>

            <article id="post-22" class="post-22 page type-page status-publish hentry no-post-thumbnail">
                <div class="entry-featured">
                </div>
                <header class="entry-header">
                    <h2 style="display:block; text-align:center; padding:20px;"><?php the_title(); ?></h2>
                </header>

                <div class="entry-wrap" style="">

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>

                        <?php endwhile;
                    endif; ?>
                    
                    <?php
                    global $current_user;
                    
//                    if (!empty($current_user->ID) && isset($_POST['amount'])):
                    if (!empty($current_user->ID) ):

                        include_once ABSPATH . 'pp_config.php';
                        ?>
                    <div class="agree_beat_button">
                        <a class="x-btn  x-btn-round x-btn-x-large agree_button" href="<?php echo get_site_url(); ?>/beat-checkout/?t=<?php echo  $_GET['t'];?>&tid=<?php echo  $_GET['tid'];?>&season=<?php echo $season;?>&month=<?php echo $monthfilter;?>&game_id=<?php echo $game_id?>">Agree &amp; Return to Beat Checkout</a>
                    </div>
                    
                    <?php /*?>
                        <form name="agree_form" action="<?php echo PAYPAL_URL; ?>" method="post">
                            <input type="hidden" name="cmd" value="<?php echo $_POST['cmd'];?>">
                            <input type="hidden" name="business" value="<?php echo PAYPAL_EMAIL; ?>">
                            <input type="hidden" name="item_name" value="<?php echo $_POST['item_name'];?>">
                            <input type="hidden" name="item_number" value="<?php echo $_POST['item_number'];?>">
                            <input type="hidden" name="custom" class="pp_custom_data" value='<?php echo stripslashes_deep($_POST['custom']);?>'>
                            <input type="hidden" name="amount" value="<?php echo $_POST['amount'];?>">
                            <input type="hidden" name="tax" value="">
                            <input type="hidden" name="quantity" value="<?php echo $_POST['quantity'];?>">
                            <input type="hidden" name="no_note" value="<?php echo $_POST['no_note'];?>">
                            <input type="hidden" name="currency_code" value="USD">
                            <input type="hidden" name="address_override" value="<?php echo $_POST['address_override'];?>">
                            <input type="hidden" name="notify_url" value="<?php echo $_POST['notify_url'];?>">
                            <input type="hidden" name="return" value="<?php echo $_POST['return'];?>">
                            <input type="hidden" name="cancel_return" value="<?php echo $_POST['cancel_return'];?>">
                            <a class="x-btn  x-btn-round x-btn-x-large agree_button" style="margin-top:20px;" href="javascript:void(0)">Agree &amp; Pay $<?php echo $_POST['amount'];?> to Publish your Beats</a>
                        </form>
                    <?php */?>
                        <script>

                            jQuery(document).ready(function () {

//                                jQuery('.agree_button').click(function (event) {
//                                    jQuery.ajax({
//                                        method: "POST",
//                                        url: "<?php // echo admin_url('admin-ajax.php'); ?>",
//                                        data: {action: "user_beats_agreement_acceptance"}
//                                    })
//                                    .done(function (response) {
//                                        document.agree_form.submit();
//                                    });
//
//                                    return false;
//                                });
                            });

                    </script>
                    <?php endif; ?>
                </div>
            </article>

        </div>
    </div>

<?php get_footer(); ?>
