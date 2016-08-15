<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists('role.php') && include_once('role.php') ){
		$role = new Role($db);
	} else {
		echo 'Unable to include role file.';
	}

	$data = json_decode(file_get_contents('php://input'));

	$role->name = $data->name;
	$role->permission = $data->permission;
	$role->menu_arr = $data->menu_list;
	$role->status = $data->status;

	if ($role->create()) {
		echo 'Role was created.';
	} else {
		echo 'Unable to create role';
	}
?>
