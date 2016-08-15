<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){
		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("role.php") && include_once("role.php") ){
		$role = new Role($db);
	} else {
		echo 'Unable to include control file.';
	}
	$para = json_decode(file_get_contents('php://input'));
	$role->role_id = $para->role_id;

	$value = $role->readForRole();
	$num = $value->rowCount();
	$data = '';
	if ($num>0) {
		$x = 1;
		while($val = $value->fetch(PDO::FETCH_ASSOC)) {
			extract($val);
			$data .= '{';
				$data .= '"id":"' . $id . '",';
				$data .= '"role_id":"' . $role_id . '",';
				$data .= '"menu_id":"' . $menu_id . '",';
				$data .= '"menu_value":"' . $menu_value . '",';
				$data .= '"config_id":"' . $config_id . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}

	echo '{"records":[' . $data . ']}';
?>
