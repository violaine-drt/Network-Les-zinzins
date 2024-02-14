<?php
include 'importBdd.php';
$mysqli = importBdd();
$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;

$wallId = isset($_GET['wall_id']) ? intval($_GET['wall_id']) : 0;

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
    <title>ReSoC - Actualités</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
    
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>
    <h2>Les news</h2>
    <div id="wrapper">

        <main>

            <?php
            $redirectionAdress = 'Location: news.php';
            if ($mysqli->connect_error) {
                echo "<article>";
                echo ("Échec de la connexion : " . $mysqli->connect_error);
                echo ("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                echo "</article>";
                exit();
            }
            //Récupérer les 10 derniers posts

            $chercherPostsDesActus = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,  
                    users.id as author_id, 
                    posts.id as postId,
                    count( DISTINCT likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 10
                    ";
            //Vérifier que la requête n'échoue pas
            $lesActualites = $mysqli->query($chercherPostsDesActus);
            if (!$lesActualites) {
                echo ("Échec de la requete chercherPostsDesActus: " . $mysqli->error);
            }

            $tableauDeLikes = array();
            while ($post = $lesActualites->fetch_assoc()) {
                $postId = $post['postId'];

                // Requête SQL pour vérifier si l'utilisateur a liké ce post spécifique
                $compterNbDeLikes = "SELECT COUNT(*) AS like_count FROM likes WHERE user_id = $connectedId AND post_id = $postId"; // compte le nb de like sur un post donné
                $likeResult = $mysqli->query($compterNbDeLikes);
                $tableauAssociatifDeLikes = $likeResult->fetch_assoc();
                $isLikedPost = $tableauAssociatifDeLikes['like_count'] > 0;

                $tableauDeLikes[$postId] = $isLikedPost;
                $taglist = $post['taglist'];

                $taglist = $post['taglist'];

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
            <?php
            }
            ?>

        </main>
    </div>
</body>

</html>