$(document).ready(function(){
    init();
});

function init(){
    $(".ce_control").click(function(){
        $action = $(this).attr('action');
        $fid = $(this).attr('fid');
        $.post("../add_to_favorites.php",{action: $action, fid: $fid, user_id: window.userid},function(){
            if ($action == "add"){
                $(this).attr('action',"remove");
                $(this).removeClass("ce_add").addClass("ce_remove");
                $(this).html('הסר ממועדפים');
            }
            if ($action == "remove"){
                $(this).attr('action',"add");
                $(this).removeClass("ce_remove").addClass("ce_add");
                $(this).html('הוסף למועדפים');
            }
        });
    });
}

