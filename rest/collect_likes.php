

<form name="input" action="collect_likes.php" method="get">
keyword: <input type="text" name="keyword" />
tag: <input type="text" name="tag" />
number of result: <input type="text" name="number_of_result" />
query: <input type="text" name="query" />
    <input type="submit" value="Submit" />
</form>
<br>
<input type="button" value="Search again" onClick="window.location.href=window.location.href">

<?php

// get keyword, get category.

include_once('loader.php');

$keyword = $_GET["keyword"];
$tag = $_GET["tag"];
$parent_tag = $_GET["parent_tag"]; // optional
$search_limit = $_GET["search_limit"];  // optional
$search_restart =  $_GET["start_from"];  // optional
$is_like_data =  $_GET["is_like_data"];  // optional
$number_of_result =  $_GET["number_of_result"];  // optional
$query =  $_GET["query"];  // optional

ob_start();


if (!isset($search_limit)) $search_limit=50;
if (!isset($is_like_data)) $is_like_data = 0 ;

if (!isset($number_of_result)) $number_of_result = 50;
else $number_of_result = round($number_of_result);

if (isset($query)) $keyword = 'value';

if (!isset($keyword)) {echo "No Keyword set!"; exit;}
if (!isset($tag)) {echo "No Tag set!"; exit;}

if ((!isset($query)) or ($query=="")) $query = 'site:facebook.com/pages/ intitle:' .$keyword;



$tag_id = set_tag($tag);


if (isset($parent_tag)){
    $parent_tag_id = set_tag($parent_tag);
    set_tags_relation ($tag_id, $parent_tag_id);
}
$num_of_rounds = round($number_of_result/50);




?>
<h1>indexing for tag: <?php echo $tag;?> using keyword: <?php echo $keyword;?></h1>
<br>

    using the query: <?php echo $query;?> <br>
    getting <?php echo $number_of_result;?> number of result within <?php echo $num_of_rounds;?> rounds <br>
    with search limit of <?php echo $search_limit;?><br>
    search restart = <?php echo $search_restart;?><br>
    is like data = <?php echo $is_like_data;?><br>
<br>

tag_id = <?php echo $tag_id;?><br>
parent tag id = <?php echo $parent_tag;?><br>


<?php

ob_flush(); flush();

$new_likes = 0;

for ($i=0;$i<$num_of_rounds;$i++){

    if (isset($search_restart)) {$search_start_from = 0; unset($search_restart);}
    else $search_start_from = get_search_start_from($query);

    ?>
    <h2>round number <?php echo $i;?></h2>
    bring results from <?php echo $search_start_from;?> to <?php echo $search_limit+$search_start_from;?>,
    <br>
    <?php 
    ob_flush(); flush();
    $bingData = getBingData($query, $search_limit, $search_start_from);

    if (!$bingData) {echo "<b>Blocked by Bing on: </b>" . $i * 50; exit;}
    $query_id = set_new_query ($query, $search_limit, $search_start_from);

    set_query_tag_relation($query_id , $tag_id);
    save_bing_data($bingData,$query,$query_id,$tag_id);
    ?>
    data saved in MongoDB <br>
    $query_id = <?php echo $query_id;?><br>
    <br>
    <hr>
    <?php
    ob_flush(); flush();
    foreach ($bingData as $entry){
        $like_data = get_Like_data_from_Bing($entry); // done
        $like_id = $like_data->like_id;
        // echo $like_id;
        if (set_new_like($like_id, $like_data->page_name, $is_like_data)){
            save_parsed_bing($like_id,$like_data);
            $new_likes++;
            ?>
                <b>found and saved new like </b><br>
             <?php
ob_flush(); flush();
        // update in querydata - increase like_insert + 1
        }

        ?>
            like_id = <?php echo $like_id;?> ||  like name = <?php echo $like_data->page_name;?><br>
         <?php

        set_like_tag_relation ($like_id, $tag_id); // done
    }
    echo "found $new_likes likes in this seeesion<br>";
    echo "sleeping for 3 secs now...<br>";
    sleep(3);
    ob_flush(); flush();
}
echo "found total of $new_likes new likes in this search";
// Finish main code
// =====================

