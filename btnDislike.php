<?php
$isWorking = isset($_POST['buttonD']);
if ($isWorking) {
    $isLikedPost= false;
    $lInstructionSql = "DELETE FROM likes WHERE  user_id=$connectedId and post_id=$postId ";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "Le dislike a échoué : " . $mysqli->error;
    } else {
        echo "Vous avez disliked ce post : ";
//redirige l'utilisateur sur la page où il était (pour réinitialiser le btn)
        header("Location: wall.php?wall_id=" . $userId);
    }
}

?>
<form method="post">
    <input type='submit' name="buttonD" value="Dislike">
</form>