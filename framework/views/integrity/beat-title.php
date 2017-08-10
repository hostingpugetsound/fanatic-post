<?php

global $gameID, $ref_team_id, $team_view_type, $homeTeamNameLink, $homeTeamName, $homeTeamLink, $homeScore, $awayTeamNameLink, $awayTeamLink, $awayTeamName, $awayScore;
global $gameType, $reverse_teams, $awayTeamID, $homeTeamID, $date;
?>
<script type="text/javascript">
    var pageType = '<?php echo "beat_" . $gameID;?>';
</script>
<style>
    <?php if(isset($_GET['update']) && $_GET['update']) { ?>
    .beat_content_<?php echo bt_class_postfix($ref_team_id, $team_view_type)?> {
        display:none;
    }
    .gform_container_<?php echo bt_class_postfix($ref_team_id, $team_view_type)?> {
        display:block !important;
    }
    <?php } ?>
</style>
<meta property="og:title" content="<?php echo $homeTeamName . ' vs ' . $awayTeamName ?>" />
<meta property="og:type" content="game" />
<meta property="og:url" content="<?php echo $actual_link; ?>" />
<meta property="og:image" content="http://fanaticpost.com/wp-content/themes/x-child/framework/img/global/fanaticPostLogo.png" />
<meta property="og:site_name" content="FanaticPost" />
<meta property="og:description" content="Read the beat writers take on the <?php echo $homeTeamName . ' vs ' . $awayTeamName; ?> game on <?php echo $date; ?>" />
<header class="entry-header">

    <!--
    <h1 class="gameHeading"><?php echo ($reverse_teams)? $awayTeamNameLink . ' at ' . $homeTeamNameLink : $homeTeamNameLink . ' vs ' . $awayTeamNameLink; ?></h1>
    <h2 class="gameHeading"><?php echo $date; echo !empty($gameType)? " - " . $gameType : ""; ?></h2>
    -->

    <br />

    <?php if($reverse_teams): ?>
        <h3 style="float:left"><?php echo $awayTeamName; echo maybe_echo_score($awayScore) ? ': ' . maybe_echo_score($awayScore) : ''; ?></h3>
        <h3 style="float:right"><?php echo $homeTeamName; echo maybe_echo_score($homeScore) ? ': ' . maybe_echo_score($homeScore) : ''; ?></h3>
    <?php else:?>
        <h3 style="float:right"><?php echo $homeTeamName; echo maybe_echo_score($homeScore) ? ': ' . maybe_echo_score($homeScore) : ''; ?></h3>
        <h3 style="float:left"><?php echo $awayTeamName; echo maybe_echo_score($awayScore) ? ': ' . maybe_echo_score($awayScore) : ''; ?></h3>
    <?php endif;?>


    <br />
</header>