// Functions


function set_new_like($like_id, $page_name, $is_like_data)
    {
		
		
        // before all, check if like already exist in db
		$like = get_like ($like_id);
		if (!$like)
		{
        if ($is_like_data) $like_data = get_like_data_fb($like_id);
        $like_name = $page_name;
        insert_new_like_mysql($like_id, $like_name, $is_like_data); // done
		}
		else return $like->like_id; 
    }

function get_like ($like_id)
{
global $mysqli;
	if ($result = $mysqli->query("select * from likes where like_id=". $like_id)) {
   		if ($result->num_rows < 1)  return false;
      		else return $result->fetch_object();

              	}
               	else return false;

}	
	
function get_search_start_from($query)
    {
   	  global $mysqli;

        // get last query (by date)
        if ($result = $mysqli->query("select * from queries_data where query='".$query . "' ORDER BY date DESC LIMIT 1")) {

        // fetch search from, limit
        while ($row = $result->fetch_assoc()) {
             $search_from = $row["start_from"];

             $limit = $row["limit_num"];

           }


         return ($search_from + $limit);
        }
        else return 0;

    }

function set_new_query($query, $search_start_from, $search_limit)
        {

        	global $mysqli;
//echo "INSERT INTO queries_data (query, start_from, limit_num, date) VALUES ('" . $query . "' , $search_limit, $search_start_from, 0)";
//if ($result = $mysqli->query("INSERT INTO likes_tags_relations (like_id, tag_id) VALUES (". $like_id . "," . $tag_id . ")")) {

		// echo "INSERT INTO 'queries_data' VALUES (NULL, '$query', '', NOW(), '$search_limit', '$search_start_from')";
               	  if ($result = $mysqli->query("INSERT INTO queries_data (query, start_from, limit_num, date) VALUES ('" . $query . "' , $search_limit, $search_start_from, NOW())")) {
                   		$query_id = $mysqli->insert_id;
                     	   	// insert success
                        	  // 	echo "gii";
                                return $query_id;
                           	  }
                           	  //if insert failed
                           	  else return false;
        }



function get_like_tag_relation($like_id, $tag_id)
    {

global $mysqli;
	if ($result = $mysqli->query("select * from likes_tags_relations where like_id=".$like_id . " and tag_id=" . $tag_id)) {
      		if ($result->num_rows < 1) return false;
             		else return true;

                          	}
                            	else return false;
    }

function set_like_tag_relation ($like_id, $tag_id)
    {
        
global $mysqli;
	$tags_relation = get_like_tag_relation($like_id, $tag_id);
      	//object relation doesn't exists
       	if (!$tags_relation){
       	   if ($result = $mysqli->query("INSERT INTO likes_tags_relations (like_id, tag_id) VALUES (". $like_id . "," . $tag_id . ")")) {
       	   	//insert success
       	   	return true;
       	  }
       	  //if insert failed
       	  else return false;
       	}
       	//if object relation exists
       	else return true;


    }

function get_tags_relation ($child_tag_id, $parent_tag_id)
         {
global $mysqli;
	if ($result = $mysqli->query("select * from tags_relation where child_tag_id=".intval($child_tag_id) . " and parent_tag_id=" . intval($parent_tag_id))) {
   		if ($result->num_rows < 1) return false;
      		else return true;

              	}
               	else return false;
        }

function set_tags_relations ($child_tag_id, $parent_tag_id)
{

global $mysqli;    
	$tags_relation = get_tags_relation($child_tag_id, $parent_tag_id);
   	//object relation doesn't exists 
   	if (!$tags_relation){ 
   	   if ($result = $mysqli->query("INSERT INTO tags_relation (child_tag_id, parent_tag_id) VALUES (".intval($child_tag_id).",".intval($parent_tag_id). ")")) {

   	   	//insert success 
   	   	return true;
   	  }	 
   	  //if insert failed 
   	  else return false; 
   	} 
   	//if object relation exists 
   	else return true;
    
    
}

