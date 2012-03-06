<?php

function registerUser($user_id,$likes){
    global $mysqli;
    $likes_names = array();
    $items = array();

    $mysqli->query("INSERT IGNORE INTO users (user_id) VALUES (".$user_id . ")");
    $mysqli->query("INSERT IGNORE INTO fxp_users (user_id) VALUES (".$user_id . ")")

    $likes = get_object_vars($likes);
    $left = sizeof($likes['data']);

    $result = $mysqli->query("select likes_count from users where user_id='".$user_id."'");
    $row  = $result->fetch_assoc();
    $likes_count = $row['likes_count'];

    $result = $mysqli->query("select count(*) from users_likes_relations where user_id='".$user_id."'");

    $row  = $result->fetch_assoc();
    $existing_relations = $row['count(*)'];
    if ($likes_count == $left){
        return true;
    }
    else{
        $mysqli->query("update users set likes_count=" . $left." where user_id=".$user_id);
        return false;
    }
}

function updateCombinedTags($user_id,$likes){
  foreach($likes['data'] as $like_object){
        $like_object = get_object_vars($like_object);
        $likes_names[$like_object['id']] = $like_object['name'];
        $external_tags = getMappedTagID($like_object,'fxp');
        $user_tags[$like_object['id']] = $external_tags;
        saveUserCategories($user_tags);
   }
    return $user_tags;
}

function saveUserCategories($user_tags){
    global $mysqli;
    foreach ($user_tags as $like_id=>$external_tags){
        foreach ($external_tags as $external_tag){
            $query = "insert ignore into users_forums_relations (user_id,forum_id) values (".$user_id.",".$external_tag.")";
            $result = $mysqli->query($query);
        }
    }
}



?>