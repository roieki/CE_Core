<?php

include('db_conf.php'); 

//include_once('functions.php');
$conf = $GLOBALS['conf'];

$mongo = new Mongo($conf['mongodb_address']);
mysqli_set_charset($mysqli, "utf8");


function save_like($like_object,$additional,$fbuid=NULL,$short=false){
	global $mongo;	
	$db = $mongo->combined;
	$likes = $db->likes;
	$merged = array_merge($like_object,$additional);
	$likes->save($merged);
	$likes->ensureIndex(array('id'=>1), array("unique" => true));
	$like_object_id = set_object('like',$like_object['name'],$like_object['id']);
	if (!$short){
        $user_id = get_object_by_external_id($fbuid);
        set_object_relation($user_id, 'user', $like_object_id, 'like');
    }

}

function save_user($fbuid,$user_object){
	global $mongo;	
	
	$db = $mongo->combined;
	$users = $db->users;
	$users->save($user_object);
	$users->ensureIndex(array('id'=>1), array("unique" => true));
	$user_exists = check_user_exist($fbuid);
	if (!$user_exists){
		$user_object_id = set_object('user',$fbuid,$fbuid);
		$res->status = 'new';
		return $res; 	
	}
	else{
		return get_user_data($fbuid);
	} 
	
}

function get_user_data($fbuid){
	global $mongo;
	global $mysqli;
	
	$db = $mongo->combined;
	$likes = $db->likes;
	$user_id = get_object_by_external_id($fbuid);
	$result = $mysqli->run_select_query("select * from objects_relations where object_id1=".intval($user_id));
	while ($row = $result->fetch_assoc()){
		$object_id = $row['object_id2'];
		$external_id = get_external_id_by_object_id($object_id);	
		$ids[] = (string)$external_id;
	}
	$res->status = "exists";
	$data = $likes->find(array('id' => array('$in'=>$ids)));
	foreach ($data as $obj) {
	    $res->data[] = $obj;
	}
	
	return $res;

}



function save_translation($like_id,$translation){
	global $mongo;	
	$db = $mongo->combined;
	$likes = $db->likes;
	$like = $likes->findOne(array('id' => $like_id));
	
	$like['translation'] = $translation;
	$likes->save($like);
	$internal_like_id = get_object_by_external_id($like_id);
	$translation_object_id = set_object('translation',$translation);
	set_object_relation($internal_like_id, 'like', $translation_object_id, 'translation');
}

function save_bing($like_id,$bing_data,$domain=NULL){
	global $mongo;	
	$db = $mongo->combined;
	$likes = $db->likes;
	$like = $likes->findOne(array('id' => $like_id));
	$like['bing_data']['data'] = $bing_data;
	if ($domain != NULL){
		$like['bing_data']['domain'] = $domain;
	}
	$likes->save($like);
}

function save_parsed_search_data($like_id,$search_query_data, $search_source){

    global $mongo;
	$db = $mongo->combined;
	$likes = $db->likes;
	$like = $likes->findOne(array('id' => $like_id));
	$like["$search_source"]['parsed_data'] = $search_query_data;

	$likes->save($like);
}


//Saves bing data regardless of any like.
function save_search_data($search_data,$query,$query_id,$tag_id=NULL, $search_source_id){
    global $mongo;
    $db = $mongo->combined;
    $bingDataStore = $db->bingDataStore;
    $search_data['query'] = $query;
    $search_data['query_id'] = $query_id;
    $search_data['search_source_id'] = $search_source_id;
    if ($tag_id != NULL){
        $search_data['tag_id'] = $tag_id;
    }
    //var_dump($bing_data); 
    //var_dump($bingDataStore);
    $bingDataStore->save($search_data);

}

function save_categories($like_id,$categories){
	global $mongo;	
	$db = $mongo->combined;
	$likes = $db->likes;
	$like = $likes->findOne(array('id' => (string)$like_id));
    $like['categories'] = $categories;
    $likes->save($like);

}

