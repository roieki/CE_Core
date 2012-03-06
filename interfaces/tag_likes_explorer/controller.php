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
    case 'deleteTag':
        $tag_id = $_POST['tag_id'];
        delete_tag($tag_id);
        break;
    case 'skip':
        $tag_id = $_POST['tag_id'];
        $like_id = $_POST['like_id'];
        skipLikeToTag($like_id,$tag_id);
        break;
    case 'updateMapping':
        $tag_id = $_POST['tag_id'];
        $forum_id = $_POST['forum_id'];
        update_external_mapping($forum_id,$tag_id,$external_name);
        break;
    case 'updateTagsRelations':
        break;
    case 'newTag':
        $tag_value = $_POST['tag_value'];
        set_tag($tag_value);
        if (isset($_POST['expand'])){
            $tag = get_tag($tag_value);
            $tag_id = $tag->id;
            expand_tag($tag_id);
        }
        break;
    case 'update_facebook_mapping':
        $tag_id = $_POST['tag_id'];
        $fb_category = $_POST['fb-category'];
        update_tag_facebook_category($tag_id,$fb_category,$external_name);
        break;
}


?>