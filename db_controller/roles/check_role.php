<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("role.php") && include_once("role.php") ){
		$role = new Role($db);
	} else {
		echo 'Unable to include role file.';
	}

	$data = json_decode(file_get_contents('php://input'));

	$role->name = $data->name;
	$stmt = $role->checkRoleName();

	$role_arr = array();
	if (($stmt->rowCount())>0) {
		$role_arr['id'] = $role->id;
		$role_arr['name'] = $role->name;
		$role_arr['status'] = $role->status;
	}

  print_r(json_encode($role_arr));
?>
