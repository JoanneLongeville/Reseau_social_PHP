<?php
// vérifier si l'utilisateur a déjà aimé le post
$liked = false;
$session_id = $_SESSION['connected_id'];
$sql2 = "SELECT * FROM likes WHERE user_id=$session_id AND post_id={$post['post_id']}";
$result2 = $mysqli->query($sql2);
if (mysqli_num_rows($result2) > 0) {
    $liked = true;
}
?>

<form method="post">
    <input type="hidden" value="<?php echo $post['post_id'] ?>" name="post_id"></input>
    <input type="hidden" value="<?php echo $liked ? 'unlike' : 'like' ?>" name="action"></input>
    <input class="<?php echo $liked ? 'unlikebutton' : 'likebutton' ?>" type='submit' value="♥ <?php echo $post['like_number'] ?>">
</form>