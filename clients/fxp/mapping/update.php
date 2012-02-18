<?php
include('../system/db/db_functions.php');


$like_id = $_POST['likeid'];
$forum_id = $_POST['forum_id'];
$approved = $_POST['approved'];
if ($forum_id != -1){
	update_like_forum($like_id,$forum_id,$approved);	
}
else {
	update_like_approved($like_id,$approved);
}

function update_like_forum($like_id,$forum_id,$approved){
	global $mysqli;
	$query = "insert into likes_forums_relations set like_id=".$like_id." , forum_id=".$forum_id.", approved=".$approved;
	$query.= " on duplicate key approved=" . $approved;
	//$query .= "update likes_forums_relations set approved=" . $approved;
	//" where like_id=".$like_id." and forum_id=".$forum_id; 

	if ($approved==-1)
	$query = "update likes_forums_relations set score=0 , approved=-1 where like_id=".$like_id." and forum_id=".$forum_id;

	$result = $mysqli->query($query);
	var_dump($query);
}

function update_like_approved($like_id,$approved){
	global $mysqli;
	$query = "update likes set approved=" . $approved . " where like_id=".$like_id;
	
	$result = $mysqli->query($query);
	var_dump($query);
}
?>