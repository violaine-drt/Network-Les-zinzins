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
    <title>ReSoC - Mes abonnements</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
    <?php include 'header.php' ?>
    </header>
    <h2>Mes abonnements</h2>
    <div id="wrapper">


        <?php


        
        $laQuestionEnSql = "SELECT 
                    users.alias AS userAlias
                    FROM users WHERE id= '$userId' ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        $user = $lesInformations->fetch_assoc();

        ?>
       
        <main class='contacts'>
            <?php
            $userId = intval($_GET['user_id']);

            $laQuestionEnSql = "
                    SELECT users.*, 
                    users.alias AS userAlias,
                    users.profile_image AS userImage,
                    users.id AS userId
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            while ($follower = $lesInformations->fetch_assoc()) {

            ?>
                <article>
                    <?php if (!empty($follower['userImage'])) : ?>
                        <img src="<?php echo $follower['userImage']; ?>" alt="Photo de profil de <?php echo $follower['userAlias']; ?>" />
                    <?php else : ?>
                        <img src="user.jpg" alt="Photo de profil par défaut" />
                    <?php endif; ?>
                    <h3><a href="wall.php?wall_id=<?php echo $follower['userId'] ?>"><?php echo $follower['userAlias'] ?></a></h3>
                    <p>Identifiant : <?php echo $follower['userId'] ?></p>
                </article>
            <?php } ?>

        </main>
    </div>
</body>

</html>