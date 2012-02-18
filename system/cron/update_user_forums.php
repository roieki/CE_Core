<?php

include('facebook-php-sdk/src/facebook.php');
include('controller.php');

//Nadav

//$mysqli->query("TRUNCATE TABLE users_forums_relations");

$result3 = $mysqli->query('select * from users');
while ($row3 = $result3->fetch_assoc()) {

$user_id = $row3["user_id"];

$result = $mysqli->query('select * from users_likes_relations where user_id='.intval($user_id));
  while ($row = $result->fetch_assoc()) {
        //printf ("%s (%s)\n", $row["forum_id"], $row["score"]);
    
	//		  $object2= $result->fetch_object();
		//		$forumid = $object2->forum_id;
			//	$score = $object2->score;
				
				$like_id  = $row["like_id"];
  $result2 = $mysqli->query('select * from likes_forums_relations where like_id='.intval($like_id));
  while ($row2 = $result2->fetch_assoc()) {
 if (isset($users_forums[$row2["forum_id"]])) $users_forums[$row2["forum_id"]] += 1;
 else $users_forums[$row2["forum_id"]] = 1;

} 
	

	
	}

	
// remove from users_forums all forums that manual!=0
$result = $mysqli->query('select * from users_forums_relations where user_id='.intval($user_id). " AND manual!=0");
  while ($row = $result->fetch_assoc()) {
 if (isset($users_forums[$row["forums_id"]])) unset $users_forums[$row["forums_id"]];
 }


foreach ($users_forums as $forumid=>$score)
{


	$mysqli->query("UPDATE users_forums_relations
	SET score=$score
	WHERE user_id=$user_id AND forum_id=$forum_id AND manual=0");
			 }
			  
$mysqli->query("INSERT IGNORE INTO users_forums_relations (user_id,forum_id, score) VALUES (".intval($user_id).",".intval($forumid).",".  intval($score).") ");
}
		

}
?>