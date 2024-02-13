<?php
include 'importBdd.php';
$mysqli = importBdd();
$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;

?>


<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Les message par mot-clé</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>
    <h2>Sur cette page vous trouverez les derniers messages comportant
            le mot-clé #<?php echo $tag['taglist'] ?></h2>
    <div id="wrapper">

        <?php
        $tagId = $_GET['tag_id'];

        $laQuestionEnSql = "SELECT
                tags.label AS taglist
                FROM tags WHERE label= '$tagId' ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        $tag = $lesInformations->fetch_assoc();
        ?>


        <main>
            <?php
            $redirectionAdress = "Location: tags.php?tag_id=$tagId";
            $chercherPostsDesActus = "
                    SELECT 
                        posts.content,
                        posts.created,
                        users.alias AS author_name,  
                        users.id AS author_id,
                        posts.id AS postId,
                        COUNT(DISTINCT likes.id) AS like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM 
                        posts
                    JOIN 
                        users ON users.id = posts.user_id
                    LEFT JOIN 
                        posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN 
                        tags ON posts_tags.tag_id = tags.id 
                    LEFT JOIN 
                        likes ON likes.post_id = posts.id 
                    WHERE 
                        tags.label = '$tagId' 
                    GROUP BY 
                        posts.id
                    ORDER BY 
                        posts.created DESC  
                    LIMIT 
                        10";

            $lesInformations = $mysqli->query($chercherPostsDesActus);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            while ($post = $lesInformations->fetch_assoc()) {
                $postId = $post['postId'];

                // Requête SQL pour vérifier si l'utilisateur a liké ce post spécifique
                $compterNbDeLikes = "SELECT COUNT(*) AS like_count FROM likes WHERE user_id = $connectedId AND post_id = $postId"; // compte le nb de like sur un post donné
                $likeResult = $mysqli->query($compterNbDeLikes);
                $tableauAssociatifDeLikes = $likeResult->fetch_assoc();
                $isLikedPost = $tableauAssociatifDeLikes['like_count'] > 0;
                $tableauDeLikes[$postId] = $isLikedPost;
            ?>
                <article>
                    <h3>
                    <time datetime='<?= $post['created'] ?>'>Le <?= date('d/m/Y', strtotime($post['created'])) ?> à <?= date('H:i:s', strtotime($post['created'])) ?></time>
                    </h3>
                    <address><a href="wall.php?wall_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer><?php include 'footer.php' ?></footer>
                </article>
            <?php } ?>

        </main>
    </div>
</body>

</html>