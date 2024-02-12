
<img src="resoc.jpg" alt="Logo de notre réseau social" />
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="wall.php?wall_id=<?php echo $connectedId ?>">Mur</a>
            <a href="feed.php?user_id=<?php echo $connectedId ?>">Flux</a>
            <a href="tags.php?tag_id=1">Mots-clés</a>
        </nav>
        <nav id="user">
            <a href="#">Profil</a>
            <ul>
                <li><a href="settings.php?user_id=<?php echo $connectedId ?>">Paramètres</a></li>
                <li><a href="followers.php?user_id=<?php echo $connectedId ?>">Mes suiveurs</a></li>
                <li><a href="subscriptions.php?user_id=<?php echo $connectedId ?>">Mes abonnements</a></li>
                <li><a href="logout.php">Se déconnecter</a></li>
            </ul>

        </nav>
