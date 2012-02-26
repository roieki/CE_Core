$(document).ready(function () {
    init();
});

function init(){
    $(".manualTagInput").autocomplete({
        source: window.combinedTagsJSONencoded,
        focus: function (event, ui) {
        },
        select:function (event, ui) {
            $(".manualTagInput-id").val(ui.item.id);
            $(".manualTagInput").val(ui.item.label);
        },
        change:function (event, ui) {
            $(".manualTagInput-id").val(ui.item.id);
            $(".manualTagInput").val(ui.item.label);
        }
    });

    initTagControls();
    initButtons();
}

function initTagControls(){
    $(".relation_type_selector").change(function(){
        $selected = $(this).val();
        $(this).parent().siblings("." + $selected).slideDown();
        $(this).parent().siblings().not("." + $selected).hide();

        $input = $(this).parent().siblings("." + $selected).children().children('.'+$selected+'_input');
        $input_id = $(this).parent().siblings("." + $selected).children().children('.'+$selected+'_input-id');
        if ($selected == "mapping_controls"){
            $data = window.forumListJson;
        }
        else if ($selected == "tags_controls"){
            $data = window.combinedTagsJSONencoded;
        }

        initAutocomplete($input,$input_id,$data);
    });
}

function initAutocomplete($target,$id_target,$source){
    $target.autocomplete({
        source: $source,
        focus: function (event, ui) {
        },
        select:function (event, ui) {
            $id_target.val(decodeURI(ui.item.id));
            $target.val(decodeURI(ui.item.label));
        },
        change:function (event, ui) {
            $id_target.val(decodeURI(ui.item.id));
            $target.val(decodeURI(ui.item.label));
        }
    });
}

function initButtons(){
    $(".button").click(function(){
        $button = $(this);
        $action = $button.attr('action');
        switch ($action){
            case 'rename':
                renameTag($button);
                break;
            case 'explore':
                exploreTag($button);
                break;
            case 'connect':
                connectTag($button);
                break;
            case 'delete':
                deleteTag($button);
                break;
            case 'updateMapping':
                updateMapping($button);
                break;
            case 'updateTagsRelation':
                updateTagsRelation($button);
                break;
            case 'newTag':
                newTag($button);
                break;
        }
    });
}


function exploreTag($button){
    $(".console").html('');
    $tag_id = $button.parent().attr('entryid');
    $.post('controller.php',{action:'getTagLikes',tag_id:$tag_id},function(data){
        formatPendingLikes(data,$tag_id);
    },"json");


}

function deleteTag($button){
    $(".console").html('');
    $tag_id = $button.parent().attr('entryid');
    $.post('controller.php',{action:'deleteTag',tag_id:$tag_id},function(){
        console.log($button.parent());
        console.log($button.parent().parent());
        $button.parent().parent().slideUp();
    });
}

function formatLikeEntries(jsonData){
    $.each(jsonData, function(key, value){

        $("body").append('<div>'+value+'</div>');
    });
}

function formatPendingLike($entry,$tag_id){

    $entryHtml = '<div class="entry">';
    $entryHtml += '<span class="entry_name" like_id="'+$entry.like_id+'" tag_id="'+$tag_id+'">'+ $entry.like_name  + '</span>';
    $entryHtml += '<span class="entry_controls button green" action="approveLikeToTag">Approve</span>';
    $entryHtml += '<span class="entry_controls button red" action="dissapproveLikeToTag">Reject</span>';
    $entryHtml += '<span class="entry_controls button silver" action="skip">Skip</span>';
    $entryHtml +='</div>';
    $(".console").append($entryHtml);
}
function formatPendingLikes(data,$tag_id){
    $entries = data;
    for (index in $entries){
            $entry = $entries[index];
            formatPendingLike($entry,$tag_id);
        }
    initPendingEntries();
}

function initPendingEntries(){
    $(".entry_controls").click(function(){
        $action = $(this).attr('action');
        $button = $(this);
        $like_id = $(this).siblings(".entry_name").attr('like_id');
        $tag_id = $(this).siblings(".entry_name").attr('tag_id');

        $.post("controller.php",{action: $action, like_id: $like_id, tag_id: $tag_id},function(data){
            $button.parents(".entry").fadeOut();
        },"json");

    });
}



/*Toggle functions for tag renaming*/
function renameTag($button){
    $tagNameDiv = $button.parent().siblings(".tag_name");
    $input = '<input id="tag_rename" value="'+$tagNameDiv.html()+'"></input>';
    $tagNameDiv.html($input);
    $button.attr('action','setName');
    $button.html('Save');
    $button.one('click',function(){
        setTagName($button);
    });
}

function setTagName($button){
    $tagNameDiv = $button.parent().siblings();
    $newTagValue = $tagNameDiv.children().val();
    $tag_id = $button.parent().attr('entryid');
    $.post('controller.php',{action:'updateTagName','value': $newTagValue,tag_id:$tag_id},function(data){
        $tagNameDiv.html($newTagValue);
        $button.attr('action','rename');
        $button.html('Rename');
        $button.one('click',function(){
            renameTag($button);
        });
    });
}

function newTag($button){
    $tagNameDiv = $button.parent().siblings();
    $newTagValue = $(".manualTagInput").val();
    if ($newTagValue != ''){
        $.post('controller.php',{action:'newTag',tag_value: $newTagValue},function(data){
            console.log(data);
        });
    }

}

function connectTag($button){
    $relationsControls = $button.parentsUntil(".tags_data").siblings(".relations_control");
    $relationsControls.slideDown('slow');
}

function updateTagsRelation($button){

}

function updateMapping($button){
    $forum_id = $(".mapping_controls_input-id").val();
    $tag_id = $(this).parentsUntil(".relations_control").siblings(".tag_data").children(".tag_controls").attr("entryid");
    console.log($(this).parentsUntil(".relations_control"));
    console.log($forum_id+","+$tag_id);
    //$.post('controller.php',{action:'update_external_mapping','tag_id': $tag_id,forum_id:$forum_id},function(data){

    //});
}