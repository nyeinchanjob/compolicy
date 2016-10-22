<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("outlet_type.php") && include_once("outlet_type.php") ){
		$outlet_type = new OutletType($db);
	} else {
		echo 'Unable to include outlet_type file.';
	}

	$data = json_decode(file_get_contents('php://input'));

  $outlet_type->id = $data->id;

	if ($outlet_type->delete()) {
		echo 'Outlet  Type was deleted.';
	} else {
		echo 'Unable to delete outlet Type';
	}
?>
