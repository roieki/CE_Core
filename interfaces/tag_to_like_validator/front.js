$(document).ready(function () {
    init();
});

function init(){
   $action = "getAllTags";

    $.post("back.php",{action: $action},function(data){
        formatTags(data);
    },"json");

    $(".controls").click(function(){
        $action = $(this).attr('id');

    });
    $(".tagList_header").click(function(){
        $(".tagList_content").toggle();
    });
}

function formatTags(data){
    $entries = data;
    for (index in $entries){
        $entry = $entries[index];
        formatTag($entry);
    }

    initTagsSelector();
}

function formatPendingLikes(data,$tag_id){
    $entries = data;
    for (index in $entries){
            $entry = $entries[index];
            formatPendingLike($entry,$tag_id);
        }
    initPendingEntries();
}



function formatPendingLike($entry,$tag_id){

    $entryHtml = '<div class="entry">';
    $entryHtml += '<span class="entry_name" like_id="'+$entry.like_id+'" tag_id="'+$tag_id+'">'+ $entry.like_name  + '</span>';
    $entryHtml += '<span class="entry_controls button green" action="approveLikeToTag">Approve</span>';
    $entryHtml += '<span class="entry_controls button red" action="dissapproveLikeToTag">Reject</span>';
    $entryHtml += '<span class="entry_controls button silver" action="skip">Skip</span>';
    $entryHtml +='</div>';
    $(".results").append($entryHtml);
}

function formatTag($entry){
    $entryHtml = '<div class="tag button silver" tag_id="'+$entry.id+'">';
    $entryHtml += '<span class="tag_name">'+ $entry.value  + '</span>';
    $entryHtml +='</div>';
    $(".tagList_content").append($entryHtml);
}

function initPendingEntries(){
    $(".entry_controls").click(function(){
        $action = $(this).attr('action');
        $button = $(this);
        $like_id = $(this).siblings(".entry_name").attr('like_id');
        $tag_id = $(this).siblings(".entry_name").attr('tag_id');
        if ($action == "skip"){
            $button.parents(".entry").fadeOut();
        }
        else{
            $.post("back.php",{action: $action, like_id: $like_id, tag_id: $tag_id},function(data){
                $button.parents(".entry").fadeOut();
            },"json");
        }
    });
}

function initTagsSelector(){

    $(".tag").click(function(){
        $tag_id = $(this).attr('tag_id')
        $action = 'getPendingLikes';
        $(".tag").not($(this)).removeClass('red').addClass('silver');
        $(this).removeClass('silver').addClass('red');
        $(".results").html('');
        $.post("back.php",{action: $action, tag_id: $tag_id},function(data){
            formatPendingLikes(data,$tag_id);

        },"json");
    });
    $(".tag").first().click();
}