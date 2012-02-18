<?php
$conf = $GLOBALS['conf'];

$conf['lang'] = 'he';
$object_types['user'] = 1;
$object_types['like'] = 2;
$object_types['domain'] = 3;
$object_types['translation'] = 4;
$object_types['forum'] = 5;

$dev = checkDev();

if ($dev){
	$server = "localhost";
	$username = "nadavraj_admin";
	$pwd = "1354231";
	$db = "nadavraj_combinedfxp";
	$mysqli = new mysqli('localhost', 'nadavraj_admin', '1354231', 'nadavraj_combinedfxp');
	$conf['mongodb_address'] = 'mongodb://combined:combined@ds029297.mongolab.com:29297/combined';
	
}
else{
	//Production
	$server = "db01-share";
	$username = "Custom App-24318";
	$pwd = "combined";
	$db = "combined_fxp_phpfogapp_com";	
	$mysqli = new mysqli($server,$username,$pwd,$db);	
	$conf['mongodb_address'] = 'mongodb://combined:combined@ds029207.mongolab.com:29207/combined';
	
}
$GLOBALS['conf'] = $conf;

function checkDev(){
	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'] : "http://".$_SERVER['SERVER_NAME'];
	
	if ($url == 'http://combined-effect.com'){
		return true;
	}else{
		return false;
	}
}



?>