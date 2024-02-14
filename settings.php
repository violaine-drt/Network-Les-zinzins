<?php
include 'importBdd.php';
$mysqli = importBdd();
$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;

$messageDelete = ""; // Message pour la suppression
$message = ""; // Initialisation du message (erreur ou succès)
$messageClass = ""; // Initialisation de la classe du message pour un CSS en début de page
                    // Pas une bonne pratique mais ça permet de le forcer dans ce cas précis

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_password'])) {
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // On vérifie si l'ancien mot de passe est correct
        $userId = intval($_SESSION['connected_id']);
        $laQuestionEnSql = "SELECT password FROM users WHERE id = '$userId'";
        $result = $mysqli->query($laQuestionEnSql);
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        if ($hashedPassword === md5($oldPassword)) {
            // Comme dans Registration on vérifie si les 2 mots de passe sont les mêmes
            if ($newPassword === $confirmPassword) {
                // On met à jour le mot de passe dans la base de données
                $hashedNewPassword = md5($newPassword);
                $updateQuery = "UPDATE users SET password = '$hashedNewPassword' WHERE id = '$userId'";
                if ($mysqli->query($updateQuery) === TRUE) {
                    $message = "Mot de passe mis à jour avec succès.";
                    $messageClass = "success";
                } else {
                    $message = "Erreur lors de la mise à jour du mot de passe: " . $mysqli->error;
                    $messageClass = "error";
                }
            } else {
                $message = "Les nouveaux mots de passe ne correspondent pas.";
                $messageClass = "error";
            }
        } else {
            $message = "L'ancien mot de passe est incorrect.";
            $messageClass = "error";
        }
    } elseif (isset($_POST['delete_account'])) {
        // Vérification du mot de passe
        $confirmPasswordDelete = $_POST['confirm_password_delete'];
        $laQuestionEnSql = "SELECT password FROM users WHERE id = '$userId'";
        $result = $mysqli->query($laQuestionEnSql);
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        if ($hashedPassword === md5($confirmPasswordDelete)) {
            // Mot de passe confirmé, suppression du compte
            $deleteQuery = "DELETE FROM users WHERE id = '$userId'";
            if ($mysqli->query($deleteQuery) === TRUE) {
                // Déconnexion de l'utilisateur et redirection vers une page d'accueil par exemple
                session_destroy();
                header("Location: registration.php");
                exit;
            } else {
                $messageDelete = "Erreur lors de la suppression du compte: " . $mysqli->error;
                $messageClass = "error";
            }
        } else {
            // Mot de passe incorrect
            $messageDelete = "Mot de passe incorrect. Veuillez réessayer.";
            $messageClass = "error";
        }
    }
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Paramètres</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
    <style>
        .success {color: green;}
        .error {color: red;}
    </style>
</head>

<body>
    <header>
        <?php include 'header.php' ?>
    </header>
    <h2>Mes Paramètres</h2>
    <div id="wrapper" class='profile'>

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
                    <h3>Importer une photo de profil</h3>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <input type="submit" value="Uploader l'image" name="submit">
                    </form>
                </article>
            <?php } ?>
            <article class='parameters'>
            <?php
            if (isset($message)) {
                echo "<p class='$messageClass'>$message</p>";
            }
            ?>
                <h3>Changer le mot de passe</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="old_password">Ancien mot de passe :</label>
                    <input type="password" name="old_password" required><br><br>
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" name="new_password" required><br><br>
                    <label for="confirm_password">Confirmez le nouveau mot de passe :</label>
                    <input type="password" name="confirm_password" required><br><br>
                    <input type="submit" name="change_password" value="Changer le mot de passe">
                </form>
            </article>
            <article class='parameters'>
                <?php 
                if (isset($message)) {
                    echo "<p class='$messageClass'>$messageDelete</p>";
                }
                ?>
                <h3>Supprimer le compte</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
                    <label for="confirm_password_delete">Entrez votre mot de passe pour confirmer :</label>
                    <input type="password" name="confirm_password_delete" required><br><br>
                    <input type="submit" name="delete_account" value="Supprimer le compte">
                </form>
</article>
        </main>
    </div>
</body>

</html>