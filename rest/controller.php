<?php

include_once('loader.php');
$conf = $GLOBALS['conf'];

function check_user_favorite($user_id,$forum_id){
    global $mysqli;
    $result = $mysqli->query('select * from users_forums_relations where user_id='.intval($user_id). " AND forum_id=".$forum_id." AND manual=1");
    if ($result->num_rows > 0) {
        return true;
    }
    else return false;
}

function add_forum_to_favorites ($user_id, $forum_id){
	global $mysqli;
	$result = $mysqli->query('select * from users_forums_relations where user_id='.intval($user_id). " AND forum_id=$forum_id");
	if ($result->num_rows > 0) {
		// update manual = 1
		$mysqli->query("UPDATE users_forums_relations SET manual=1, score=10 WHERE user_id=$user_id AND forum_id=$forum_id");
       //echo "UPDATE users_forums_relations SET manual=1, score=10 WHERE user_id=$user_id AND forum_id=$forum_id";
	}
	else {
		$mysqli->query("INSERT INTO users_forums_relations (user_id,forum_id, score, manual) VALUES (".intval($user_id).",".intval($forum_id).",10, 1)");
        //echo "INSERT IGNORE INTO users_forums_relations (user_id,forum_id, score, manual) VALUES (".intval($user_id).",".intval($forum_id).",10, 1)";
	}
}

function delete_user_forum ($user_id, $forum_id){
	global $mysqli;
	// update manual = -1
	$mysqli->query("UPDATE users_forums_relations SET manual=-1, score=0 WHERE user_id=$user_id AND forum_id=$forum_id");
}


function is_user_register ($user_id)
{
    global $mysqli;
        $result = $mysqli->query('select * from users where user_id='.intval($user_id));
        if ($result->num_rows > 0) return true;
        else return false;

}
	
?>
