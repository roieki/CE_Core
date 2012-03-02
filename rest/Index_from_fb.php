<?php

$fb_results = search_fb("radiohead", "page");

foreach ($fb_results as $entry)
{
    echo $entry->name . " ";
    echo $entry->category . " ";
    echo $entry->id . "<br>";

}




function search_fb($keyword, $fb_type, $limit=4999, $offset=0)
{
$url = "https://graph.facebook.com/search?q=" . $keyword . "&type=" . $fb_type. "&limit=" . $limit . "&offest=" . $offset;

$result = json_decode(file_get_contents($url));
$entries = $result->data;
	return $entries ;

}
