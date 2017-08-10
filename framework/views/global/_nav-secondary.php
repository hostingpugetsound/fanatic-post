<?php

// =============================================================================
// VIEWS/GLOBAL/_NAV-SECONDARY.PHP
// -----------------------------------------------------------------------------
// Outputs the secondary nav.
// =============================================================================

?>



<nav class="desktop-secondary x-nav-wrap" role="navigation">
    <div class="column left">
        <ul class="inline hidden-sm-down">
            <li><a href="<?php echo home_url(); ?>/be-the-beat/">Be The Beat</a></li>
            <li><a href="<?php echo home_url(); ?>/about-us/">About</a></li>
        </ul>

        <div class="burger hidden">
            <span class="slice"></span>
            <span class="slice"></span>
            <span class="slice"></span>
        </div>

    </div>
    <div class="column center">
        <?php x_get_view( 'global', '_brand' ); ?>
    </div>
    <div class="column right">
        <ul class="inline hidden-sm-down">
        <li><a href="<?php echo home_url(); ?>/my-teams/">My Teams</a></li>
        <li>
            <?php
            if ( is_user_logged_in() ):
                #$first_name = get_user_meta( $current_user->ID, 'first_name', true );
                #$last_name  = get_user_meta( $current_user->ID, 'last_name', true );
                ?>
                <a class="profilenav_link" href="#">
                    <span class="profilenav_dname">My Account</span>
                </a>
                <script>
                    jQuery(document).ready(function () {
                        jQuery(".profilenav_link").click(function () {
                            jQuery(".pop-over").toggle("slow");
                        });
                        jQuery(".pop-over-header-close-btn").click(function () {
                            jQuery(".pop-over").hide("slow");
                        });
                        jQuery(document).click(function (e) {
                            if (e.target.class != 'pop-over' && !jQuery('.pop-over').find(e.target).length) {
                                jQuery(".pop-over").hide("slow");
                            }
                        });
                    });
                </script>
                <div id="profilenav_con" class="pop-over">

                    <div class="pop-over-header">
                        <span class="pop-over-header-title"><?php echo ( ! empty( $first_name ) ) ? $first_name . ' ' . $last_name : $current_user->display_name; ?></span>
                        <a href="#" class="pop-over-header-close-btn">x</a>
                    </div>

                    <div class="pop-over-content" style="max-height: 301px;">
                        <div>
                            <ul class="pop-over-list">
                                <li><a href="<?php echo get_site_url(); ?>/profile/">My Profile</a></li>
                                <li>
                                    <a href="<?php echo wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ); ?>">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a class="profilenav_link" href="<?php echo get_site_url(); ?>/#login">Log In</a>
            <?php endif; ?>
        </li>
        </ul>


    </div>
</nav>
