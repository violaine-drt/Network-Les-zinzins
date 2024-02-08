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
    <div id="wrapper">
        <aside>
            <h2>Présentation</h2>
            <p><?php echo $user['userAlias'] ?>, quelque chose à dire ? </p>
        </aside>
        <main>
            <article>
                <h2>Poster un nouveau message</h2>
                <?php
                $enCoursDeTraitement = isset($_POST['message']);
                if ($enCoursDeTraitement) {
                    $postContent = $_POST['message'];
                    //  Petite sécurité pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $postContent = $mysqli->real_escape_string($postContent);
                    $lInstructionSql = "INSERT INTO posts "
                        . "(id, user_id, content, created, parent_id) "
                        . "VALUES (NULL, "
                        . $userId . ", "
                        . "'" . $postContent . "', "
                        . "NOW(), "
                        . "NULL);";
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