<?php
include_once("../loader.php");


function getMappedTagID($like,$client){
    global $mysqli;
    $like_id  = $like['id'];
    $like_name = $like['name'];

    $result = insertLike($like_id,$like_name);
    $tags = getTagInternalMapping($like_id);
    if ($tags){
        foreach($tags as $tag_id){
            $etags = getTagExternalMapping($tag_id,$client);
            if ($etags){
                foreach ($etags as $etag){
                    $external_tags[] = $etag;
                }
            }
        }

    }
    else{
        //no mapping
        createMappingForLikeOnTheFly($like);
    }

    return $external_tags;
}

function insertLike($like_id,$like_name){
    global $mysqli;

    $query = "INSERT IGNORE INTO likes (like_id,like_name, approved) VALUES (".$like_id.",'". $like_name."', 0)";
    return ($result = $mysqli->query($query));
}

function getTagInternalMapping($like_id){
    global $mysqli;
    $query = "select tag_id from like_tags_relations where like_id=" . $like_id;
    $result = $mysqli->query($query);
    if ($result->numrows > 0){
        while ($row = $result->fetch_assoc()){
            $tags[] = $row['tag_id'];
        }
        return $tags;
    }
    else return false;

}

function getTagExternalMapping($tag_id,$client){
    global $mysqli;
    $query = "select external_tag_id from ".$client."_tags_mapping where tag_id=" . $tag_id;
    $result = $mysqli->query($query);
    if ($result->numrows > 0){
        while ($row = $result->fetch_assoc()){
            $tags[] = $row['external_tag_id'];
        }
        return $tags;
    }
    else return false;

}

function getTagInternalFromExternal($external_tag_id,$client){
    global $mysqli;
    $query = "select internal_tag_id from ".$client."_tags_mapping where external_tag_id=" . $external_tag_id;
    $result = $mysqli->query($query);
    if ($result->numrows > 0){
        while ($row = $result->fetch_assoc()){
            $tags[] = $row['internal_tag_id'];
        }
        return $tags;
    }
    else return false;

}

function createMappingForLikeOnTheFly($like){
    //THIS IS WHAT WE WANT:
    $winning_external_tags = null;

    //go to bing and get forums from FXP

    $additional = get_object_vars(json_decode(file_get_contents("http://graph.facebook.com/" .$like->id)));
    $like_facebook_category = $additional['category'];

    $forum_links = process($like);
    //foreach forum we got
    foreach ($forum_links as $forum_link){
        $external_tag_id = getExternalTagsFromLink($forum_link);
        $internal_tag_ids = getTagInternalFromExternal($external_tag_id,'fxp');
        //if internal_tag_ids == false - i have nothing to say, nothing is approved.
        if (!$internal_tag_ids) continue;
        //else, data is valid and then, learn what you can from already mapped tags
        else{
            foreach ($internal_tag_ids as $internal_tag_id){
                $internal_tag_facebook_category = getInternalTagFacebookCategory($internal_tag_id);
                if (fuzzyFacebookCategoriesComaprison($internal_tag_facebook_category,$like_facebook_category)){
                    if (!isset($pro[$internal_tag_facebook_category])){
                        $pro[$internal_tag_facebook_category] = 1;
                    }
                    else {
                        $pro[$internal_tag_facebook_category]++;
                    }
                }
                else {
                    if (!isset($con[$internal_tag_facebook_category])){
                        $con[$internal_tag_facebook_category] = 1;
                    }
                    else {
                        $con[$internal_tag_facebook_category]++;
                    }
                }
            }

            //THE PUNCH LINE
            if (sizeof($pro) > sizeof($con)){
                $max = max($pro);
                $winning_internal_tag_facebook_category = array_search($max,$pro);
                if (!isset($winning_external_tags[$external_tag_id])){
                    $winning_external_tags[$external_tag_id] = 1;
                }
                else{
                    $winning_external_tags[$external_tag_id]++;
                }

            }
            //TODO: connect the tags found as new likes_tags_relations for the like we're on with approved= -10
        }
        //We can use a threshold here
    }

    //We can return more than one $external_tag_id per like
    //TODO: decide what to return and to whom.
    //TODO: insert on the fly mapping to db in user_categories
}


function getInternalTagFacebookCategory($internal_tag_id){
    global $mysqli;
    $query = 'select * from tags_facebook_category where tag_id = ' . $internal_tag_id . " limit 1";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    if ($row['category_id'] != 0){
        return $row['category_id'];
    }
    else{
        $query = 'select * from tags_relation where child_id = ' . $internal_tag_id;
        $result = $mysqli->query($query);
        while ($row = $result->fetch_assoc()){
            return getInternalTagFacebookCategory($row['parent_tag_id']);
        }
        return false;
    }
}

function getExternalTagsFromLink($link){
    if ($link == "empty") continue;
    if (preg_match ("/showthread\.php\?t\=([0-9]+)$/" , $link['url'], $m)){
        //set_object_relation($like_id,'like',$forum_id,'forum');
        //save_forum_relation_db($like_id,$forum_id);
        $external_tag_id =  $m[1];
        return $external_category_id;
    }
    else{
        return false;
    }
}

function fuzzyFacebookCategoriesComaprison($category_a,$category_b){
    global $mysqli;

        if ($category_a == $category_b) return true;
        $query = 'select * from fb2fb where category_a = ' . $category_a . " and category_b = " . $category_b;
        $result = $mysqli->query($query);

        if ($result->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
}



?>

