<?php
	if(file_exists('../../user/database.php') && include_once('../../user/database.php') ){

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
	$user->department = $data->department;
	$user->position = $data->position;
	$user->role_id = $data->role_id;
	$user->username = $data->username;
	$user->password = $data->password;
	$user->user_status = $data->user_status;

	if ($user->create()) {
		echo 'User was created.';
	} else {
		echo 'Unable to create user';
	}
?>
