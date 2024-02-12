<?php
$isWorking = isset($_POST['buttonL']);
if ($isWorking) {
    $isLikedPost = true;
    $lInstructionSql = "INSERT INTO likes (id, user_id, post_id) "
        . "VALUES (NULL, "
        . "'" . $connectedId . "', "
        . "'" . $postId . "'"
        . ");";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "Le like a échoué : " . $mysqli->error;
    } else {
        echo "Vous avez liké ce post : ";
//redirige l'utilisateur sur la page où il était (pour réinitialiser le btn)
        header("Location: wall.php?wall_id=" . $userId);
    }
}

?>
<form method="post">
    <input type='submit' name="buttonL" value="Like">
</form>