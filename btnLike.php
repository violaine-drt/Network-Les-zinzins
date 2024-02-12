<?php
$isLikedPost = isset($tableauDeLikes[$post['postId']]) ? $tableauDeLikes[$post['postId']] : false;

if ($isLikedPost) {
    echo "Vous avez déjà liké ce post";
} else {
?>
    <form method="post">
        <input type='submit' name="buttonL_<?php echo $post['postId']; ?>" value="Like">
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