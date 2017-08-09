<div class="my-teams">
    <h2 class="red-header">Teams</h2>
    <div class="light-bg teams-container">
        <?php
        $favorites = get_user_meta( get_current_user_id(), "favorites", false );
        foreach( $favorites as $team_id ) {
            $title = get_the_title( $team_id );
            if( $title != 'Favorites' )
                echo fsu_team_circle( $team_id, true );
        }
        ?>
    </div>
</div>