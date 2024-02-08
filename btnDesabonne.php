<?php
$isWorking = isset($_POST['button']);
if ($isWorking) {
    $isFollowing = false;
    $lInstructionSql = "DELETE FROM followers WHERE followed_user_id=$userId and following_user_id=$connectedId ";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "L'abonnement a échoué : " . $mysqli->error;
    } else {
        echo "Vous êtes bien abonné à : " . $userAlias;
    }
}

?>
<form method="post">
    <input type='submit' name="button" value="Se désabonner">
</form>