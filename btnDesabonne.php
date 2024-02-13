<?php
$isWorking = isset($_POST['button']);
if ($isWorking) {
    $isFollowing = false;
    $lInstructionSql = "DELETE FROM followers WHERE followed_user_id=$userId and following_user_id=$connectedId ";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "Le désabonnement a échoué : " . $mysqli->error;
    } else {
        echo "Vous ne suivez désormais plus " . $userAlias;
//redirige l'utilisateur sur la page où il était (pour réinitialiser le btn)
        header("Location: wall.php?wall_id=" . $userId);
    }
}

?>
<form class="wrap" method="post">
    <input class="button" type='submit' name="button" value="Se désabonner">
</form>