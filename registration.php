<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Inscription</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

<h1>Les zinzins zestés, un rézeau zympa !</h1>

    <div id="wrapper">
        <aside><img src="./apercu.png" alt="apercu du réseau"></aside>
        <main>
            <article>
                <h2>Inscription</h2>
                <?php

                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement) {

                    $new_email = $_POST['email'];
                    $new_alias = $_POST['pseudo'];
                    $new_passwd = $_POST['motpasse'];
                    $confirm_passwd = $_POST['confirmer_motpasse'];

                    // On compare les 2 entrées
                    if ($new_passwd !== $confirm_passwd) {
                        echo "<p style='color:red;'>Les mots de passe ne correspondent pas.</p>";
                    } else {
                    include 'importBdd.php';
                    $mysqli = importBdd();
                    //  Petite sécurité pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $new_email = $mysqli->real_escape_string($new_email);
                    $new_alias = $mysqli->real_escape_string($new_alias);
                    $new_passwd = $mysqli->real_escape_string($new_passwd);
                    // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                    $new_passwd = md5($new_passwd);
                    // NB: md5 est pédagogique mais n'est pas recommandée pour une vraies sécurité
                    $lInstructionSql = "INSERT INTO users (id, email, password, alias) "
                        . "VALUES (NULL, "
                        . "'" . $new_email . "', "
                        . "'" . $new_passwd . "', "
                        . "'" . $new_alias . "'"
                        . ");";
                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "L'inscription a échouée : " . $mysqli->error;
                    } else {
                        echo "Votre inscription est un succès : " . $new_alias;
                        echo " <a href='login.php'>Connectez-vous.</a>";
                    }
                }
            }
                ?>
                <form action="registration.php" method="post">
                    <dl>
                        <dt><label for='pseudo'>Pseudo</label></dt>
                        <dd><input type='text' name='pseudo' class="inputtxt"></dd>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email'class="inputtxt"></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse'class="inputtxt"></dd>
                        <dt><label for='motpasse'>Confirmation</label></dt>
                        <dd><input type='password' name='confirmer_motpasse'class="inputtxt"></dd>
                    </dl>
                    <input type='submit' class="inputbtn">
                </form>
                <p>
                Déjà inscrit ? <a href="login.php">Se connecter</p>
            </article>
        </main>
    </div>
</body>

</html>