<?php
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
include('loader.php');
include_once('controller.php');
global $mysqli;

$forum_id = $_GET["fid"];
$action = $_GET['action'];
$user_id = $_COOKIE['combined'];


if ($action == "check"){
    $exists = check_user_favorite($user_id,$forum_id);
    $nextAction = "check";
}
if ($action == "add"){
    add_forum_to_favorites($user_id,$forum_id);
    $nextAction = "remove";
}
if ($action == "remove"){
    delete_user_forum($user_id,$forum_id);
    $nextAction = "add";
}
$data = "success";
?>
<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){
//            socket.postMessage("the string I want to send to the other end");

            $(".ce_control").click(function(){
                $fid = $(this).attr('fid');
                $action = $(this).attr('action');
                $url = 'http://combined-effect.com/CE_core/rest/add_to_favorites.php?action=' + $action + '&fid=' + $fid;
                window.location = $url;
                if(typeof parent.window !== 'undefined' && typeof parent.window.combinedRefresh === 'function'){
                    combinedRefresh(); //Call the function only if it exists
                }
                else{
                    console.log(typeof parent.window);
                    console.log(typeof parent.window.combinedRefresh);
                }
            });
        });
        </script>
        <style>
            .ce_control{
                font-family: Arial,Calibri,Verdana,Geneva,sans-serif;
                font-size: 13px;
                float: right;
            }
        </style>
    </head>
    <body>

        <?php
            switch($nextAction){
                case "check":
                    if ($exists){
                        ?><div class="ce_control" action="remove" fid="<?=$forum_id?>">הסר ממועדפים</div><?php
                    }
                    else{
                        ?> <div class="ce_control" action="add" fid="<?=$forum_id?>">הוסף למועדפים</div><?php
                    }
                    break;
                case "remove":
                    ?> <div class="ce_control" action="remove" fid="<?=$forum_id?>">הסר ממועדפים</div><?php
                    break;
                case "add":
                    ?><div class="ce_control" action="add" fid="<?=$forum_id?>">הוסף למועדפים</div><?php
                    break;
            }
        ?>

    </body>
</html>
