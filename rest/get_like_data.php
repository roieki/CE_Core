<?php

// get keyword, get category.

include ('loader.php');

$stop_at = $_GET["limit"];
$tag = $_GET["tag"];

$tag = cleanFreeText($tag);
$tag = get_tag($tag);

if (!$tag) {
	echo "tag doesnt exist!";
	exit;
}

$tag_id = $tag->id;

$i=0;
if ($result = $mysqli->query("SELECT * FROM likes_tags_relations WHERE tag_id ='".$tag_id."'")) {
 //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
    while ($row = $result->fetch_assoc()) {
        $like_id = $row["like_id"];
        if ($result2 = $mysqli->query("SELECT * FROM likes WHERE like_id =".$like_id." and is_like_data=0")) {
            if ($result2->num_rows < 1)  continue;
            if ($i++ > $stop_at) exit;
            $like= $result2->fetch_object();
            echo $i . ") " . $like->like_name . "<br>";
            $like_data = json_decode(file_get_contents("https://graph.facebook.com/" . $like->like_id),true);
            $like_id = $like->like_id;
            if ($like_data){
            //check and save in mongo +
                $add = array();
                save_like($like_data,$add,$like_id,true);
                $mysqli->query("UPDATE likes SET is_like_data=1 WHERE like_id=$like_id");
            }
            var_dump($like_data);
            echo "<br><br>";
            sleep(2);
        }
    }
}




function get_tag($tag){
	global $mysqli;
   	if ($result = $mysqli->query("SELECT * FROM tags WHERE value='".$tag."'")) {
   	  //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
   	  if ($result->num_rows < 1) return false;
   	  $tag= $result->fetch_object();
   	  $result->close();
   	  return $tag;
   	}
   	else return false;


    }
function cleanFreeText($text){
	$clean_text = strtolower($text);
   	$clean_text = trim($clean_text);
	return $clean_text;
    }





?>
