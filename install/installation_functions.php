<?php

function registerUser($user_id,$access_token){
    $likes_names = array();
    $likes = json_decode(file_get_contents("https://graph.facebook.com/" . $user_id . "/likes?" . $access_token));

    $items = array();

    $mysqli->query("INSERT IGNORE INTO users (user_id) VALUES (".$user_id . ")");
    $mysqli->query("INSERT IGNORE INTO fxp_users (user_id) VALUES (".$user_id . ")")


    //echo "new user installed";

    $likes = get_object_vars($likes);
    $left = sizeof($likes['data']);

    $result = $mysqli->query("select likes_count from users where user_id='".$user_id."'");
    $row  = $result->fetch_assoc();
    $likes_count = $row['likes_count'];

    $result = $mysqli->query("select count(*) from users_likes_relations where user_id='".$user_id."'");

    $row  = $result->fetch_assoc();
    $existing_relations = $row['count(*)'];
    if ($likes_count == $left){
        return $likes;
    }
    else{
        $mysqli->query("update users set likes_count=" . $left." where user_id=".$user_id);
        return false;
    }

}

?>