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
    <title>ReSoC - Mes abonnés </title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>

    <?php

    $laQuestionEnSql = "SELECT 
                users.alias AS userAlias
                FROM users WHERE id= '$userId' ";
    $lesInformations = $mysqli->query($laQuestionEnSql);
    $user = $lesInformations->fetch_assoc();
    ?>
    <h2>Mes abonnés</h2>
    <div id="wrapper">


        <main class='contacts'>
            <?php

            $laQuestionEnSql = "
                    SELECT users.*,
                    users.alias AS userAlias,
                    users.id AS userId
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            while ($follower = $lesInformations->fetch_assoc()) {

            ?>
                <article>
                    <img src="user.jpg" alt="blason" />
                    <h3><?php echo $follower['userAlias'] ?></h3>
                    <p>Identifiant : <?php echo $follower['userId'] ?></p>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>