<?php
if (!$isLikedPost) {
    include 'btnLike.php';
} else {
    include 'btnDislike.php';
}
?>
<a href="">#<?php echo $post['taglist'] ?></a>