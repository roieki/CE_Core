<?php


include ('loader.php');
echo '<a href="dauup_report.php">Home</a><br>';

$cat = $_GET["cat"];
$check_like_id = $_GET["like_id"];


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

    	  echo "<tr><td bgcolor=\"#FF0000\"><b>$key</b></td><td>$value</td></tr>";
		//echo $obj->country;
        }
        echo "</table>";


    exit;
}



if ((!isset($cat)) or ($cat == ""))
{
$results = $mysqli->query("SELECT `like_type` ,COUNT(`like_type`) FROM `excited_reports` GROUP BY `like_type` ORDER BY COUNT(`like_type`) DESC");
echo "<table>";
?>
<tr bgcolor="#FF0000"><td>page type</td><td>#Pages</td></tr>
<?php
	while ($row = $results->fetch_assoc()) {

	$type = $row["like_type"];
	echo "<tr><td><a href=\"dauup_report.php?cat=$type\">$type</a></td>";
	echo "<td>" . $row["COUNT(`like_type`)"] . "</td></tr>";
}
echo "</table>";
}
else
{

$results = $mysqli->query("SELECT * FROM `excited_reports` WHERE like_type='$cat' ORDER BY num_likes DESC");
echo "<table>";
?>
<tr bgcolor="#FF0000">
<td>Page id</td>
<td>Page name</td>
<td>Page url</td>
<td>Page type</td>
<td>#Likes</td>
<td>#Talking about this</td>
</tr>
<?php
	while ($row = $results->fetch_assoc()) {
		
		$url = $row["like_url"];
        $page_like_id = $row["like_id"];
    echo "<td>" . "<a href=\"dauup_report.php?like_id=$page_like_id\">$page_like_id</a>"  . "</td>";
	echo "<td>" . $row["like_name"]  . "</td>";
	echo "<td>" . "<a href=\"$url\" target=_blank>$url</a>"  . "</td>";
	echo "<td>" . $row["like_type"]  . "</td>";
	echo "<td>" . $row["num_likes"]  . "</td>";	
	echo "<td>" . $row["talk_about"] . "</td></tr>";
}

echo "</table>";
}



