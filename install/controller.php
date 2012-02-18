<?php

include_once('loader.php');

$action = $_POST['action'];
$data = $_POST['data'];
$uid = $_POST['uid'];

switch ($action){
	case 'set_user':
		$user_object = $_POST['user'];
		$fbuid = $_POST['fbuid'];
		set_user($user_object,$fbuid);		
	break;
	case 'process':
		$additional = $_POST['additional'];
		$like_object = $_POST['like'];
		$fbuid = $_POST['fbuid'];
		process($like_object,$additional,$fbuid);
	break;
	case 'save_forum_relation':
		$like_id = $_POST['like_id'];
		$forum_id = $_POST['forum_id'];
		$category_id = $_POST['category_id'];
		save_forum_relation($like_id,$forum_id);
	break;
	case 'getlike':
		$like_id = $_POST['likeid'];
		$like_object = $_POST['like'];
		$fbuid = $_POST['fbuid'];
		get_like($like_id,$fbuid,$like_object);
	break;
	case 'save_existing_like':
		$like_id = $_POST['likeid'];
		$additional = $_POST['additional'];
		$fbuid = $_POST['fbuid'];
		save_existing_like($like_id,$additional,$fbuid);
	break;
	case 'get_fxp_categories':
		$links = $_POST['links'];
		get_fxp_categories($links);
	break;
	default:
	break;
	
}
function get_fxp_categories($links){
	foreach ($links as $link){
		if (preg_match ("/t-([0-9]+)\.html/" , $link['url'], $m)){
			$ids[] = $m[1];	
			
		}
	}
	$url_ids = join(",",$ids);
	$furl = "http://fxp7.spdsites.com/Get_forum_id.php?ids=" .$url_ids;
	$res = file_get_contents($furl);
	$result = explode("<br>",$res);
	$i = 0;
	foreach ($result as $entry){
		$links[$i]['cid'] = $entry;	
		$i++;
	}
	//echo json_encode($links);

}

function save_forum_relation($like_id,$forum_id){
	set_object_relation($like_id,'like',$forum_id,'forum');
	save_forum_relation_db($like_id,$forum_id);
}

function set_user($user_object,$fbuid){
	$res = save_user($fbuid,$user_object);
	echo json_encode($res);	
}

function process($like_object,$additional,$fbuid,$domain=NULL){
	global $conf;	
	$domain = 'fxp.co.il -site:fxp.co.il/archive';
	//$domain = 'fxp.co.il/archive';
	//$domain = NULL;
	save_like($like_object,$additional,$fbuid);
	$translation = get_translation($like_object['id'],$like_object['name']);
	$query = '"' . $translation . '"';
	if ($translation == ''){
		$query = '"' . $like_object['name'] . '"';
	}
	if ($domain != NULL){
		$query .= " site:" . $domain;
	}

	//get bing data
	$results = getBingData($query);
	//Add category data
	if (is_array($results)){
        foreach($results as $entry){
            if (is_null($entry)) {
                continue;
            }
//			var_dump($entry);
            $item = get_object_vars($entry);
            $item['url'] = $entry->Url;
			$res[] = $item;
		}	
	} 
	else {
		$res[] = 'empty';
	}
	
	save_bing($like_object['id'],$res,$domain);
	return($res);
	
	
}

function get_like($likeid,$fbuid,$like_object){
	global $mongo;
	
	$db = $mongo->combined;
	$likes = $db->likes;
	//echo $likeid;
	$data = $likes->findOne(array('id' => $likeid));
	
	$res->id = $likeid;
	if (!empty($data)){
		$res->data = $data;
		$res->found = true;
		//save_like($like_object,$data,$fbuid);	
	}
	else {
		$res->found = false;
	}
	//echo json_encode($res);
	return $res;
	
}

function get_like_by_object_id($likeid){
	global $mongo;
	
	$db = $mongo->combined;
	$likes = $db->likes;
	
	$data = $likes->findOne(array('id' => $likeid));
	
	$res->id = $likeid;
	if (!empty($data)){
		$res->data = $data;
		$res->found = true;
	}
	else {
		$res->found = false;
	}
	return $res;
}
function save_existing_like($like_id,$additional,$fbuid){
	
}

function get_translation($like_id,$term){
	$translation = wikilang($term);	
	if ($translation != 'N/A'){
		save_translation($like_id,$translation);	
	}
	return $translated;
}

?>