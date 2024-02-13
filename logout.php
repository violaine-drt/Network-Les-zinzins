<?php
include 'importBdd.php';
$mysqli = importBdd();

session_destroy();

?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Connexion</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div id="wrapper">


        <main>
            <article>
                <p>Vous êtes bien déconnecté</p>
                <h3>Déjà un compte ?</h3>
                <p><a href="login.php">Se connecter.</a></p>
                <h3>Pas de compte ?</h3>
                <p><a href='registration.php'>Inscrivez-vous.</a></p>
            </article>
        </main>
    </div>
</body>

</html>