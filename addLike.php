<?php 
if (isset($_SESSION['connected_id'])){ 
    $like_sent = isset($_POST['post_id']);
    if($like_sent){
        $session_id = $_SESSION['connected_id'];
        $current_post_id =  $_POST['post_id'];
        $verif_likesql = "SELECT * FROM likes WHERE likes.user_id = '$session_id' AND likes.post_id = '$current_post_id' ";
        $res = $mysqli->query($verif_likesql)->fetch_all();
        if($res == []){
            $add_like_sql = "INSERT INTO socialnetwork.likes (id, user_id, post_id) VALUES (NULL, '$session_id', '$current_post_id');";
            $ok = $mysqli->query($add_like_sql);
            if(!$ok){
                echo "L'ajout du like a échoué : " . $mysqli->error;
            }
        }else {
            $delete_like_sql = "DELETE FROM socialnetwork.likes WHERE user_id = '$session_id' AND post_id = '$current_post_id'";
            $ok = $mysqli->query($delete_like_sql);
            if (!$ok) {
                   echo "La suppression du like a échoué : " . $mysqli->error;
            }
        }
    }
}
?>