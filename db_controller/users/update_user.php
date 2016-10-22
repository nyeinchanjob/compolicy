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

	$user->id = $data->id;
	$user->name = $data->name;
	$user->department = $data->department;
	$user->position = $data->position;
	$user->role_id = $data->role_id;
	$user->username = $data->username;
	$user->password = $data->password;
	$user->user_status = $data->user_status;

	if ($user->update()) {
		echo 'User was updated.';
	} else {
		echo 'Unable to update user';
	}
?>
