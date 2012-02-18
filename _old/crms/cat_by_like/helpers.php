<?php

function getBingData($query,$limit=10,$offset=0){
	//echo "in bing";
	$appid = 'D969D39C98E0E4D8DD5D87EBCF144C9604DACA46';
	$url = 'http://api.search.live.net/json.aspx?Appid='
	.$appid.'&query='.urlencode($query).
	'&sources=web&Web.Count=' . $limit. '&Web.Offset=' . $offset;
	$result = json_decode(file_get_contents($url));
	$entries = $result->SearchResponse->Web->Results;
	return $entries ;
}

function wikidefinition($s) {
    $url = "http://en.wikipedia.org/w/api.php?action=opensearch&search=".urlencode($s)."&format=xml&limit=1";
    $page = rcurl($url);
    $xml = simplexml_load_string($page);
	//var_dump($xml);
	$wikiUrlTitle = (string)$xml->Section->Item->Text;
	$wikiUrlTitle = preg_replace('/\s/', '%20', $wikiUrlTitle);
	 $url = 'http://en.wikipedia.org/w/api.php'
             . '?format=json&action=query&prop=langlinks&lllimit=500&redirects=1'
             . '&titles='.htmlentities($wikiUrlTitle);
			 
			$res = rcurl($url);
    if((string)$xml->Section->Item->Description) {
        return array((string)$xml->Section->Item->Text, (string)$xml->Section->Item->Description, (string)$xml->Section->Item->Url);
    } else {
        return "";
    }
}

function wikilang($s) {
    $url = "http://en.wikipedia.org/w/api.php?action=opensearch&search=".urlencode($s)."&format=xml&limit=1";
    $page = rcurl($url);
    $xml = simplexml_load_string($page);
	//var_dump($xml);
	$wikiUrlTitle = (string)$xml->Section->Item->Text;
	$wikiUrlTitle = preg_replace('/\s/', '%20', $wikiUrlTitle);
	$url = 'http://en.wikipedia.org/w/api.php'
             . '?format=json&action=query&prop=langlinks&lllimit=500&redirects=1'
             . '&titles='.htmlentities($wikiUrlTitle);
			 
	$res = rcurl($url);
	$result = json_decode($res);
	$keys = get_object_vars($result->query->pages);
	if (is_array($keys)){
		$object = array_pop($keys);	
		if (!is_array($object->langlinks)) return "N/A";
		foreach ($object->langlinks as $lang_entry){
			if ($lang_entry->lang == 'he'){
				return $lang_entry->{'*'};
			}
		}
	}
	//if not translation found
	return "N/A";
}

function getPostCategories($url){
	
}

function rcurl($url){
	$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_POST, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_NOBODY, FALSE);
    curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
    curl_setopt($ch, CURLOPT_REFERER, "");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; he; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8");
    $page = curl_exec($ch);
	return $page;
}
?>
