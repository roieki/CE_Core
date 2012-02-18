<?php
include_once('loader.php');

removeForum($_POST['user_id'],$_POST['fid']);

function removeForum($user_id,$forum_id){
    global $mysqli;
    $query = "UPDATE users_forums_relations SET manual = -1 WHERE forum_id=" . intval($forum_id) . " and user_id=" . intval($user_id);
    $mysqli->query($query);
    echo $query;

}

?>