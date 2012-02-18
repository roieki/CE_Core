<?php

$conf = $GLOBALS['conf'];
 
$env = getEnvPath();
switch ($env){
	case 'dev':
		$conf['base'] = 'http://combined-effect.com/fxp/';
		$conf['base_switch'] = 'http://combined-fxp.phpfogapp.com/index.php';
		$conf['env_name'] = 'Development';
		$conf['fbappid'] = '119906071429474';
		$conf['fbappsecret'] = '6515dd77294dc8bdeabaae0083c16957'; 
		break;
	case 'production':
		$conf['base'] = 'http://combined-fxp.phpfogapp.com/';
		$conf['base_switch'] = 'http://combined-effect.com/fxp/index.php';
		$conf['env_name'] = 'Production';
		$conf['fbappid'] = '311338642243201';
		$conf['fbappsecret'] = '96a50449dfd5b0d2aac997067729bf91'; 
		break;
}
$GLOBALS['conf'] = $conf;

function getEnvPath(){
	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'] : "http://".$_SERVER['SERVER_NAME'];
	
	if ($url == 'http://combined-effect.com'){
		return 'dev';
	}else{
		return 'production';
	}
}
	
?>