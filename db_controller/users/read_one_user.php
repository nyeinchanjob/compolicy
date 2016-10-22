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
	$user->readOne();

	$user_arr[] = array(
    			'id' => $user->id,
			'name' => $user->name,
			'department' => $user->department,
			'position' => $user->position,
			'role_id' => $user->role_id,
			'username' => $user->username,
			'password' => $user->password,
			'user_status' => $user->user_status
  		);

	print_r(json_encode($user_arr));
?>
