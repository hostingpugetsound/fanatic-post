<?php

// =============================================================================
// VIEWS/INTEGRITY/WP-COMMENTS.PHP
// -----------------------------------------------------------------------------
// The area of the page that contains both current comments and the comment
// form. The actual display of comments is handled by a callback to
// x_comment().
// =============================================================================

include ABSPATH . "/pp.config.php";

$paypal_status = PAYPAL_STATUS;
$paypal_email = PAYPAL_EMAIL;

$current_url = $_SERVER['REQUEST_URI'];
if($_GET['action_Promote'] =='Promote' &&  $_GET['c'] !=""){

    if($paypal_status == 'Live'){
          $current_paypal_promotion = '<form  action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form_submit_promotion">';
    }
    if($paypal_status=='Test'){
        $current_paypal_promotion = '<form  action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="paypal_form_submit_promotion">';
        }

      echo  $current_paypal_promotion.'<input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="'.$paypal_email.'">
            <input type="hidden" name="item_name" value="Promote Your Artuicl">
            <input type="hidden" name="item_number" value="' . $_GET['c'] . '">
            <input type="hidden" name="amount" value="1">
            <input type="hidden" name="tax" value="">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="address_override" value="1">
            <input type="hidden" name="notify_url" value="' . get_site_url() . '/wp-comments-post.php/?cat=' . $_GET['c'] . '&password=741258&current_url=' . $current_url . '">
            <input type="hidden" name="return" value="' . get_site_url() . '/wp-comments-post.php/?cat=' . $_GET['c'] . '&password=741258&current_url=' . $current_url . '">
            <input type="hidden" name="cancel_return" value="' . get_site_url() . '/wp-comments-post.php/?cat=' . $_GET['c'] . '&current_url=' . $current_url . '">
        </form>'; ?>

        <div style=" background-color: red; font-weight: bold; text-align: center; height: 50px; padding-top: 11px; color: white;" >
                Please wait, We are going for Payment!
            </div>

        <script>
            document.getElementById("paypal_form_submit_promotion").submit();
        </script>


        <?php
     exit();
}

        if ($_SESSION['payment_messae'] == 1) {
            session_unset('payment_messae');
            ?>
            <div style=" background-color: green; font-weight: bold; text-align: center; height: 50px; padding-top: 11px; color: white;" >
                Thanks. You have made payment successfully!
            </div>
        <?php } if ($_SESSION['payment_messae'] == 2) {
            session_unset('payment_messae');
            ?>
            <div style=" background-color: red; font-weight: bold; text-align: center; height: 50px; padding-top: 11px; color: white;" >
                Sorry. You have not made payment successfully!
            </div>
        <?php
        }



/*           SET UP SORT FILTER LINKS       */

/*$serverURI = preg_replace("#&sort=.*#", '', $_SERVER['REQUEST_URI']);
$serverURI = preg_replace("#&filter=.*#", '', $serverURI);
$serverURI = preg_replace("&sort=replies", '', $serverURI);*/

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
} else {
    $filter = 'all';
}

/* if (isset($_GET['sort_replies'])) {
  $sort = $_GET['sort_replies'];
  } */

if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
} else {
    $sort = 'newest';
}

if (strpos($serverURI, '?') == false) { // Determine if a ? already exists in the URL
    $varStart = '?';
} else {
    $varStart = '&';
}
$newestLink = $serverURI . $varStart . "sort=newest&filter=" . $filter;
$newestActive = '';

if ('newest' == $sort) {
    $newestLink = 'javascript:void(0)';
    $newestActive = 'active';
}

$oldestLink = $serverURI . $varStart . "sort=oldest&filter=" . $filter;
$oldestActive = '';
if ('oldest' == $sort) {
    $oldestLink = 'javascript:void(0)';
    $oldestActive = 'active';
}

$repliesLink = $serverURI . $varStart . "sort=replies&filter=" . $filter;
$repliesActive = '';
if ('replies' == $sort) {
    $newestLink = '';
    $oldestLink = '';
    $repliesLink = 'javascript:void(0)';
    $repliesActive = 'active';
}

