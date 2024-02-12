<?php
$isLikedPost = isset($tableauDeLikes[$post['postId']]) ? $tableauDeLikes[$post['postId']] : false;

if (!$isLikedPost) {
    echo "Vous n'avez pas encore liké ce post";
} else {
?>
    <form method="post">
        <button type="submit" name="buttonD_<?php echo $post['postId']; ?>" style="background-image: url(./aimer.png); width: 32px; height: 32px; border: none; background-color: transparent"></button>
        <?php echo $post['like_number']; ?>
    </form>
<?php
}

if (isset($_POST['buttonD_' . $post['postId']])) {
    $postId = $post['postId'];
    $lInstructionSql = "DELETE FROM likes WHERE user_id='$connectedId' AND post_id='$postId'";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "Le dislike a échoué : " . $mysqli->error;
    } else {
        header($redirectionAdress);
        exit();
    }
}
?>