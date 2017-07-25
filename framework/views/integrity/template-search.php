<?php

// =============================================================================
// VIEWS/INTEGRITY/TEMPLATE-BLANK-1.PHP (Container | Header, Footer)
// -----------------------------------------------------------------------------
// A blank page for creating unique layouts.
// =============================================================================

$disable_page_title = get_post_meta( get_the_ID(), '_x_entry_disable_page_title', true );

?>

<?php get_header(); ?>

<header>

 

  <div class="x-container max width " style="margin-top:20px;">
    <div class="x-main full" role="main"  style="padding:40px;">
		<h3 style="display:block; text-align:center; padding:20px; margin-bottom:20px; display:block; ">Find out what people are saying</h3>
			<div style="width:100%; height:40px;">&nbsp;</div>
				<div class="searchFormContainer">
				<?php get_search_form( TRUE ); ?>

				</div>
	</div>
</div>

<?php get_footer(); ?>