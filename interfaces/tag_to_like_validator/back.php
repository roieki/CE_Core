<?php

include("../loader.php");

$action = $_POST['action'];

switch ($action){
    case 'getPendingLikes':
        $tag_id = $_POST['tag_id'];
        getPendingLikes($tag_id);
        break;
    case 'getAllTags':
        getAllTags();
        break;
    case 'getLikesPerTag':
        $tag_id = $_POST['tag_id'];
        getLikesPerTag($tag_id);
        break;

    case 'approveLikeToTag':
        $tag_id = $_POST['tag_id'];
        $like_id = $_POST['like_id'];
        approveLikeToTag($like_id, $tag_id);
        break;

    case 'dissapproveLikeToTag':
        $tag_id = $_POST['tag_id'];
        $like_id = $_POST['like_id'];
        disapproveLikeToTag($like_id, $tag_id);
        break;
}

//DB function to get all tags from list
function getAllTags(){

    global $mysqli;
    if ($result = $mysqli->query("SELECT * FROM tags")) {
   		if ($result->num_rows < 1)  echo "false";

           while ($row = $result->fetch_assoc()) {
           $tags[]=$row;
           }

           echo json_encode($tags);
    }
	
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

function getLikesPerTag($tag_id){

global $mysqli;

if ($result = $mysqli->query("SELECT * FROM likes_tags_relations WHERE tag_id ='".$tag_id."'")) {
    
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

?>