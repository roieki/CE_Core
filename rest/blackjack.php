<?php


include ('loader.php');


$file = json_decode(file_get_contents("blackjack.json"));

$file = $file->data;

foreach ($file as $line)
{

echo $line->id . " || ";
echo $line->name. " || ";
echo $line->category; 	
echo "<br><br>";

				$like_data_id = $line->id;
				$like_data_name = $line->name;
				$like_data_cat = $line->category;

				$like_data_link = "N/A";
				$like_data_likes = -1;
				$like_data_talking = -1;

				
echo "INSERT IGNORE excited_reports (like_id, like_name, like_url, like_type, num_likes, talk_about) VALUES ($like_data_id, '$like_data_name', '$like_data_link', '$like_data_cat', $like_data_likes, $like_data_talking)";
echo "<br>";				
$mysqli->query("INSERT IGNORE excited_reports (like_id, like_name, like_url, like_type, num_likes, talk_about) VALUES ($like_data_id, '$like_data_name', '$like_data_link', '$like_data_cat', $like_data_likes, $like_data_talking)");

}