<?php
include('../loader.php');
include('back.php');
$action = $_POST['action'];

switch ($action){
    case 'updateTagName':
        $tag_id = $_POST['tag_id'];
        $value = $_POST['value'];
        rename_tag($tag_id, $value);
        break;
    case 'getTagLikes':
        $tag_id = $_POST['tag_id'];
        getPendingLikes($tag_id);
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

    case 'skip':
        $tag_id = $_POST['tag_id'];
        $like_id = $_POST['like_id'];
        skipLikeToTag($like_id,$tag_id);
        break;

}


?>