function insert_new_like_mysql($like_id, $page_name, $is_like_Data)
    {
    global $mysqli;

        // insert to ;likes' new like, with is_like_data = 0
        // return like_id

    $mysqli->query("INSERT IGNORE INTO likes (like_id,like_name, approved, is_like_data) VALUES (".intval($like_id).",'". $page_name."', 0, $is_like_Data)");

  }

function get_internal_tag_name($tag_id)
{

    return $tag_name;
}


function get_external_tag_name($tag_id, $client_name)
{

    return $tag_name;
}


function get_external_id_by_internal_id($internal_id, $client_name)
{

    // select
    return $external_id;
}


function get_internal_id_by_external_id($external_id, $client_name)
{

    // select
    return $internal_id;
}

// ===================

function get_like_data_fb($like_id){
	global $mongo;
global $mysqli;


    // search like on mysql
    // if is_like_data = 1 ,  return from mongo
    // else get like from fb to mongo an return



$db = $mongo->combined;
	$likes = $db->likes;
$like = $likes->findOne(array('like.id' => $like_id));

if (!empty($like)) {
	return $like;
}
else {
	//get like
	$lasttimefeteched = filegetcontents("last_time_fetched_facebook");

if (NOW()-lasttimefetched < 2000) {
	sleep(2);
}

$like = file_get_contents("graph.facebook.com/" . $like_id);
file_put_contents("last_time_fetched_facebook",NOW());

$likes->save($like);
//save in mysqli with ID and name
return $like;
}
}


function getBingData_smart($query,$tag){
	global $mongo;
    global $mysqli;

    $db = $mongo->combined;
	$queries = $db->queries;
    $query = $queries->findOne(array('query' => $query));

    if (!empty($query)) {
        return $query["returned_data"];
    }
    else {
        $query_entry["returned_data"] = getBing($query);
    //	insert to mysqli
        $query_entry["timestamp"] = timestamp();
        $query_entry["mysqlid"] = $mysqli;
        $query_entry["tag"] = $tag;
    $queries>save($query_entry);
}
}

// DONE

function get_Like_data_from_Bing($entry)
{
    		$url= $entry->Url;
          		preg_match("/(http\:\/\/www.facebook.com\/pages\/([A-Za-z\-0-9]+)\/([0-9]+)).*/",$url,$urlparts);

                  		$like_data->clean_url = $urlparts[1];
                      		$like_data->page_url_name = $urlparts[2];
                          		$like_data->like_id = $urlparts[3];
                              		$page_name = $entry->Title;
                                  $page_name = str_replace(" | Facebook", "", $page_name);
                                  $page_name = str_replace(" - Info", "", $page_name);
                                  $page_name = str_replace(" - Photos", "", $page_name);
                                  $page_name = str_replace(" - Wall", "", $page_name);
                                 $like_data->page_name = $page_name;

    return $like_data;
}


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

function cleanFreeText($text){
	$clean_text = strtolower($text);
   	$clean_text = trim($clean_text);
	return $clean_text;
    }



function set_tag($tag) {
	global $mysqli;

   	$tag_exist = get_tag($tag);
   if (!$tag_exist){
   	  if ($result = $mysqli->query("INSERT INTO tags (value) VALUES ('$tag')")) {

   		$tag_id = $mysqli->insert_id;
      	   //	insert success
       	   	return $tag_id;
       	  }
       	  //if insert failed
       	  else return false;
       	}
       	//if user exists
       	else return $tag_exist->id;
       }


function get_tag($tag)
    {

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


function set_query_tag_relation($query_id , $tag_id)
    {
	global $mysqli;

     if (!get_query_tag_relation($query_id , $tag_id))
         {

               	  if ($result = $mysqli->query("INSERT INTO query_tag_relation (query_id, tag_id) VALUES ($query_id , $tag_id)")) {

                     return true;
                     }
                  else return false;




         }
         else return true;
    }


function get_query_tag_relation($query_id , $tag_id)    {

	global $mysqli;

      	if ($result = $mysqli->query("SELECT * FROM query_tag_relation WHERE query_id=$query_id AND tag_id = $tag_id")) {
       	  //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
       	  if ($result->num_rows < 1) return false;
       	  else return true;


       	}
       	else return false;


        }

?>
