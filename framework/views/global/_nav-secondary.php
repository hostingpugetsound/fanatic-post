<?php

// =============================================================================
// VIEWS/GLOBAL/_NAV-SECONDARY.PHP
// -----------------------------------------------------------------------------
// Outputs the secondary nav.
// =============================================================================

?>



<nav class="x-navbar-fixed-left" role="navigation">
    <a href="<?php echo home_url(); ?>/be-the-beat/">Be The Beat</a>
    <a href="<?php echo home_url(); ?>/about/">About</a>
    <?php x_get_view( 'global', '_brand' ); ?>
    <a href="<?php echo home_url(); ?>/my-teams/">My Teams</a>
    <a href="<?php echo home_url(); ?>/my-account/">My Account</a>
</nav>