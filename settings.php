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
    <title>ReSoC - Paramètres</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>

    <div id="wrapper" class='profile'>
        <h2>Mes Paramètres</h2>
        <?php
        $userId = intval($_SESSION['connected_id']);

        $laQuestionEnSql = "SELECT 
                    users.alias AS userAlias
                    FROM users WHERE id= '$userId' ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        $user = $lesInformations->fetch_assoc();

        ?>
        <main>
            <?php

            $laQuestionEnSql = "
                    SELECT users.*, 
                    users.alias AS userAlias,
                    users.id AS userId,
                    users.email AS userEmail,
                    users.password AS userPass,
                    count(DISTINCT posts.id) as totalpost, 
                    count(DISTINCT given.post_id) as totalgiven, 
                    count(DISTINCT recieved.user_id) as totalrecieved 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes as given ON given.user_id=users.id 
                    LEFT JOIN likes as recieved ON recieved.post_id=posts.id 
                    WHERE users.id = '$userId' 
                    GROUP BY users.id
                    LIMIT 100
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            while ($user = $lesInformations->fetch_assoc()) {
            ?>
                <article class='parameters'>

                    <dl>
                        <dt>Pseudo</dt>
                        <dd><a href="wall.php?wall_id=<?php echo $userId ?>"><?php echo $user['userAlias'] ?></a></dd>
                        <dt>Email</dt>
                        <dd><?php echo $user['userEmail'] ?></dd>
                        <dt>Nombre de message</dt>
                        <dd><?php echo $user['totalpost'] ?></dd>
                        <dt>Nombre de "J'aime" donnés </dt>
                        <dd><?php echo $user['totalgiven'] ?></dd>
                        <dt>Nombre de "J'aime" reçus</dt>
                        <dd><?php echo $user['totalrecieved'] ?></dd>
                    </dl>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>