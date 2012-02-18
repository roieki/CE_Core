<?php
//עברית
// include_once("config.php");
// include_once("functions/load_url.php");
// include_once("functions/get_likes.php");
// include_once("functions/keyword_density_functions.php");
// include_once("functions.php");

//$url = $_GET['url'];
$url = "http://www.fxp.co.il/showthread.php?t=9596522";
//echo $url . "<br>";
echo get_url_contents($url);
exit;

$opts = array(
    'http' => array(
        'user_agent' => 'PHP libxml agent',
    )
);

$context = stream_context_create($opts);
libxml_set_streams_context($context);

$dom = new DOMDocument;
libxml_use_internal_errors(true);
@$dom->loadHTMLFile("$url"); // insert url of choice
libxml_use_internal_errors(false);
$xpath = new DOMXPath($dom);
//var_dump($xpath);
// $aTag = $xpath->query("//div[@class='navbit']/a"); // search for all anchor tags that provide an href attribute


 $anchors = $xpath->query("//li[@class='navbit']/a");
 foreach($anchors as $a)
 { 
     print $a->nodeValue." - ".$a->getAttribute("href")."<br/>";
 }


function get_url_contents($url, $timeout = 10, $userAgent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.215 Safari/534.10'){
    $rawhtml = curl_init();  //create our handler
    curl_setopt ($rawhtml, CURLOPT_URL,$url);  //set the url
    curl_setopt ($rawhtml, CURLOPT_RETURNTRANSFER, 1);  //return result as string rather than direct output
    curl_setopt ($rawhtml, CURLOPT_CONNECTTIMEOUT, $timeout);  //set the timeout
    curl_setopt ($rawhtml, CURLOPT_USERAGENT, $userAgent);  //set our 'user agent'
    $output = curl_exec($rawhtml);  //execute the curl call
    curl_close($rawhtml);  //close our connection
    if (!$output) {
        return -1;  //if nothing was obtained, return '-1'
    }
    return $output;
}

?>