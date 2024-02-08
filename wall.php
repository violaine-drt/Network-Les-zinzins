<?php

include 'importBdd.php';
$mysqli = importBdd();

$wallId = intval($_GET['wall_id']);

$connectedId = intval($_SESSION['connected_id']);

if ($wallId == $connectedId) {
    $userId = $connectedId;
    $myOwnWall = true;
} else {
    $userId = $wallId;
    $myOwnWall = false;
}


?>


<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>
    <div id="wrapper">


        <aside>
            <?php

            $laQuestionEnSql = "SELECT 
                users.alias AS userAlias
                FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            $userAlias = $user['userAlias'];
            $isFollowing = false;
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $userAlias ?>
                    (n° <?php echo $userId ?>)
                </p>
            </section>
            <?php
            if (!$myOwnWall) {
                if (!$isFollowing) {
                    include 'btnAbonne.php';
                } else {
                    include 'btnDesabonne.php';
                }
            }

            ?>

        </aside>
        <main>
            <?php

            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, 
                    users.alias as author_name, 
                    users.id as author_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";


            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            ?>
            <button><a href="connectedpost.php?user_id=<?php echo $connectedId ?>">Nouveau post<a /></button>
            <?php
            while ($post = $lesInformations->fetch_assoc()) {

            ?>

                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?wall_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>
                        <small>♥ <?php echo $post['like_number'] ?></small>
                        <a href="">#<?php echo $post['taglist'] ?></a>
                    </footer>
                </article>
            <?php } ?>


        </main>
    </div>
</body>

</html>