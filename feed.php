<?php

include 'importBdd.php';
$mysqli = importBdd();
// Vérifier si un utilisateur est connecté
if (!isset($_SESSION['connected_id'])) {
    // Rediriger vers "login"
    header("Location: login.php");
    exit(); // Ici on sort après redirection (sécurité)
}



$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;


?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Flux</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>

    <div id="wrapper">
        <h2>Mon Feed</h2>

        <main>
            <?php

            $chercherPostsDeCeFeed =

                $lesPostsDeCeFeed = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id as author_id,
                    posts.id as postId,
                    count(DISTINCT likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM followers
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id
                    LEFT JOIN likes      ON likes.post_id  = posts.id
                    WHERE followers.following_user_id='$userId'
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query(
                $chercherPostsDeCeFeed
            );
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }


            $tableauDeLikes = array();
            $redirectionAdress = "Location: feed.php?user_id=$userId";

            while ($post = $lesInformations->fetch_assoc()) {
                $postId = $post['postId'];

                $ChercherLesPostsQueJaiLike = "
                    SELECT
                    likes.post_id AS postIdOfLike
                    FROM likes 
                    LEFT JOIN users ON likes.user_id  = users.id 
                    WHERE likes.user_id='$connectedId'
                    GROUP BY likes.post_id
                    ";

                // Requête SQL pour vérifier si l'utilisateur a liké ce post spécifique
                $compterNbDeLikes = "SELECT COUNT(*) AS like_count FROM likes WHERE user_id = $connectedId AND post_id = $postId";
                $likeResult = $mysqli->query($compterNbDeLikes);
                $tableauAssociatifDeLikes = $likeResult->fetch_assoc();
                $isLikedPost = $tableauAssociatifDeLikes['like_count'] > 0;

                $tableauDeLikes[$postId] = $isLikedPost;

                $leResultatDesPosts = $mysqli->query($ChercherLesPostsQueJaiLike);

            ?>

                <article>
                    <h3>
                    <time datetime='<?= $post['created'] ?>'>Le <?= date('d/m/Y', strtotime($post['created'])) ?> à <?= date('H:i:s', strtotime($post['created'])) ?></time>
                    </h3>
                    <address><a href="wall.php?wall_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>
                        <?php
                        include 'footer.php'
                        ?>
                    </footer>

                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>