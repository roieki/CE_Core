<?php
include_once("loader.php");

$user_id = $_POST['userid'];
echo checkUserExists($user_id);

function checkUserExists($user_id){
	global $mysqli;
	$query = "select * from users where user_id = " . intval($user_id);
    $result = $mysqli->query($query);
	if ($result->num_rows > 0){
		return "true";
	}
	else return "false";
}

?>