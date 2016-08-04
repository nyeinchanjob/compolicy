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
		echo 'Unable to include product file.';
	}

	$data = json_decode(file_get_contents('php://input'));

  $role->id = $data->id;
	$role->name	= $data->name;
	$role->status = $data->status;

	if ($role->update()) {
		echo 'Role was updated.';
	} else {
		echo 'Unable to update role';
	}
?>
