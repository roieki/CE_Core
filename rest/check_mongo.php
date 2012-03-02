<?php

include("../loader.php");

$like_id_user = $_GET["like_id"];

//if ((!isset($like_id)) or ($like_id=="")) exit;


  if ($result2 = $mysqli->query("SELECT * FROM likes WHERE is_like_data=1 limit 50")) {
   			if ($result2->num_rows < 1)  continue;

			while ($row = $result2->fetch_assoc()) {
             $like_id = $row["like_id"];

             $like_name = $row["like_name"];
echo "<a href=check_mongo.php?like_id=$like_id>$like_name</a><br>";
           }


	$db = $mongo->combined;
	$likes = $db->likes;
	$like_count = $likes->count();
	
	echo "found $like_count likes data from fb in mongo<br>";
	
	//$like = $likes->find();
	
	
	//$like = $likes->findOne("'id':'$like_id'");
	$like = $likes->findOne(array('id' => $like_id_user));
	//$like = $likes->findOne();

	
	//var_dump($like);
	
	
	  foreach ($like as $key=>$value) {
    	  echo "$key = $value<br>";
		//echo $obj->country;
  }
	

}	
	
?>
