<?php
if (!$isLikedPost) {
    include 'btnLike.php';
} else {
    include 'btnDislike.php';
}
?>
<a href="tags.php?tag_id=<?php echo $post['taglist'] ?>">
<?php 
if (!is_null($post['taglist'])) {
    echo "#".$post['taglist'];
}
?></a>