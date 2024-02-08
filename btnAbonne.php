<?php
$isWorking = isset($_POST['button']);
if ($isWorking) {
    $isFollowing = true;
    $lInstructionSql = "INSERT INTO followers (id, followed_user_id, following_user_id) "
        . "VALUES (NULL, "
        . "'" . $userId . "', "
        . "'" . $connectedId . "'"
        . ");";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "L'abonnement a échoué : " . $mysqli->error;
    } else {
        echo "Vous êtes bien abonné à : " . $userAlias;
    }
}

?>
<form method="post">
    <input type='submit' name="button" value="S'abonner">
</form>