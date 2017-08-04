


<div class="x-column x-sm x-1-4">

    <div class="news-sidebar">
        <h2>News</h2>
        <ul class="x-nav news-widget">
            <li class="news-single">
                <a href="<?php echo home_url(); ?>/about-us/"><img src="//lorempixel.com/293/293" /></a>
                <h3>Lorem Ipsum dolor Sit Amet, Consectetur</h3>
                <div class="author">
                    From <span class="source">Yahoo! News</span> - <time>6/21/17</time>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit... </p>
            </li>
            <li class="news-single">
                <a href="<?php echo home_url(); ?>/about-us/"><img src="//lorempixel.com/293/293" /></a>
                <h3>Lorem Ipsum dolor Sit Amet, Consectetur</h3>
                <div class="author">
                    From <span class="source">Yahoo! News</span> - <time>6/21/17</time>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit... </p>
                <?php x_get_view( 'global', '_ad' ); ?>
            </li>
            <li class="news-single">
                <a href="<?php echo home_url(); ?>/about-us/"><img src="//lorempixel.com/293/293" /></a>
                <h3>Lorem Ipsum dolor Sit Amet, Consectetur</h3>
                <div class="author">
                    From <span class="source">Yahoo! News</span> - <time>6/21/17</time>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit... </p>
            </li>
        </ul>
    </div>



    <?php if( !is_front_page() ) : ?>
    <div class="standings-sidebar">
        <h2>Standings</h2>
        <ul class="x-nav standings-nav">
            <li><a href="#">American</a></li>
            <li><a href="#">National</a></li>
            <li><a href="#">East</a></li>
            <li><a href="#">Central</a></li>
            <li><a href="#">West</a></li>
        </ul>

        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>WI</th>
                    <th>LO</th>
                    <th>%</th>
                    <th>G</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Team</td>
                    <td>00</td>
                    <td>00</td>
                    <td>00</td>
                    <td>00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php x_get_view( 'global', '_ad' ); ?>

    <?php endif; ?>




</div>