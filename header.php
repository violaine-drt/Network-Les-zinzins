
<img src="./citron.png" alt="Logo de notre réseau social" />
<p>Les Zinzins Zestés</p>
        <nav id="menu">
            <a href="news.php">News</a>
            <a href="wall.php?wall_id=<?php echo $connectedId ?>">Wall</a>
            <a href="feed.php?user_id=<?php echo $connectedId ?>">Feed</a>
        </nav>
        <nav id="user">
            <a href="#">Profil</a>
            <ul>
                <li><a href="settings.php?user_id=<?php echo $connectedId ?>">Paramètres</a></li>
                <li><a href="followers.php?user_id=<?php echo $connectedId ?>">Abonnés</a></li>
                <li><a href="subscriptions.php?user_id=<?php echo $connectedId ?>">Abonnements</a></li>
                <li><a href="logout.php">Se déconnecter</a></li>
            </ul>

        </nav>
