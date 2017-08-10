<?php

?>
<ul class="tabs">
    <li class="tab-link <?php echo get_active_class(($reverse_teams)? $awayTeamID : $homeTeamID, 'preview'); ?>" data-tab="<?php echo ($reverse_teams)? 'awaypregame':'homepregame';?>">
        <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $awayTeamPreview : $homeTeamPreview, ($reverse_teams)? $awayTeamID : $homeTeamID, 'preview'); ?>">Preview</a>
    </li>
    <li class="tab-link <?php echo get_active_class(($reverse_teams)? $awayTeamID : $homeTeamID, 'recap'); ?>" data-tab="<?php echo ($reverse_teams)? 'awaypostgame':'homepostgame'?>">
        <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $awayTeamRecap : $homeTeamRecap, ($reverse_teams)? $awayTeamID : $homeTeamID, 'recap'); ?>">Recap</a>
    </li>

    <li class="tab-link <?php echo get_active_class(($reverse_teams)? $homeTeamID : $awayTeamID, 'recap'); ?>" data-tab="<?php echo ($reverse_teams)? 'homepostgame':'awaypostgame'?>" style="float:right">
        <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $homeTeamRecap : $awayTeamRecap, ($reverse_teams)? $homeTeamID : $awayTeamID, 'recap'); ?>">Recap</a>
    </li>
    <li class="tab-link <?php echo get_active_class(($reverse_teams)? $homeTeamID : $awayTeamID, 'preview'); ?>" data-tab="<?php echo ($reverse_teams)? 'homepregame':'awaypregame'?>" style="float:right">
        <a href="<?php echo get_beat_distinct_url($gameID, ($reverse_teams)? $homeTeamPreview : $awayTeamPreview, ($reverse_teams)? $homeTeamID : $awayTeamID, 'preview'); ?>">Preview</a>
    </li>
</ul>