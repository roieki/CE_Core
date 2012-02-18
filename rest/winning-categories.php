<?php
//עברית
include('loader.php');

$start = time();
$facebook = new Facebook(array(
  'appId'  => '119906071429474',
  'secret' => '6515dd77294dc8bdeabaae0083c16957',
));

$user_id = $facebook->getUser();
if ($user_id){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="he">

<head>





	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<meta id="e_vb_meta_bburl" name="vb_meta_bburl" content="http://www.fxp.co.il" />

<meta http-equiv="X-UA-Compatible" content="IE=100"/>

<style type="text/css" id="vbulletin_css">

@import url("http://images.fxp.co.il/css_static_main/main_css.css");

</style>


<link rel="stylesheet" type="text/css" href="http://www.images.fxp.co.il/css_static/forumhome-rollup.css" />

<link rel="stylesheet" type="text/css" href="http://images.fxp.co.il/css_static_main/tfooter.css" />


		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 
	</head>
	<body>
	<center>
<?php

echo "<h1>פורומים מועדפים</h1>";

$result = $mysqli->query('select distinct * from users_forums_relations where user_id='.intval($user_id). " AND manual=1 ORDER BY score DESC LIMIT 10");
			  if ($result->num_rows > 0) {
			   while ($row = $result->fetch_assoc()) {
        //printf ("%s (%s)\n", $row["forum_id"], $row["score"]);
    
	//		  $object2= $result->fetch_object();
		//		$forumid = $object2->forum_id;
			//	$score = $object2->score;
				
				$forumid = $row["forum_id"];
				$score = $row["score"];
				
			  if ($result2 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$forumid")) {
		  //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
			//  if ($result->num_rows < 1) return false;
			  $object= $result2->fetch_object();
			  
			  $forum_name = $object->forum_name;
			  $catid = $object->catid;
			  
			  $result3 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$catid");
			  $object2= $result3->fetch_object();
			  $category_name= $object2->forum_name;
			  
			  echo "<br><a href=\"http://www.fxp.co.il/forumdisplay.php?f=$forumid\" target=\"_parent\">$forum_name</a> || <a href=\"http://www.fxp.co.il/forumdisplay.php?f=$catid\" target=\"_parent\">$category_name</a> <b>[$score]</b><br>";
			  
			}
			}
				////echo "UPDATE likes_forums_relations SET score=score+1 WHERE like_id=$likes_id and forum_id=$forumid"."<br>";	
				//$mysqli->query("UPDATE likes_forums_relations SET score=score+1 WHERE like_id=$likes_id and forum_id=$forumid");
}

			  
			  
			  
			  
echo "<h1>פורומים שיכולים להתאים לך</h1>";

$result = $mysqli->query('select distinct * from users_forums_relations where user_id='.intval($user_id). " AND manual=0 ORDER BY score DESC LIMIT 10");
			  if ($result->num_rows > 0) {
			   while ($row = $result->fetch_assoc()) {
        //printf ("%s (%s)\n", $row["forum_id"], $row["score"]);
    
	//		  $object2= $result->fetch_object();
		//		$forumid = $object2->forum_id;
			//	$score = $object2->score;
				
				$forumid = $row["forum_id"];
				$score = $row["score"];
				
			  if ($result2 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$forumid")) {
		  //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
			//  if ($result->num_rows < 1) return false;
			  $object= $result2->fetch_object();
			  
			  $forum_name = $object->forum_name;
			  $catid = $object->catid;
			  
			  $result3 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$catid");
			  $object2= $result3->fetch_object();
			  $category_name= $object2->forum_name;
			  
			  echo "<br><a href=\"http://www.fxp.co.il/forumdisplay.php?f=$forumid\" target=\"_blank\">$forum_name</a> || <a href=\"http://www.fxp.co.il/forumdisplay.php?f=$catid\" target=\"_blank\">$category_name</a> <b>[$score]</b><br>";
			  
}
}
				////echo "UPDATE likes_forums_relations SET score=score+1 WHERE like_id=$likes_id and forum_id=$forumid"."<br>";	
				//$mysqli->query("UPDATE likes_forums_relations SET score=score+1 WHERE like_id=$likes_id and forum_id=$forumid");
			  }
}

else{
	 $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream' ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';
}
?>
	</body>
</html>

