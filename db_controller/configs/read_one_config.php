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

	$config->id = $data->id;
  $config->readOne();

  $config_arr[] = array(
    'id' => $config->id,
    'value' => $config->value,
    'type' => $config->type,
    'status' => $config->status
  );

  print_r(json_encode($config_arr));
?>
