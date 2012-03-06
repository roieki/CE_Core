<?php
error_reporting(1);

include_once('loader.php');
global $mysqli;

$conf = $GLOBALS['conf'];

ob_start();

$start = time();

$facebook = new Facebook(array(
  'appId'  => $conf['fbappid'],
  'secret' => $conf['fbappsecret'],
));

if (isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];
    $access_token = $_GET['at'];
}
else{

	$user_id = $facebook->getUser();
}

?>

<html>
	<header>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            body{
                background-color: white;
                font-family: arial;
                direction: rtl;
            }
        </style>
	</header>
	<body>

<?php

//If we have Facebook user id
if ($user_id){
	$user_likes = json_decode(file_get_contents("https://graph.facebook.com/" . $user_id . "/likes?" . $access_token));
    $user_updated = registerUser($user_id,$user_likes);

    if ($user_updated){
        $user_categories = getCombinedTags($user_id);
    }
    else{
        $user_categories = updateCombinedTags($user_id,$user_likes);
    }
    //Check that user has the same number fo lkes
    //TODO: Why did we check existing relations count?
    //if ($likes_count == $left || $left == $existing_relations){
    if ($user_categories){
        echo "<script>window.location.href = 'http://combined-effect.com/CE_core/rest/user_categories_display.php?userid=" . $user_id . "';</script>";
        return;
    }
    else echo "Error.";

}
//if user is not logged in
else{
    $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream,user_likes' ) );
    echo 'Please <a href="' . $login_url . '">login.</a>';
    var_dump($login_url);
}
?>
	</body>	
</html>

