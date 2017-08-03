<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-1.PHP (Container | Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );
  
?>  

	<?php get_header(); ?>

    <div class="<?php x_main_content_class(); ?>" role="main">
        <div class="x-container max width">


            <?php x_get_view( 'global', '_sidebar-news' ); ?>

            <div class="x-column x-sm x-2-4 content">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php x_get_view( 'integrity', 'content', 'page' ); ?>
                    <?php x_get_view( 'global', '_comments-template' ); ?>
                <?php endwhile; ?>
            </div>

            <?php x_get_view( 'global', '_sidebar-be-the-beat' ); ?>

        </div>

    </div>


		
		<div class="container" style="margin-top:20px;">
			<div class='all-posts-sidebar'>
				<ul>
					<li class="active">All Posts</li>
					<li>All Favorites</li>
					<li>Seattle Seahawks</li>
					<li>Seattle Mariners</li>
				</ul>
			</div>
			<div class='all-posts-content'>
				<div class="row post arena-post">
					<span class="headshot"><a href=""><img src="http://placehold.it/66x66"></a></span>
					<div class="post-text">
						<span class="post-title"><a href="#postedPage" >"Seahawks should be looking at drafting Boise State Bronco running back, Jeremy McNichols. This guy is a stud!"
</a></span>
						<span class="username">Arena Post by <a href="#user">Username</a> on <a href="">Seattle Seahawks</a> Yesterday</span>

					</div>

				</div>
				<div class="row post article-post">
					<span class="headshot"><a href=""><img src="http://placehold.it/66x66"></a></span>
					<div class="post-text">
						<span class="post-title"><a href="#postedPage" >Seattle’s “NO NAME” receivers
</a></span>
						<span class="username">Article by <a href="#user">DJHaze</a> on <a href="">Seattle Seahawks</a> on 11/11/16</span>

					</div>

				</div>
				<div class="row post beat-post">

					<div class="post-text">
						<span class="post-title"><a href="#postedPage" >Seattle Seahawks vs. Miami Dolphins Preview Beat Article
</a></span>
						<span class="username">Beat by <a href="#user">Username</a> on <a href=""></a> Yesterday</span>

					</div>

				</div>

				<div class="row post arena-post">
					<span class="headshot"><a href=""><img src="http://placehold.it/66x66"></a></span>
					<div class="post-text">
						<span class="post-title"><a href="#postedPage" >"Seahawks should be looking at drafting Boise State Bronco running back, Jeremy McNichols. This guy is a stud!"
</a></span>
						<span class="username">Arena Post by <a href="#user">Username</a> on <a href="">Seattle Seahawks</a> Yesterday</span>

					</div>

				</div>
				<div class="row post article-post">
					<span class="headshot"><a href=""><img src="http://placehold.it/66x66"></a></span>
					<div class="post-text">
						<span class="post-title"><a href="#postedPage" >Seattle’s “NO NAME” receivers
</a></span>
						<span class="username">Article by <a href="#user">DJHaze</a> on <a href="">Seattle Seahawks</a> on 11/11/16</span>

					</div>

				</div>
				<div class="row post beat-post">

					<div class="post-text">
						<span class="post-title"><a href="#postedPage" >Seattle Seahawks vs. Miami Dolphins Preview Beat Article
</a></span>
						<span class="username">Beat by <a href="#user">Username</a> on <a href=""></a> Yesterday</span>

					</div>

				</div>

			</div>

		</div>
		<div style="clear:both;">&nbsp;</div>
		</div>



		<?php get_footer(); ?>