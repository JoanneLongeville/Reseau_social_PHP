<?php                                                 $date_str = $post['created'];
$timestamp = strtotime($date_str);
$date_formatted = date("j F Y G\hi", $timestamp);
echo $date_formatted; 
?>