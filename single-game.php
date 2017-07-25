<?php

// =============================================================================
// sing-team.PHP
// -----------------------------------------------------------------------------
// Handles output of teams landing pages
// =============================================================================

?>

<?php get_header(); ?>
<script>
var pageType = 'team';
</script>
  <div class="x-container max width offset">
    <div class="x-main left" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
<?php  x_get_view( x_get_stack(), 'template', 'game' ); ?>



      <?php endwhile; ?>

    </div>

    <aside class="x-sidebar right" role="complementary">
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- TDF sidebar -->
      <ins class="adsbygoogle" style="display:inline-block;width:336px;height:280px" data-ad-client="ca-pub-6614460239177654" data-ad-slot="9635782928"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>


      <?php  x_get_view( x_get_stack(), 'sidebar', 'articles' ); ?>

    </aside>


  </div>

<?php get_footer(); ?>
