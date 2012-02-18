<?php

// get keyword, get category.

include ('../loader.php');

$stop_at = $_GET["limit"];
$tag = $_GET["tag"];

$tag = cleanFreeText($tag);
$tag = get_tag($tag);
ob_start();


if (!$tag) {
	echo "tag doesnt exist!";
	exit;
}

$tag_id = $tag->id;

$i=0;
if ($result = $mysqli->query("SELECT * FROM likes_tags_relations WHERE tag_id ='".$tag_id."'")) {
 //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
    echo "<table>";
	while ($row = $result->fetch_assoc()) {
        $like_id = $row["like_id"];
        if ($result2 = $mysqli->query("SELECT * FROM likes WHERE like_id =".$like_id." and is_like_data=0")) {
   			if ($result2->num_rows < 1)  continue;
            if ($i++ > $stop_at) exit;
            $like= $result2->fetch_object();
            //echo $i . ") " . $like->like_name . "<br>";
            $like_data = json_decode(file_get_contents("https://graph.facebook.com/" . $like->like_id),true);
            $like_id = $like->like_id;
            if ($like_data){
            //check and save in mongo +
                $add = array();
                save_like($like_data,$add,$like_id,true);
                $mysqli->query("UPDATE likes SET is_like_data=1 WHERE like_id=$like_id");
				
				$like_data_id = $like_data->id;
				$like_data_name = $like_data["name"];
				$like_data_link = $like_data["link"];
				$like_data_likes = $like_data["likes"];
				$like_data_cat = $like_data["category"];
				$like_data_talking = $like_data["talking_about_count"];
				
				if ((!isset($like_data_cat)) or ($like_data_cat=="")) $like_data_cat = "No Category";
				if ((!isset($like_data_talking)) or ($like_data_talking=="")) $like_data_talking = 0;
				if ((!isset($like_data_likes)) or ($like_data_likes=="")) $like_data_likes = 0;
				
				echo "<tr><td>";
				echo $like_data_id;
				echo "</td><td>";
				echo $like_data_name;
				echo "</td><td>";
				echo $like_data_link;
				echo "</td><td>";
				echo $like_data_likes;
				echo "</td><td>";
				echo $like_data_cat;
				echo "</td><td>";
				echo $like_data_talking;
				echo "</td></tr>";
				
				$mysqli->query("INSERT INTO excited_reports (like_id, like_name, like_url, like_type, num_likes, talk_about) VALUES (" . $like_id .", '" . $like_data_name . "', '" . $like_data_link . "', '" . $like_data_cat . "', " . $like_data_likes . ", " . $like_data_talking . ")");
            }	//echo "INSERT INTO excited_reports (like_id, like_name, like_url, like_type, num_likes, talk_about) VALUES (" . $like_id .", '" . $like_data_name . "', '" . $like_data_link . "', '" . $like_data_cat . "', " . $like_data_likes . ", " . $like_data_talking . ")";
            //var_dump($like_data);
           // echo "<br><br>finish like, now resting 2 secs...<br><br>";
ob_flush(); flush();

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
</table>