<?php
include_once('loader.php');

function getCategories($user_id=NULL){
	global $mysqli;
	$query = "select * from likes_forums_relations where approved = 0";
	$result = $mysqli->query($query);
	
	$likes = array();
	$forumsCache = array();
	$list = array();
	while ($row = $result->fetch_assoc()){
		$forumid = $row['forum_id'];
		if (!is_null($forumsCache[$forumid])){
			$forum = $forumsCache[$forumid];
			
		}
		
		else{
			$res = $mysqli->query("select forum_name from forums_list where forumid=".$forumid." limit 1");
			$nrow = $res->fetch_assoc();
			$forum_name = $nrow['forum_name'];
			
			$forum->forum_id = $forumid;
			$forum->forum_name = $forum_name;
			$forumsCache[$forumid] = $forum;
			$list[$row['like_id']] = array();
		}
		
		
		$forum = '';
		$forum->forum_id = $forumid;
		$forum->forum_name = $forum_name;
		//$list[$row['like_id']] = array();
		if (!is_null($list[$row['like_id']])){
			array_push($list[$row['like_id']],$forum);	
		}
		
	}
	return $list;
	
}
?>