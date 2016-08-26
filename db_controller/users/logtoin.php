<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists('user.php') && include_once('user.php') ){
		$user = new User($db);
	} else {
		echo 'Unable to include user file.';
	}

	//$data = json_decode(file_get_contents('php://input'));

	//$user->username = $data->username;
	//$user->password = $data->password;
	
	print_r('Yae');	
	//$stmt = $user->login();

	//$user_arr = array();
	//if (($stmt->rowCount())>0) {
		
	//	$user_arr['id'] = $user->id;
	//	$user_arr['name'] = $user->name;
	//	$user_arr['department'] = $user->department;
	//	$user_arr['position'] = $user->position;
	//}

	//print_r(json_encode($user_arr));
?>
