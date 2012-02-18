<?php

include('db_conf.php');
global $mysqli;

//$mysqli->query("TRUNCATE TABLE likes");
$mysqli->query("TRUNCATE TABLE likes_forums_relations");
$mysqli->query("TRUNCATE TABLE users");
$mysqli->query("TRUNCATE TABLE users_forums_relations");
$mysqli->query("TRUNCATE TABLE users_likes_relations");

//$mysqli->query("TRUNCATE TABLE likes_tags_relations");
//$mysqli->query("TRUNCATE TABLE queries_data");
//$mysqli->query("TRUNCATE TABLE query_tag_relation");
//$mysqli->query("TRUNCATE TABLE tags");
//$mysqli->query("TRUNCATE TABLE tags_relation");






echo "db is clean";


?>