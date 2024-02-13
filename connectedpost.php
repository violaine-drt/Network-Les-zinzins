<?php
include 'importBdd.php';
$mysqli = importBdd();

$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;

$laQuestionEnSql = "SELECT
users.alias AS userAlias
FROM users WHERE id= '$userId' ";
$lesInformations = $mysqli->query($laQuestionEnSql);
$user = $lesInformations->fetch_assoc()
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Nouveau post</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>
    <h2><?php echo $user['userAlias'] ?>, quelque chose à dire ? </h2>
    <div id="wrapper">


        <main>
            <article>
                <h2>Poster un nouveau message</h2>
                <?php

                $enCoursDeTraitement = isset($_POST['message']);
                if ($enCoursDeTraitement) {
                    $postContent = $_POST['message'];
                    //On utilise la fonction extraireTags pour repérer tous les hashtags
                    $tags = extraireTags($postContent);
                    //  Petite sécurité pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $postContent = $mysqli->real_escape_string($postContent);
                    $EnregistrerLeMessage = "INSERT INTO posts "
                        . "(id, user_id, content, created, parent_id) "
                        . "VALUES (NULL, "
                        . $userId . ", "
                        . "'" . $postContent . "', "
                        . "NOW(), "
                        . "NULL);";
                    $ok = $mysqli->query($EnregistrerLeMessage);
                    if (!$ok) {
                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                    } else {
                        echo "Message posté, " . $user['userAlias'];
                        // Récupérer l'id du post que l'on vient d'ajouter (la propriété insert_id retourne l'id de la dernière ligne insérée en requete)
                        $leDernierPostAjouté = $mysqli->insert_id;
                        // On utilise la fonction saveTags pour enregistrer les tags en les insérant en BDD. Cette fonction prend en argument l'id du post et un tableau de tags
                        enregistrerTagsDeCePost($leDernierPostAjouté, $tags);
                    }
                }
                //Fonction pour repérer les mots commencant par "#" et les stocker dans un tableau tags
                function extraireTags($text)
                {
                    preg_match_all("/#(\w+)/", $text, $tags);
                    return $tags[1];
                }
                //Fonction qui enregistre les hashtags en BDD dans la table tags
                function enregistrerTagsDeCePost($postId, $tags)
                {
                    global $mysqli;
                    global $userId;
                    //échappement caractères spéciaux
                    $userId = $mysqli->real_escape_string($userId);
                    //Boucle pour parcourir le tableau de tags
                    foreach ($tags as $tag) {
                        // Échapper les caractères spéciaux dans le tag pour éviter les injections SQL
                        $tag = $mysqli->real_escape_string($tag);
                        // Cette requête insère le tag en BDD s'il n'existe pas déjà
                        $query = "INSERT INTO tags (label) VALUES ('$tag') ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
                        $mysqli->query($query);
                        // On récupère l'id du tag, maintenant qu'on est sûrs qu'il est en BDD
                        $tagIdResult = $mysqli->query("SELECT id FROM tags WHERE label = '$tag'");
                        //Extrait l'id du tag
                        $tagId = $tagIdResult->fetch_assoc()['id'];
                        //On enregistre le tag dans la table de liaison entre les posts et les tags
                        $mysqli->query("INSERT INTO posts_tags (post_id, tag_id) VALUES ($postId, $tagId)");
                    }
                }


                ?>
                <form action="connectedpost.php" method="post">
                    <dl>
                        <dt><label for='auteur'>Auteur</label></dt>
                        <dd>
                            <p><?php echo $user['userAlias'] ?></p>
                        </dd>
                        <dt><label for='message'>Message</label></dt>
                        <dd><textarea name='message'></textarea></dd>
                    </dl>
                    <input type='submit' value='Poster'>
                </form>
            </article>
        </main>
    </div>
</body>

</html>