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
		echo 'Unable to include product file.';
	}

	$data = json_decode(file_get_contents('php://input'));

  $config->id = $data->id;
	$config->value	= $data->value;
	$config->type	= $data->type;
	$config->status = $data->status;

	if ($config->update()) {
		echo 'Config was updated.';
	} else {
		echo 'Unable to update config';
	}
?>
