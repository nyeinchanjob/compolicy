<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("config.php") && include_once("config.php") ){
		$config = new Config($db);
	} else {
		echo 'Unable to include config file.';
	}

	$data = json_decode(file_get_contents('php://input'));

	$config->value	= $data->value;
	$config->type	= $data->type;
	$config->status = $data->status;

	if ($config->create()) {
		echo 'Config was created.';
	} else {
		echo 'Unable to create config';
	}
?>
