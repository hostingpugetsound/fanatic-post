<?php

// =============================================================================
// VIEWS/GLOBAL/_AD.PHP
// -----------------------------------------------------------------------------
// Outputs the ads.
// =============================================================================


$adClient      = "ca-pub-6614460239177654";
$adSlot        = "9635782928";
$sidebarAdSlot = "9635782928";

?>


    <section class="x-navbar-wrap ads">
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
    </section>

