<?php
include("../loader.php");

$check_like_id = $_GET["like_id"];


if ((isset($check_like_id )) and ($check_like_id  != ""))
{

    $db = $mongo->combined;
	$likes = $db->likes;

	$like = $likes->findOne(array('id' => $check_like_id));
        echo "<h1>All data for page id " . $check_like_id . "</h1>";
        var_dump($like);exit;
        echo "<table>";
	  foreach ($like as $key=>$value) {
          $key = str_replace("_", " ", $key);
          $key = ucfirst($key);
          $value = ucfirst($value);

    	  echo "<tr><td bgcolor=\"#FF0000\"><b>$key</b></td><td>$value</td></tr>";
		//echo $obj->country;
        }
        echo "</table>";


}
