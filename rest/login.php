<?php
ob_start();
include('loader.php');

$start = time();
$facebook = new Facebook(array(
  'appId'  => '119906071429474',
  'secret' => '6515dd77294dc8bdeabaae0083c16957',
));

	
?>
<?php
		$user_id = $facebook->getUser();
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="he">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta id="e_vb_meta_bburl" name="vb_meta_bburl" content="http://www.fxp.co.il" />
	<meta http-equiv="X-UA-Compatible" content="IE=100"/>
		<style>
            body{
                background: white;
                height: 40px;
            }

            .login{
                width:468px;
                height:68px;
                font-size:50px;
                color:white;
                background-color: #3965a4;
                text-align:center;
            }
            .connect{
                font-family: arial;
                background: #3B5998;
                width: 120px;
                padding: 10px;
                color: white;
                border-radius: 5px;
                font-weight: 700;
                font-size: 18px;
                text-align: center;
                cursor: pointer;
                margin-right: 148px;
            }
            .connect.intransit{
                margin-right: 5px;
            }
		</style>	
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://combined-effect.com/fxp/js/easyXDM/easyXDM.debug.js"></script>
		<script type="text/javascript" src="js/login.js"></script>

	</head>
	<body> 	
        <div class="connect">התחברו ל-iFXP</div>
	</body>	
</html>
