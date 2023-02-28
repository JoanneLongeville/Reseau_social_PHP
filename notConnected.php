<?php
if (!isset($_SESSION['connected_id'])){
    header('Location: login.php');
} else if(isset($_SESSION['connected_id']) && isset($_GET['user_id'])){
    $userId =intval($_GET['user_id']);
} else if(isset($_SESSION['connected_id']) && !isset($_GET['user_id'])){
    $userId =intval($_SESSION['connected_id']);
}            
?>
