<?php

global $gameID, $ref_team_id, $team_view_type, $homeTeamNameLink, $homeTeamName, $awayTeamNameLink, $awayTeamLink, $awayTeamName;
global $gameType, $reverse_teams, $awayTeamID, $homeTeamID;
?>
<script type="text/javascript">
    var pageType = '<?php echo "beat_" . $gameID;?>';
</script>
<style>
    <?php
    if(isset($_GET['update']) && $_GET['update'])
    {
        ?>
    .beat_content_<?php echo bt_class_postfix($ref_team_id, $team_view_type)?>
    {
        display:none;
    }

    .gform_container_<?php echo bt_class_postfix($ref_team_id, $team_view_type)?>
    {
        display:block !important;
    }
    <?php
}
?>
</style>
<?php
$homeTeamNameLink = '<a href="'.$homeTeamLink.'">'. $homeTeamName .'</a>';
$awayTeamNameLink = '<a href="'.$awayTeamLink.'">'. $awayTeamName .'</a>';
?>
<meta property="og:title" content="<?php echo $homeTeamName . ' vs ' . $awayTeamName ?>" />
<meta property="og:type" content="game" />
<meta property="og:url" content="<?php echo $actual_link; ?>" />
<meta property="og:image" content="http://fanaticpost.com/wp-content/themes/x-child/framework/img/global/fanaticPostLogo.png" />
<meta property="og:site_name" content="FanaticPost" />
<meta property="og:description" content="Read the beat writers take on the <?php echo $homeTeamName . ' vs ' . $awayTeamName; ?> game on <?php echo $date; ?>" />
<header class="entry-header">

    <?php
    $homeTeamNameLink = '<a href="'.$homeTeamLink.'">'. $homeTeamName .'</a>';
    $awayTeamNameLink = '<a href="'.$awayTeamLink.'">'. $awayTeamName .'</a>';
    ?>
    <h1 class="gameHeading"><?php echo ($reverse_teams)? $awayTeamNameLink . ' at ' . $homeTeamNameLink : $homeTeamNameLink . ' vs ' . $awayTeamNameLink; ?></h1>
    <h2 class="gameHeading"><?php echo $date; echo !empty($gameType)? " - " . $gameType : ""; ?></h2>

    <br />

    <?php if($reverse_teams):?>
        <h2 class="gameHeading" style="float:left"><a href="<?= get_team_beat_page_link($awayTeamID); ?>"><?= $awayTeamName; ?></a><?php echo (is_numeric($awayScore))? ': ' . $awayScore:''?></h2>
        <h2 class="gameHeading" style="float:right"><a href="<?= get_team_beat_page_link($homeTeamID)?>"><?php echo $homeTeamName; ?></a><?php echo (is_numeric($homeScore))? ': ' . $homeScore:''?></h2>
    <?php else:?>
        <h2 class="gameHeading" style="float:left"><a href="<?= get_team_beat_page_link($homeTeamID)?>"><?php echo $homeTeamName; ?></a><?php echo (is_numeric($homeScore))? ': ' . $homeScore:''?></h2>
        <h2 class="gameHeading" style="float:right"><a href="<?= get_team_beat_page_link($awayTeamID); ?>"><?= $awayTeamName; ?></a><?php echo (is_numeric($awayScore))? ': ' . $awayScore:''?></h2>
    <?php endif;?>


    <br />
</header>