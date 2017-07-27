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
        <li><a href="<?php echo home_url(); ?>/my-account/">My Account</a></li>
    </ul>
</nav>
