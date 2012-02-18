<?php
//עברית
include_once("db.php");


$data = file_get_contents("http://fxp7.spdsites.com/Get_forum_list.php");

$forum_tupples = explode("<br>", $data);

foreach ($forum_tupples as $forum_tupple)

	{
	$insert_data = explode(",", $forum_tupple);
	$i=1;
	foreach ($insert_data as $insert)
		{
		
		
		//echo $insert . "<br>";
			// fourmid, catid, forumname
		switch ($i) {
    
			case 1:
				$forumid= $insert;
				break;
			case 2:
				$catid = $insert;
				break;
			case 3:
				$forum_name = $insert;
				break;	
		}
		$i++;
			//global $mysqli;
		  
		}
			if ($result = $mysqli->query("INSERT INTO forums_list (forumid,catid, forum_name) VALUES (".intval($forumid).",".intval($catid).",'". $forum_name."') ON DUPLICATE KEY UPDATE catid=" .intval($catid). ", forum_name='". $forum_name."'")) {
			//insert success 
			echo "forum updated: forum name = $forum_name, forumid= $forumid, forum category= $catid <br>";
			} else echo "FAILED: forum name = $forumname, forumid= $forumid, forum category= $catid <br>";

	}
	
	
	
?>