$allLink = $serverURI . $varStart . "sort=" . $sort . "&filter=all";
if ('all' == $filter) {
    $allLink = 'javascript:void(0)';
}

$fanLink = $serverURI . $varStart . "sort=" . $sort . "&filter=fan";
if ('fan' == $filter) {
    $fanLink = 'javascript:void(0)';
}
$foeLink = $serverURI . $varStart . "sort=" . $sort . "&filter=foe";
if ('foe' == $filter) {
    $foeLink = 'javascript:void(0)';
}

function child_comment_counter($id) {
    global $wpdb;
    $query = "SELECT COUNT(comment_post_id) AS count FROM `wp_comments` WHERE `comment_approved` = 1 AND `comment_parent` = " . $id;
    $children = $wpdb->get_row($query);
    return $children->count;
}

function x_integrity_comment_2($comment, $args, $depth) {


    $GET_promot = get_comment_meta(get_comment_ID(), 'promot', true);
    $GET_promot_date = get_comment_meta(get_comment_ID(), 'promot_date', true);
    if($GET_promot_date !=""){
    $entery_time = $GET_promot_date;
    $firstTime=strtotime($entery_time);
    $lastTime=  strtotime(date('Y-m-d H:i:s'));

   // perform subtraction to get the difference (in seconds) between times
   $timeDiff=$lastTime-$firstTime;
   $hours = abs(floor(($timeDiff)/3600));
        if($hours >= 3){
            update_comment_meta( get_comment_ID(), 'promot_date', '0000-00-00 00:00:00' );
            update_comment_meta( get_comment_ID(), 'promot', '0' );
        }

        if($hours < 3 && $GET_promot ==1 ){  ?>
        <script>
            document.getElementById("promot_id").style.display = "none";
        </script>


       <?php }
    }




    $fan_or_foe = get_comment_meta(get_comment_ID(), 'fan_or_foe', true);
    if ($fan_or_foe == 'fan') {
        $title = "Fan";
    }

    $GLOBALS['comment'] = $comment;

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
        if ($filter == 'all') {
            $showcomment = true;
        } else
        if ($filter == $fan_or_foe) {
            $showcomment = true;
        } else {
            $showcomment = false;
        }
    } else {
        $filter = 'all';
        $showcomment = true;
    }


    if ($showcomment) {



        switch ($comment->comment_type) :
            case 'pingback' :  // 1
            case 'trackback' : // 1
                ?>

                <li <?php comment_class($fan_or_foe); ?> id="comment-<?php comment_ID(); ?>" >
                    <p><?php _e('Pingback:', '__x__'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', '__x__'), '<span class="edit-link">', '</span>'); ?></p>
                    <?php
                    break;
                default : // 2
                    GLOBAL $post;




                    $avatar_variation = ( x_is_product() ) ? ' x-img-thumbnail' : '';
                    ?>

                <li id="li-comment-<?php comment_ID(); ?>" <?php comment_class($fan_or_foe); ?>  >
                    <?php
                    printf('<div class="x-comment-img">%1$s %2$s</div>', '<span class="avatar-wrap cf' . $avatar_variation . '">' . get_avatar($comment, 120) . '</span>', '<span class="bypostauthor">' . __(ucwords($fan_or_foe), '__x__') . '</span>'
                    );
                    ?>
                    <article id="comment-<?php comment_ID(); ?>" class="comment">
                        <header class="x-comment-header">

                            <?php
                            printf('<cite class="x-comment-author">%1$s</cite>', get_comment_author_link()
                            );

                             if ($comment->comment_parent < 1 && $comment->user_id == get_current_user_id() &&  $GET_promot != 1) :  ?>
                            <a class="propmt" id="propmt" href="./?action_Promote=Promote&amp;c=<?php echo comment_ID(); ?>" style="text-align: right; float: right; margin-top: 15px;" ><i class="x-icon-edit"></i> Promote this post</a>
                            <?php endif;

                            printf('<div><a href="%1$s" class="x-comment-time"><time datetime="%2$s">%3$s</time></a></div>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'), sprintf(__('%1$s at %2$s', '__x__'), get_comment_date(), get_comment_time()
                                    )
                            );
                            edit_comment_link(__('<i class="x-icon-edit"></i> Edit', '__x__'));
                            ?>

                <?php if (x_is_product() && get_option('woocommerce_enable_review_rating') == 'yes') : ?>
                                <div class="star-rating-container">
                                    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf(__('Rated %d out of 5', 'woocommerce'), $rating) ?>">
                                        <span style="width:<?php echo ( intval(get_comment_meta($GLOBALS['comment']->comment_ID, 'rating', true)) / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo intval(get_comment_meta($GLOBALS['comment']->comment_ID, 'rating', true)); ?></strong> <?php _e('out of 5', 'woocommerce'); ?></span>
                                    </div>
                                </div>
                        <?php endif; ?>
                        </header>
                        <section class="x-comment-content">
                            <?php comment_text(); ?>
                        </section>
                            <?php if (!$comment->comment_parent) : ?>
                            <div class="x-reply">
                            <?php comment_reply_link(array_merge($args, array('reply_text' => __('<span class="comment-reply-link-after"><i class="x-icon-reply"></i></span> Reply ', '__x__'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        <?php echo "( " . child_comment_counter($comment->comment_ID) . " )"; ?>
                            </div>
                    <?php endif; ?>
                    </article>
                    <?php
                    break;




            endswitch;




        }
    }





//
// 1. If the current post is protected by a password and the visitor has not
//    yet entered the password, we will return early without loading the
//    comments.
//

    if (post_password_required())
        return; // 1
    ?>

    <div id="comments" class="x-comments-area">


        <?php
        $postid = get_the_ID();
        $postType = get_post_type($postid);

        $user_ID = get_current_user_id();

        if ($postType == 'article') {

            $teamConnected = new WP_Query(
                    array(
                'connected_type' => 'articles_to_teams',
                'connected_items' => $post,
                'nopaging' => true
                    )
            );


            $fan_cookie = $user_ID . "_" . $teamConnected->post->ID . "_fan_cookie";
            $title = $teamConnected->post->post_title;
        } else {
            $title = get_the_title();
            $fan_cookie = $user_ID . "_" . $postid . "_fan_cookie";
        }

        if (isset($_COOKIE[$fan_cookie])) {
            if ($_COOKIE[$fan_cookie] == 'fan') {
                $fan_checked = " checked";
            } else if ($_COOKIE[$fan_cookie] == 'foe') {
                $foe_checked = " checked";
            } else {
                echo $_COOKIE[$fan_cookie];
            }
        }
        ?>


        <script>

            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ')
                        c = c.substring(1);
                    if (c.indexOf(name) == 0)
                        return c.substring(name.length, c.length);
                }
                return "";
            }

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + "; path=/; " + expires;
            }

            function checkCookie() {
                var fan_status = getCookie("<?php echo $fan_cookie; ?>");
                console.log(fan_status);
                if (fan_status != "") {
                    //jQuery('#fan_box').hide();
                    if (fan_status == 'fan') {
                        jQuery("#entry-comment-submit").addClass("fan_toggle");
                        jQuery("#entry-comment-submit").val('Post TrashTalk as Fan');
                        //alert("fan");
                    } else if (fan_status == 'foe') {
                        jQuery("#entry-comment-submit").addClass("foe_toggle");
                        jQuery("#entry-comment-submit").val('Post TrashTalk as Foe');
                        //alert("foe");
                    }
                    jQuery('#fan_box').hide();
                    //alert('already a fan');

                } else {
                    jQuery('#fan_box').show();
                    jQuery('#fan_box').removeClass('hidden');
                    //alert ('not yet a fan');
                    jQuery('#comment').hide();
                    jQuery('.form-submit').hide();
                    jQuery('#fan_or_foe').hide();
                }
            }



            jQuery(document).ready(function () {
                checkCookie();


                // set cookie to fan
                jQuery('#fan_toggle').click(function () {
                    setCookie("<?php echo $fan_cookie; ?>", "fan", 365);
                    jQuery('#comment').show();
                    jQuery('.form-submit').show();
                    jQuery('#fan_or_foe').show();
                    jQuery('#fan_box').hide();
                    jQuery("#fan_or_foe_fan").prop('checked', true);
                    jQuery("#entry-comment-submit").addClass("fan_toggle");
                    jQuery("#entry-comment-submit").val('Post TrashTalk as Fan');
                });

                jQuery('#fan_or_foe_fan').click(function () {
                    setCookie("<?php echo $fan_cookie; ?>", "fan", 365);
                    jQuery("#entry-comment-submit").addClass("fan_toggle");
                    jQuery("#entry-comment-submit").val('Post TrashTalk as Fan');

                });


                // set cookie to foe
                jQuery('#foe_toggle').click(function () {
                    setCookie("<?php echo $fan_cookie; ?>", "foe", 365);
                    jQuery('#comment').show();
                    jQuery('.form-submit').show();
                    jQuery('#fan_or_foe').show();
                    jQuery('#fan_box').hide();
                    jQuery("#fan_or_foe_foe").prop('checked', true);

                    jQuery("#entry-comment-submit").removeClass("fan_toggle");
                    jQuery("#entry-comment-submit").val('Post TrashTalk as Foe');
                });

                jQuery('#fan_or_foe_foe').click(function () {
                    setCookie("<?php echo $fan_cookie; ?>", "foe", 365);

                    jQuery("#entry-comment-submit").removeClass("fan_toggle");
                    jQuery("#entry-comment-submit").val('Post TrashTalk as Foe');
                });


                jQuery('.children').hide();

                // Toggle all
                jQuery('#toggle-all').click(function () {
                    var $subcomments = jQuery('.x-comments-list').find('> li > ol');
                    if ($subcomments.filter(':hidden').length) {
                        $subcomments.slideDown();
                    } else {
                        $subcomments.slideUp();
                    }
                });

                // Add buttons to threaded comments
                jQuery('.x-comments-list').find('> li > ol').prev().find('.x-reply')
                        .append('<a class="toggle">Open Replies</a>');

                // Toggle one section
                jQuery('.x-comments-list').find('a.toggle').click(function () {
                    var coder_subcomments = jQuery(this).parent().parent().next('ol');
                    if (coder_subcomments.filter(':hidden').length) {
                        coder_subcomments.slideDown();
                        jQuery(this).html('Close Replies');
                    } else {
                        coder_subcomments.slideUp();
                        jQuery(this).html('Open Replies');
                    }
                });





            });



        </script>

        <?php
