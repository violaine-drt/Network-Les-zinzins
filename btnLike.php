<?php
$isLikedPost = isset($tableauDeLikes[$post['postId']]) ? $tableauDeLikes[$post['postId']] : false;

if ($isLikedPost) {

    echo "Vous avez déjà liké ce post";
} else {
?>
    <form method="post">
    <button class="heart" type="submit" name="buttonL_<?php echo $post['postId']; ?>" style="background-image: url(./coeur.png); width: 32px; height: 32px; border: none; background-color: transparent"></button>
    <?php echo $post['like_number'];?>
    </form>
<?php
}

if (isset($_POST['buttonL_' . $post['postId']])) {
    $postId = $post['postId'];
    $lInstructionSql = "INSERT INTO likes (id, user_id, post_id) "
        . "VALUES (NULL, '$connectedId', '$postId')";
    $ok = $mysqli->query($lInstructionSql);
    if (!$ok) {
        echo "Le like a échoué : " . $mysqli->error;
    } else {
        header($redirectionAdress);
        exit();
    }
}
?>