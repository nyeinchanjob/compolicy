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

	$outlet_type->name = $data->name;

	$stmt = $outlet_type->checkOutletTypeName();

	$outlet_type_arr = array();
	if (($stmt->rowCount())>0) {
		$outlet_type_arr['id'] = $outlet_type->id;
		$outlet_type_arr['name'] = $outlet_type->name;
		$outlet_type_arr['is_active'] = $outlet_type->is_active;
	}

  print_r(json_encode($outlet_type_arr));
?>
