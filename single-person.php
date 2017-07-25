<?php

// =============================================================================
// single-person.PHP
// -----------------------------------------------------------------------------
// Handles output of teams landing pages
// =============================================================================

?>

<?php get_header(); ?>
<script>
var pageType = 'person';
</script>
  <div class="x-container max width offset">
    <div class="x-main left" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
<?php  x_get_view( x_get_stack(), 'template', 'person' ); ?>
<?php  x_get_view( 'global', '_comments-template' ); ?>



      <?php endwhile; ?>

    </div>

      <?php
        $author_id = get_the_author_meta('ID');
        $adClient = get_user_meta($author_id, 'adClient', true);
        $adSlot = get_user_meta($author_id, 'adSlot', true);
        $sidebarAdSlot = get_user_meta($author_id, 'sidebarAdSlot', true);


        if (!$adClient || !$adSlot || !$sidebarAdSlot) {
          $adClient       = "ca-pub-6614460239177654";
          $adSlot         = "9635782928";
          $sidebarAdSlot  = "9635782928";
        }
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


  </div></div>

  <style>

    @media (max-width: 522px) {
      .x-container .x-main {
        min-height: 600px;
      }
    }

  </style>
<?php get_footer(); ?>
