<?php
include 'importBdd.php';
$mysqli = importBdd();
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
            <h1>Bonzour !</h1>
            <p>Bienvenue zur notre rézeau zozial</p>
        </aside>
        <main>
            <article>
                <h2>Connexion</h2>
                <?php
                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement) {

                    $emailAVerifier = $_POST['email'];
                    $passwdAVerifier = $_POST['motpasse'];
                    //  Petite sécurité pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                    $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                    // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                    $passwdAVerifier = md5($passwdAVerifier);

                    $lInstructionSql = "SELECT * "
                        . "FROM users "
                        . "WHERE "
                        . "email LIKE '" . $emailAVerifier . "'";
                    $res = $mysqli->query($lInstructionSql);
                    $user = $res->fetch_assoc();
                    if (!$user or $user["password"] != $passwdAVerifier) {
                        echo "La connexion a échoué.";
                    } else {
                        session_start();
                        $_SESSION['connected_id'] = $user['id'];
                        $connectedId =  $user['id'];
                        header("Location: wall.php?wall_id=" . $connectedId);
                        exit();
                    }
                }
                ?>
                <form action="login.php" method="post">
                    <dl>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email' class="inputtxt"></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse' class="inputtxt"></dd>
                    </dl>
                    <input type='submit' class="inputbtn">
                </form>
                <p>
                    Pas de compte?
                    <a href='registration.php'>S'inscrire</a>
                </p>
            </article>
        </main>
    </div>
</body>

</html>