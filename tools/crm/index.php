<?php
include('loader.php');
global $conf;

$start = time();
$facebook = new Facebook(array(
  'appId'  => $conf['fbappid'],
  'secret' => $conf['fbappsecret'],
));

$user_id = $facebook->getUser();

//$forum_list = get_forums_list();
$target = $_GET['target'];


if ($user_id){
    $me = $facebook->api('/me','GET');
?>
<html>
	<head>
		<link href="../css/crm.css" rel="stylesheet">
		<link href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet">
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
		<script src="../js/md5-min.js" type="text/javascript"></script>
 		
 		<script src="../js/crm.js" type="text/javascript"></script>
 		
	</head>
	<body>
		<h1>Combined Effect - Like => Forum mapper</h1>
<?php
	$result = $mysqli->query('select * from likes where approved = 0 group by like_id');
	$left = $result->num_rows;
	$result = $mysqli->query('select * from likes where approved = -1 group by like_id');
	$skipped = $result->num_rows;
	$result = $mysqli->query('select * from likes where approved = 1 group by like_id');
	$completed = $result->num_rows;
	
	echo '<div class="stats">';
	echo '<span class="entry" type="left">Left: ' . $left . '</span>';
	echo '<span class="entry" type="skipped">Skipped: ' . $skipped . '</span>';
	echo '<span class="entry" type="completed">Completed: ' . $completed . '</span>';
	echo '</div>';
	
	
	if ($target != ''){
		switch ($target){
			case 'skipped':
				$app = -1;
				break;
			case 'completed':
				$app = 1;
				break;
			case 'left':
				$app = 0;
				break;
		}
	}
	else{
		$app = 0;
	} 
	$result = $mysqli->query('select * from likes where approved='.$app.' LIMIT 1');
	
	$object= $result->fetch_object();
	if (is_null($object)){
		echo "No results.";
		exit;
	}
	$likes_id = $object->like_id;
	$like_name = $object->like_name;
	
	$result = $mysqli->query("select * from likes_forums_relations where like_id=$likes_id ORDER BY score");
	
	//echo "select * from likes_forums_relations where like_id=$likes_id SORT BY score";
	echo "<div class='like' lid='".$likes_id."'>";
	echo "<div class='like_name'>$like_name</div>";
	
	while ($row = $result->fetch_assoc()) {
		$forumid = $row["forum_id"];
		$score = $row["score"];
					
		if ($result2 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$forumid")) {
			$object= $result2->fetch_object();
			$forum_name = $object->forum_name;
			$catid = $object->catid;
			$result3 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$catid");
			$object2= $result3->fetch_object();
			$category_name= $object2->forum_name;
			echo "<div class='category' like_id='".$likes_id."' catid='".$forumid."'><span class='content'>$forum_name - $category_name - $score</span>";
			  echo "<span class='controls'>";
			  	echo "<span class='yep button'>Yep</span>";
			  	echo "<span class='nope button'>Nope</span>";
				
			 echo "</span></div>";  
	
		}
	}
	
	
	echo '<div class="like_controls" lid="'.$likes_id.'">';
	echo "<span class='manualForum'>Manual Forum<input class='manualForumInput' type='text'></><input type='hidden' class='manualForum-id' /><span class='manualForumSubmit button'>Submit</span></span>";
	//echo "<span class='manualScore'>Manual Score<input class='manualScoreInput' type='text'></input><span class='manualScoreSubmit button'>Submit</span></span>";
	echo '<span class="navigation"><span class="skip button">Skip</span><span class="done button">Done</span></span>';
	echo '</div>';
	echo '</div>';
}
	


else{
	 $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream' ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';
}
?>
	</body>
</html>

