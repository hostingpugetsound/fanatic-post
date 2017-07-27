<?php

// =============================================================================
// VIEWS/GLOBAL/_NAV-SECONDARY.PHP
// -----------------------------------------------------------------------------
// Outputs the secondary nav.
// =============================================================================

?>



<nav class="desktop-secondary x-nav-wrap" role="navigation">
    <ul class="inline column left">
        <li><a href="<?php echo home_url(); ?>/be-the-beat/">Be The Beat</a></li>
        <li><a href="<?php echo home_url(); ?>/about/">About</a></li>
    </ul>
    <div class="column center">
        <?php x_get_view( 'global', '_brand' ); ?>
    </div>
    <ul class="inline column right">
        <li><a href="<?php echo home_url(); ?>/my-teams/">My Teams</a></li>
        <li>
            <?php
            if ( is_user_logged_in() ):
                $first_name = get_user_meta( $current_user->ID, 'first_name', true );
                $last_name  = get_user_meta( $current_user->ID, 'last_name', true );
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
            <?php else: ?>
                <a class="profilenav_link" href="<?php echo get_site_url(); ?>/profile/" style="margin-right:8px;">Log In</a>
            <?php endif; ?>
        </li>


    </ul>
</nav>
