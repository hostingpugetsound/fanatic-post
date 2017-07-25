<?php

// =============================================================================
// VIEWS/GLOBAL/_teamHeader.PHP
// -----------------------------------------------------------------------------
// Outputs the team Header
// =============================================================================

/*

?>

<?php
$my_query = new WP_Query( 'post_type=team&pagename='.$_GET['t'] );
if ( $my_query->have_posts() ) { 
while ($my_query->have_posts()) {
	$my_query->the_post();

	
	echo '

             <header class="entry-header">
			  <div style="float:left; max-width:500px;">
	  <h2 class="teamHeading">';

	  the_title();
	  
	  echo '</h2> 
	  </div>
	  ';
	$dterms = get_the_terms( $post->ID , 'division' );

	foreach ( $dterms as $dterm ) {
	    $dlink = get_term_link( $dterm );
		$division =  $dterm->name;
	}


	$lterms = get_the_terms( $post->ID , 'league' );

	foreach ( $lterms as $lterm ) {
		$llink = get_term_link( $lterm );
		$league =  $lterm->name;
	}


	$orderby = 'date';
	$order = 'DESC';


	if (isset($_GET['sort'])) {
		switch ($_GET['sort']) {
		
			case "newest" :
				$orderby = 'date';
				$order = 'DESC';
			break;
		
			case "oldest" :
				$orderby = 'date';
				$order = 'ASC';		
			break;
		
			case "replies" :
				$orderby = 'comment_count';
				$order = 'DESC';		
			break;
		
		}
	}



    // Find connected pages
    $connected = new WP_Query(
		array(
			'orderby' => $orderby,
			'order' => $order,
			'connected_type' => 'articles_to_teams',
			'connected_items' => $post,
			'nopaging' => true
		)
	);
$articlesNum = $connected->post_count;


}

?>

<div style="float:right; ">
<?php echo "<a href='".$llink."'>". $league . "</a> / <a href='".$dlink."'>". $division . "<br />"; ?>
</div>
<div style="clear:both;">&nbsp;</div>
	  <hr class="teamHeader_hr">
	  <ul id="navlist">
		<li class="active"><a href="/team/<?php echo $post->post_name; ?>">The Arena (<?php comments_number( '', '1', '%' ); ?>)</a></li>
		<li><a href="/article/?t=<?php echo $post->post_name; ?>">Articles (<?php echo $articlesNum; ?>)</a></li>
		<li><a href="#">Scores</a></li>
		<li><a href="#">Merchandise</a></li>		
	</ul>
	  <hr  class="teamHeader_hr">
          </header>


<?php

$serverURI = preg_replace("#&sort=.*#", '', $_SERVER['REQUEST_URI']);	
$serverURI = preg_replace("#&filter=.*#", '', $serverURI);	

if (isset($_GET['filter'])) {
	$filter = $_GET['filter'];
} else {
		$filter = 'all';
}

if (isset($_GET['sort'])) {
	$sort = $_GET['sort'];
} else {
		$sort = 'newest';
}
$newestLink = $serverURI . "&sort=newest&filter=".$filter;
$oldestLink = $serverURI . "&sort=oldest&filter=".$filter;
$repliesLink = $serverURI . "&sort=replies&filter=".$filter;

$allLink = $serverURI . "&sort=".$sort."&filter=all";
$fanLink = $serverURI . "&sort=".$sort."&filter=fan";
$foeLink = $serverURI . "&sort=".$sort."&filter=foe";

	*/
?>