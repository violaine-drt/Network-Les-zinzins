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
        <aside>
            <p>Tu es bien déconnecté</p>
            <h1>Nous zommes triztes de te voir partir...</h1>
        </aside>

        <main>
            <article>

                <p>Déjà un compte ?
                    <a href="login.php">Se connecter</a>
                </p>
                <p>
                    Pas de compte ?
                    <a href='registration.php'>S'inscrire</a>
                </p>
            </article>
        </main>
    </div>
</body>

</html>