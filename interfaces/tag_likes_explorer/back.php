<?php
include("../loader.php");


function getAllTags($json=true){
    global $mysqli;
    $query = "select * from tags where approved >= 0";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()){
        //$entry['value'] = urlencode(str_replace("'","",$row['value']));
        $entry['value'] = urlencode($row['value']);
        $entry['id'] = $row['id'];
        $tags[] = $entry;
    }
    if ($json){
        echo json_encode($tags);
    }
    else{
        return $tags;
    }

}

function delete_tag($tag_id){
    global $mysqli;
    // delete all tag relations

    $query ="DELETE FROM likes_tags_relations WHERE tag_id=" . $tag_id;
    if ($mysqli->query($query)) echo "true";
    else echo "false";

    // set tag to status delete
    $query = "update tags set approved=-1 where id=" . $tag_id;
    echo $query;
    if ($mysqli->query($query)) echo "true";
    else echo "false";

}

function rename_tag($tag_id, $value){
    global $mysqli;
    $query = "update tags set value='".$value."' where id=".$tag_id;
    echo $query;
    if ($mysqli->query($query)) echo "true";
    else echo "false";
}

function get_tags_relation($child_tag_id, $parent_tag_id){
    global $mysqli;
	if ($result = $mysqli->query("select * from tags_relation where child_tag_id=".intval($child_tag_id) . " and parent_tag_id=" . intval($parent_tag_id))) {
    if ($result->num_rows < 1) return false;
        else return true;
    }
    else return false;
}

function set_tags_relation ($child_tag_id, $parent_tag_id){
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


function getPendingLikes($tag_id){

    global $mysqli;

    if ($result = $mysqli->query("SELECT * FROM likes_tags_relations WHERE tag_id =".$tag_id." and approved=0")) {

        while ($row = $result->fetch_assoc()) {
            $like_id = $row["like_id"];
            if ($result2 = $mysqli->query("SELECT * FROM likes WHERE like_id =".$like_id)) {
                   if ($result2->num_rows < 1)  continue;
                $like= $result2->fetch_object();
                    //$like->id;
                    //$like->like_name;
                    //$like->approved;
                    //$like->is_like_data;
                    $entry->tag_id = $tag_id;

                    $likes[] = $like;

                }
        }
     echo json_encode($likes);
     }
    else echo "false";

}



function approveLikeToTag($like_id,$tag_id){
    global $mysqli;
     $query = "update likes_tags_relations set approved=1 where like_id=".$like_id . " and tag_id=" . $tag_id;

     if ($mysqli->query($query)) echo "true";
     else echo "false";


}

function disapproveLikeToTag($like_id,$tag_id){
    global $mysqli;
    $query = "update likes_tags_relations set approved=-1 where like_id=".$like_id . " and tag_id=" . $tag_id;

    if ($mysqli->query($query)) echo "true";
    else echo "false";


}

function skipLikeToTag($like_id,$tag_id){
    global $mysqli;
     $query = "update likes_tags_relations set approved=-2 where like_id=".$like_id . " and tag_id=" . $tag_id;

     if ($mysqli->query($query)) echo "true";
     else echo "false";


}

function get_external_categories($external_name,$tableName=null){
    global $mysqli;
    $external_table = $external_name . "_extrnal_categoires";
    if (!is_null($tableName)) $external_table = $tableName;

    $query = "select * from " . $external_table;

    if ($result = $mysqli->query($query)){
        while ($row = $result->fetch_assoc()){
            $entry['value'] = str_replace("'","",$row['forum_name']);
            $entry['id'] = $row['forumid'];
            $categories[] = $entry;
        }
        return json_encode($categories);
    }
    else{
        return false;
    }
}

function update_external_mapping($forum_id,$tag_id,$external_name,$tableName=null){
    global $mysqli;
    $external_table = $external_name . "_tags_mapping";
    if (!is_null($tableName)) $external_table = $tableName;

    $query = "insert ignore into " . $external_table . " values (" . $forum_id . "," . $tag_id . ")";
    if ($result = $mysqli->query($query)) return true;
    else return false;
}

function getFacebookCategories(){
    global $mysqli;
    $query = "select * from fb_categories";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()){
        $entry['id'] = $row['id'];
        $entry['value'] = $row['value'];
        $categories[] = $entry;
    }
    echo json_encode($categories);
}

function update_tag_facebook_category($tag_id,$fb_category){
    global $mysqli;
    $query = "insert ignore into internal_tags_fb_categories_mapping values (".$tag_id.",".$fb_category.",0)";
    $result = $mysqli->query($query);

}

?>