function save_forum_relation_db($like_id,$forum_id){
	global $mongo;
	global $mysqli;
	$type_id = get_type_id($type);
	
	$result = $mysqli->run_select_query('select * from likes_forums_relations where like_id='.intval($like_id).' and forum_id='.intval($forum_id).' and category_id='.intval($category_id).' and approved=\'true\'');
	if ($result->num_rows > 0) return false;
	$object= $result->fetch_object();
	$result->close();
	 
	if (!$object){
		//echo "INSERT INTO likes_forums_relations (like_id,forum_id,category_id,approved) 
	  	//VALUES (".intval($like_id).",".intval($forum_id).",'".intval($category_id)."','true')";
		
	  if ($result = $mysqli->query("INSERT INTO likes_forums_relations (like_id,forum_id,category_id,approved) 
	  	VALUES (".intval($like_id).",".intval($forum_id).",'".intval($category_id)."','true')")) {
		return; 
		$object_id = $mysqli->insert_id;
		//insert success
	   	return $object_id;
	  }
	  //if insert failed
	  else return false;
	}
	//if user exists
	else return $object->id;
	
}

function set_object($type, $content, $id_in_source=0) {
	global $mysqli;
	$type_id = get_type_id($type);
	$object = get_object_by_value_type($content, get_type_from_id ($type_id));
	if (!$object){
	  if ($result = $mysqli->query("INSERT INTO object (id_in_source,type_id,content) VALUES (".intval($id_in_source).",".intval($type_id).",'". $content."')")) {
		$object_id = $mysqli->insert_id;
		//insert success 
	   	return $object_id;
	  }
	  //if insert failed
	  else return false;
	}
	//if object exists
	else return $object->id;
}

function get_object_by_value_type($value, $type){
	global $mysqli;

	$type_id = get_type_id($type);
	if ($result = $mysqli->run_select_query("SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'")) {
	  //echo "SELECT * FROM object WHERE type_id=$type_id AND content='".$value."'";
	  if ($result->num_rows < 1) return false;
	  $object= $result->fetch_object();
	  $result->close();
	  return $object;
	}
	else return false;
}

function get_object_by_external_id($id_in_source){
	global $mysqli;	
	if ($result = $mysqli->query("SELECT * FROM object WHERE id_in_source=".intval($id_in_source))) {
	  if ($result->num_rows < 1) return false;
	  $object= $result->fetch_object();
	  $result->close();
	  return $object->id;
	}
}

function get_external_id_by_object_id($objectid){
	global $mysqli;	
	//echo "SELECT id_in_source FROM object WHERE id=".intval($objectid)."\n";
	if ($result = $mysqli->run_select_query("SELECT id_in_source FROM object WHERE id=".intval($objectid))) {
	  if ($result->num_rows < 1) return false;
	  $object= $result->fetch_object();
	  $result->close();
	  return $object->id_in_source;
	}
}

function get_type_from_id($type_id){
	global $object_types;
	$type = array_search($type_id, $object_types); 
	return $type;
}

function get_type_id($type){  
	global $object_types;
	$type_id = $object_types[$type];
	return $type_id;
}


//====RELATIONS

function set_object_relation($object_id_1,$object_type_1,$object_id_2,$object_type_2){
 global $mysqli;
    $relation = get_object_relation_by_object_id($object_id_1,$object_id_2);
    
    if (!$relation){
      if ($result = $mysqli->query("INSERT INTO objects_relations (object_id1,type_id1,object_id2,type_id2) VALUES (".intval($object_id_1).",".intval(get_type_id($object_type_1)).",".intval($object_id_2).",".intval(get_type_id($object_type_2)).")")) {
        $relation = get_object_relation_by_relation_id($mysqli->insert_id);
        //insert success
        return $relation;
      }
      //if insert failed
      else return false;
    }
    
    else {
    	return $relation;
	}
		
}

function check_user_exist($fbuid){
	global $mysqli;
	return get_object_by_external_id($id_in_source);
}

function get_object_relation_by_relation_id($relation_id){
	global $mysqli;
	if ($result = $mysqli->run_select_query("select * from objects_relations where id=".intval($relation_id))) {
		if ($result->num_rows < 1) return false;
		$user_object_relation = $result->fetch_object();
		return $user_object_relation;
	}
	else return false;
}


function get_object_relation_by_object_id($object_id1,$object_id2){
    global $mysqli;
    if ($result = $mysqli->run_select_query("select * from objects_relations where object_id1=".intval($object_id1)." and object_id2=".intval($object_id2))) {
        if ($result->num_rows < 1) return false;
		$user_object_relation = $result->fetch_object();
        return $user_object_relation;
    }
    else return false;
}

function run_select_query($sql){
    global $memcache;
    global $mysqli;

    //create an index key for memcache
    $key = md5('query'.$sql);

    //lookup value in memcache
    $result = $memcache->get($key);

    //check if we got something back
    if($result == null) {
        //fetch from database
        $qry = mysqli_query($sql) or die(mysql_error()." : $sql");

        if(mysqli_num_rows($qry)> 0) {
            $result = mysqli_fetch_object($qry);
            //store in memcache
            $memcache->set($key,$result,0,3600);
        }
    }
    return $result;
}

?>