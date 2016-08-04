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

	$config->value = $data->value;
	$config->type = $data->type;

	$stmt = $config->checkConfigName();

	$config_arr = array();
	if (($stmt->rowCount())>0) {
		$config_arr['id'] = $config->id;
		$config_arr['value'] = $config->value;
		$config_arr['type'] = $config->type;
		$config_arr['status'] = $config->status;
	}

  print_r(json_encode($config_arr));
?>
