<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("user.php") && include_once("user.php") ){
		$user = new User($db);
	} else {
		echo 'Unable to include user file.';
	}

	$data = json_decode(file_get_contents('php://input'));
	$user->name = $data->name;

	$stmt = $user->checkUserName();

	$user_arr = array();
	if (($stmt->rowCount())>0) {
		$user_arr['id'] = $user->id;
		$user_arr['name'] = $user->name;
	}

  print_r(json_encode($user_arr));
?>
