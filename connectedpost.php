<?php
session_start();
$userId = intval($_SESSION['connected_id']);
// BD
include 'importBdd.php';
$mysqli = importBdd();
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
        <img src="resoc.jpg" alt="Logo de notre réseau social" />
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="wall.php?user_id=5">Mur</a>
            <a href="feed.php?user_id=5">Flux</a>
            <a href="tags.php?tag_id=1">Mots-clés</a>
        </nav>
        <nav id="user">
            <a href="#">Profil</a>
            <ul>
                <li><a href="settings.php?user_id=5">Paramètres</a></li>
                <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
            </ul>
        </nav>
    </header>
    <div id="wrapper">
        <aside>
            <h2>Présentation</h2>
            <p><?php echo $user['userAlias'] ?>, quelque chose à dire ? </p>
        </aside>
        <main>
            <article>
                <h2>Poster un nouveau message</h2>
                <?php
                /**
       
                 * Récupération de la liste des auteurs
                 */
                // $listAuteurs = [];
                // $laQuestionEnSql = "SELECT * FROM users";;
                // $lesInformations = $mysqli->query($laQuestionEnSql);
                // while ($user = $lesInformations->fetch_assoc())
                // {
                //     $listAuteurs[$user['id']] = $user['alias'];
                // }
                /**
                 * TRAITEMENT DU FORMULAIRE
                 */
                // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                // si on recoit un champ email rempli il y a une chance que ce soit un traitement
                $enCoursDeTraitement = isset($_POST['message']);
                if ($enCoursDeTraitement) {
                    // $authorId = $_POST['auteur'];
                    $postContent = $_POST['message'];
                    //Etape 3 : Petite sécurité
                    // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $postContent = $mysqli->real_escape_string($postContent);
                    //Etape 4 : construction de la requete
                    $lInstructionSql = "INSERT INTO posts "
                        . "(id, user_id, content, created, parent_id) "
                        . "VALUES (NULL, "
                        . $userId . ", "
                        . "'" . $postContent . "', "
                        . "NOW(), "
                        . "NULL);";
                    // Etape 5 : execution
                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                    } else {
                        echo "Message posté " . $user['userAlias'];
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
                    <input type='submit'>
                </form>
            </article>
        </main>
    </div>
</body>

</html>