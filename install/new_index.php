<?php
error_reporting(1);

include_once('loader.php');
global $mysqli;

$conf = $GLOBALS['conf'];

ob_start();

$start = time();

$facebook = new Facebook(array(
  'appId'  => $conf['fbappid'],
  'secret' => $conf['fbappsecret'],
));

if (isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];
    $access_token = $_GET['at'];
}

else{
	$user_id = $facebook->getUser();
}
?>

<html>
	<header>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            body{
                background-color: white;
                font-family: arial;
                direction: rtl;
            }
        </style>
	</header>
	<body>




<?php

//If we have Facebook user id
if ($user_id){
	$likes_names = array();
	$likes = json_decode(file_get_contents("https://graph.facebook.com/" . $user_id . "/likes?" . $access_token));

    $items = array();

    //Create and register a user
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

    //Check that user has the same number fo lkes
    //TODO: Why did we check existing relations count?
    //if ($likes_count == $left || $left == $existing_relations){
    if ($likes_count == $left){
        echo "<script>window.location.href = 'http://combined-effect.com/CE_core/rest/user_categories_display.php?userid=" . $user_id . "';</script>";
    }
    else{
        $mysqli->query("update users set likes_count=" . $left." where user_id=".$user_id);
        ob_flush(); flush();
    }


    echo "מתקין...";
    ob_flush(); flush();

    foreach($likes['data'] as $like_object){
		//echo $left . " left...<br>";
		$like_object = get_object_vars($like_object);
        $left--;

        echo ".";
        ob_flush(); flush();
		//echo $like_object['id'] . "<br>";
		$likes_names[$like_object['id']] = $like_object['name'];
		///echo $like_object['name'] . " || ";
        $mysqli->query("INSERT IGNORE INTO likes (like_id,like_name, approved) VALUES (".$like_object['id'].",'". $like_object['name']."', 0)");


        //WE ARE HERE
        $result = $mysqli->query("select * from likes_tags_relations where like_id=" . $like_object['id']);
        $additional = get_object_vars(json_decode(file_get_contents("http://graph.facebook.com/" .$like_object['id'])));
		$cached_like = get_like_by_object_id($like_object['id']);

		$result = $mysqli->query("select * from users_likes_relations where user_id=$user_id and like_id=". $like_object['id']);
		if ($result->num_rows < 1) $mysqli->query("INSERT IGNORE INTO users_likes_relations (user_id,like_id) VALUES (".intval($user_id).",".intval($like_object['id']).")");

        ob_flush(); flush();

		$links = array();
		//Found like in the DB
        if ($cached_like->found){
			$cached_bing_res = $cached_like->data['bing_data']['data'];
			if ($cached_bing_res[0] != 'empty'){
                foreach ($cached_bing_res as $entry){
                    $links[] = $entry;
                }
            }
            //No data was found in bing
            else {
                continue;
            }
        }
		else{
            echo ":";
            ob_flush(); flush();
			$links = process($like_object,$additional,$user_id);
		}
		$ids = array();

        //If we have cached categories
        if (is_array($links['categories'])){
            $categories[$like_id] = $link['categories'];
            $cachedCategories = true;
        }
        else{
            foreach ($links as $link){
                if ($link == "empty") continue;
                if (preg_match ("/showthread\.php\?t\=([0-9]+)$/" , $link['url'], $m)){
                    //if (preg_match("/t-([0-9]+)\.html/" , $link->url, $m)){
                    //?!?!?!?!??! TODO ?!?!?!?!? Like id doesn't exist yet!?!
                    set_object_relation($like_id,'like',$forum_id,'forum');
                    echo "(";
                    ob_flush(); flush();
                    save_forum_relation_db($like_id,$forum_id);
                    echo ")";
                    ob_flush(); flush();
                    $ids[] = $m[1];
                }
            }
            if (!is_array($ids)) continue;

            $items[$like_object['id']] = join(",",$ids);
            //var_dump($items[$like_object['id']]);
            $cachedCategories = false;

            //TODO: deal with likes that really don't have categories with TTL (don't search every time)
        }

	}
 	//if there are some missing categories
    if (!$cachedCategories){
        foreach ($items as $like_id=>$url_ids){
            if ($url_ids == '') continue;
            $furl = "http://fxp7.spdsites.com/Get_forum_id.php?ids=" .$url_ids;
            $res = file_get_contents($furl);
            $categoriesAr = explode("<br>",$res);
            save_categories($like_id,$categoriesAr);
            $categories[$like_id] = $categoriesAr;
        }
    }

	//Save categories data
    echo "מכין ביצה...";
    ob_flush(); flush();
	foreach ($categories as $likes_id=>$catAr){
		foreach ($catAr as $forumid){
			if ($result = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$forumid")) {
			  if ($result->num_rows < 1) continue;
			  $object= $result->fetch_object();
			  $forum_name = $object->forum_name;
			  $catid = $object->catid;
			  $result = $mysqli->query('select * from likes_forums_relations where like_id='.intval($likes_id).' and forum_id='.intval($forumid));
			  if (isset($like_tupples["$likes_id,$forumid,$catid"])) $like_tupples["$likes_id,$forumid,$catid"]++;
			  else $like_tupples["$likes_id,$forumid,$catid"] = 1;

			$result->close();
			}
		}
	}

    echo "עורך שולחן...";
    ob_flush(); flush();
	foreach ($like_tupples as $like_tupple=>$score){
		$pieces = explode(",", $like_tupple);

		$likes_id = $pieces[0];
		$forumid = $pieces[1];
		$catid =$pieces[2];

		$result = $mysqli->query('select * from likes_forums_relations where like_id='.intval($likes_id).' and forum_id='.intval($forumid));
		if ($result->num_rows > 0) {
		}
		else{
			$mysqli->query("INSERT IGNORE INTO likes_forums_relations (like_id,forum_id, category_id, approved, score) VALUES (".intval($likes_id).",".intval($forumid).",".  intval($catid).",0,$score)");
		}
	}
    echo "עושה כלים...";
        ob_flush(); flush();

	$result = $mysqli->query('select * from users_likes_relations where user_id='.intval($user_id));
	while ($row = $result->fetch_assoc()) {
		$like_id  = $row["like_id"];
		$result2 = $mysqli->query('select * from likes_forums_relations where like_id='.intval($like_id));
		while ($row2 = $result2->fetch_assoc()) {
			if (isset($users_forums[$row2["forum_id"]])) $users_forums[$row2["forum_id"]] += $row2["score"];
			else $users_forums[$row2["forum_id"]] = $row2["score"];
	  	}
	}

	foreach ($users_forums as $forumid=>$score){
		$mysqli->query("INSERT IGNORE INTO users_forums_relations (user_id,forum_id, score) VALUES (".intval($user_id).",".intval($forumid).",".  intval($score).") ");
	}


	echo "<script>window.location.href = 'http://combined-effect.com/CE_core/rest/user_categories_display.php?userid=" . $user_id . "';</script>";
	echo "<a href='".$facebook->getLogoutUrl()."'>Logout</a>";

}

//if user is not logged in
else{
	 $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream,user_likes' ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';
}

?>
	</body>
</html>


