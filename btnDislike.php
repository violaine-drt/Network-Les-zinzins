<?php
$isLikedPost = isset($tableauDeLikes[$post['postId']]) ? $tableauDeLikes[$post['postId']] : false;

if (!$isLikedPost) {
    echo "Vous n'avez pas encore liké ce post";
} else {
    ?>
    <form method="post">
        <input type='submit' name="buttonD_<?php echo $post['postId']; ?>" value="Dislike">
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
        header("Location: wall.php?wall_id=$userId");
        exit();
    }
}
?>
