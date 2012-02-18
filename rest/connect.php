<?php

include('../system/facebook-php-sdk/src/facebook.php');

$start = time();
$facebook = new Facebook(array(
  'appId'  => '119906071429474',
  'secret' => '6515dd77294dc8bdeabaae0083c16957',
));

$user_id = $facebook->getUser();
if ($user_id){
	echo "Logged in";
}
else{
	 $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream' ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';
}
?>