<?php


// get keyword, get category.

include ('loader.php');



$stop_at = $_GET["limit"];
$tag = $_GET["tag"];
$check_like_id =$_GET["likeid"];

$tag = cleanFreeText($tag);
$tag = get_tag($tag);
ob_start();



if ((isset($check_like_id )) and ($check_like_id  != ""))
{

    $db = $mongo->combined;
	$likes = $db->likes;

	$like = $likes->findOne(array('id' => $check_like_id));
        echo "<h1>All data for page id " . $check_like_id . "</h1>";
        echo "<table>";
	  foreach ($like as $key=>$value) {
          $key = str_replace("_", " ", $key);
          $key = ucfirst($key);
          $value = ucfirst($value);

          switch ($key){

              case "Picture":
                    $value = '<img src="'.$value.'">';
                  break;
              case "Link":
                  $value = '<a href="'.$value.'" target="_blank">' . $value . '</a>';
                  break;
              case "Likes":
                  $value = '<b>' . $value . '</b>';
                  break;
          }

    	  echo "<tr><td bgcolor=\"#FF0000\"><b>$key</b></td><td>$value</td></tr>";
		//echo $obj->country;
        }
        echo "</table>";


    exit;
}


if (!$tag) {
	echo "tag doesnt exist!";
	exit;
}

$tag_id = $tag->id;

if ($result = $mysqli->query("SELECT * FROM likes_tags_relations WHERE tag_id ='".$tag_id."'")) {
 //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
    echo "found: " . $result->num_rows . " likes<br>";
    echo "<table>";
	while ($row = $result->fetch_assoc()) {
        $like_id = $row["like_id"];
        $approved_tag_like = $row["approved"];

        if ($result2 = $mysqli->query("SELECT * FROM likes WHERE like_id =".$like_id)) {
   			if ($result2->num_rows < 1)  continue;
            $like= $result2->fetch_object();

                //var_dump($like);
                echo "<tr><td>";
                if ($approved_tag_like==3)echo "approved";
                echo "</td><td>";            
				//echo $like->like_id;
				echo "</td><td>";
				echo $like->like_name;
				echo "</td><td>";
				//echo $like->approved;
				echo "</td><td>";
				if($like->is_like_data) echo '<a href="get_all_tag_likes.php?likeid='. $like->like_id . '">see more info on like</a>';
				echo "</td></tr>";

		    }	//echo "INSERT INTO excited_reports (like_id, like_name, like_url, like_type, num_likes, talk_about) VALUES (" . $like_id .", '" . $like_data_name . "', '" . $like_data_link . "', '" . $like_data_cat . "', " . $like_data_likes . ", " . $like_data_talking . ")";
            //var_dump($like_data);
           // echo "<br><br>finish like, now resting 2 secs...<br><br>";
ob_flush(); flush();


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