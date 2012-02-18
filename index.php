<?php
	//TODO: creat main index loader.php
	include_once("system/conf/paths.php");
	
	
?>

<html>
	<head>
		
	</head> 
	<body>
		<h1>Combined Effect / FXP</h1>
		<h3>Enviornment: <?=$conf['env_name']?></h3>
		<h4><a href="<?=$conf['base_switch']?>">Switch Enviorment</a></h6>
			<ul>
				<li><a href="<?php echo $confp['base'] . "/crm/index.php"; ?>">CRM</a></li>
				<li><a href="<?php echo $confp['base'] . "/install/index.php"; ?>">Install</a></li>
			</ul>
	</body>
</html>