//
        $siteurl = get_site_url();

        comment_form(array(
            'comment_notes_after' => '',
            'id_submit' => 'entry-comment-submit',
            'class_submit' => 'foe_toggle submit',
            'label_submit' => __('Post TrashTalk', '__x__'),
            'comment_field' => '
	  <div style="padding:20px; display:block; text-align:center; margin: 0 0 40px 0; border: 1px solid #AAA; border-radius:5px; background:#FFF;" class="one-edge-shadow hidden" id="fan_box">
		<h3 style="margin:0 0 20px 0; padding:0px; ">Are you a fan or a foe of ' . $title . '?</h3>
		<a class="x-btn  x-btn-round x-btn-x-large fan-btn" style="margin-right:20px;" id="fan_toggle">I\'m a Fan!</a>
		<a class="x-btn x-btn-round x-btn-x-large" id="foe_toggle">I\'m a Foe!</a>
	  </div>

	  <p class="comment-form-comment"><label for="comment">' . _x('', 'noun') .
            '</label><textarea id="comment" name="comment" cols="45" rows="4" aria-required="true" placeholder="Talk Some Trash!">' .
            '</textarea></p>

<div style="float:right;" id="fan_or_foe">
    <input type="radio" id="fan_or_foe_fan" name="fan_or_foe" value="fan" aria-required="true" ' . $fan_checked . '>
       <label for="fan_or_foe_fan">Post as a Fan</label>

    <input type="radio" id="fan_or_foe_foe" name="fan_or_foe" aria-required="true" value="foe" ' . $foe_checked . '>
       <label for="fan_or_foe_foe">Post as a Foe</label>

  </div>
  <div style="clear:both;">&nbsp;</div>


	',
            'must_log_in' => '<div class="joinContainer"><span style="display:block; text-align:center;"><a class="x-btn x-btn-red x-btn-real x-btn-square x-btn-x-large joinBtn"  href="' . $siteurl . '/register" title="Join Now">Join Now &amp; Talk Some Trash!</a></span></div>',
            'logged_in_as' => '<p class="logged-in-as">' .
            sprintf(
                    __(''), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))
            ) . '</p>',
            'title_reply' => __(''),
        ));
        ?>

        <?php if (have_comments()) : ?>
            <?php
            $allActive = 'active';
            $fanActive = $foeActive = '';
            if (isset($_GET['filter'])) {
                if ($_GET['filter'] == 'fan') {
                    $fanActive = 'active';
                    $allActive = '';
                } else if ($_GET['filter'] == 'foe') {
                    $foeActive = 'active';
                    $allActive = '';
                }
            }
            ?>
            <div id="sortBox">
                <div style="float:left;">
                    <a href="<?php echo $allLink; ?>" class="<?php echo $allActive; ?>">All</a> / <a href="<?php echo $fanLink; ?>" class="<?php echo $fanActive; ?>" >Fan Only</a> / <a href="<?php echo $foeLink; ?>" class="<?php echo $foeActive; ?>" >Foe Only</a>
                </div>
                <?php
                $oldestActive = $repliesActive = '';
                $newestActive = 'active';
                if (isset($_GET['sort'])) {
                    if ($_GET['sort'] == 'newest') {
                        $newestActive = 'active';
                    } else if ($_GET['sort'] == 'oldest') {
                        $newestActive = '';
                        $oldestActive = 'active';
                    }
                }
                if (isset($_GET['sort']) && $_GET['sort'] == 'replies') {
                    $newestLink = $serverURI . $varStart . "sort=newest&filter=" . $filter;
                    $oldestLink = $serverURI . $varStart . "sort=oldest&filter=" . $filter;
                    $repliesActive = 'active';
                    $repliesLink = 'javascript:void(0)';
                    $oldestActive = '';
                    $newestActive = '';
                }
                ?>
                <div style="float:right;">
                    Sort By: <a href="<?php echo $newestLink; ?>" class="<?php echo $newestActive ?>">Newest</a> / <a href="<?php echo $oldestLink; ?>" class="<?php echo $oldestActive ?>">Oldest</a> / <a href="<?php echo $repliesLink; ?>" class="<?php echo $repliesActive; ?>">Most Replied</a>
                </div>
                <div style="clear:both;">&nbsp;</div>
            </div>

        <a id="promot_id" href="#propmt" >
        <div class="propmt_c" style="text-align: center; width: 150px; margin: 0 auto; border: 1px solid; font-weight: bold; color: red">
            Control the Conversation</div>
        </a>

            <?php
            unset($oldestActive);
            unset($repliesActive);
            unset($newestActive);
            ?>

                <?php /* <h2 class="h-comments-title"><span><?php _e( 'Comments' , '__x__' ); ?> <small><?php echo number_format_i18n( get_comments_number() ); ?></small></span></h2> */ ?>
            <ol class="x-comments-list">
                <?php
                if ($sort == 'newest') {
                    $reverse_top_level = true;
                } else {
                    $reverse_top_level = false;
                }

                wp_list_comments(array(
                    'callback' => 'x_integrity_comment_2',
                    'style' => 'ol',
                    'reverse_top_level' => $reverse_top_level
                ));
                ?>
            </ol>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <nav id="comment-nav-below" class="navigation" role="navigation">
                    <h1 class="visually-hidden"><?php _e('Comment navigation', '__x__'); ?></h1>
                    <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', '__x__')); ?></div>
                    <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', '__x__')); ?></div>
                </nav>
            <?php endif; ?>

    <?php if (!comments_open() && get_comments_number()) : ?>
                <p class="nocomments"><?php _e('Comments are closed.', '__x__'); ?></p>
    <?php endif; ?>

<?php endif;
?>

    </div>
