<?php
if (!$isLikedPost) {
    include 'btnLike.php';
} else {
    include 'btnDislike.php';
}
?>
<a href="tags.php?tag_id=<?php echo $post['taglist'] ?>">#<?php echo $post['taglist'] ?></a>