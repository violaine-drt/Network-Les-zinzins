<?php
include 'importBdd.php';
$mysqli = importBdd();

session_destroy();
echo "Vous êtes bien déconnecté";
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
        <aside>
            <h2>Présentation</h2>
            <p>Bienvenu sur notre réseau social.</p>
        </aside>
        <main>
            <article>
                <h2>Déjà un compte ?</h2>
                <p><a href="login.php">Se connecter.</a></p>
                <h2>Pas de compte?</h2>
                <p><a href='registration.php'>Inscrivez-vous.</a></p>
            </article>
        </main>
    </div>
</body>

</html>