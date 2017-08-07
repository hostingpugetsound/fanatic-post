<div class="my-teams">
    <h2 class="red-header">My Teams</h2>
    <div class="light-bg my-teams-container">
        <?php
        foreach ($favorites as $favorite) {
            $perma = get_permalink($favorite);
            $title = get_the_title($favorite);
            if ($title != 'Favorites')
                echo "<a href='".$perma."' data-id='".$post_id."'>".$title."</a><br />";
        }
        ?>
    </div>
</div>