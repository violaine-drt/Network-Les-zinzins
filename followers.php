<?php
    include 'importBdd.php';
    $mysqli = importBdd();

    
    $connectedId = intval(  $_SESSION['connected_id']);
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

    <!-- // Etape 1: récupérer l'id de l'utilisateur -->
    <?php


    $laQuestionEnSql = "SELECT 
                users.alias AS userAlias
                FROM users WHERE id= '$userId' ";
    $lesInformations = $mysqli->query($laQuestionEnSql);
    $user = $lesInformations->fetch_assoc();
    ?>
    <div id="wrapper">
        <aside>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes qui
                    suivent les messages de l'utilisatrice <a href="wall.php?user_id=<?php echo $userId ?>"><?php echo $user['userAlias'] ?></a>
                    (n° <?php echo $userId ?>)</p>

            </section>
        </aside>
        <main class='contacts'>
            <?php


            // Etape 3: récupérer le nom de l'utilisateur
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

            // Etape 4: à vous de jouer
            //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
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