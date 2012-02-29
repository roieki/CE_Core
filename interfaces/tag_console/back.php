<?php

include("../loader.php");

function delete_tag($tag_id){
    global $mysqli;
    // delete all tag relations
    $query ="DELETE FROM likes_tags_relations WHERE tag_id=" . $tag_id;
    if ($mysqli->query($query)) echo "true";
    else echo "false";
    
    // set tag to status delete
    $query = "update tags set approved=-1 where tag_id=" . $tag_id;
    if ($mysqli->query($query)) echo "true";
    else echo "false";

}

function rename_tag($tag_id, $value){
    global $mysqli;
         $query = "update tags set value=" . $value." where id=".$tag_id;

         if ($mysqli->query($query)) echo "true";
         else echo "false";


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

function set_tags_relation ($child_tag_id, $parent_tag_id)
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





?>
