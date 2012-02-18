<?php

$userid = $_POST['userid'];
$accessToken = $_POST['at'];

$_SESSION['userid'] = $userid;
$_SESSION['at'] = $accessToken;

?>