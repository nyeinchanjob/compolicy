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
		echo 'Unable to include role file.';
	}

	$stmt = $role->readAll();
	$num = $stmt->rowCount();

	if($num>0) {
		$data = '';
		$x = 1;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$data .= '{';
				$data .= '"id":"' . $id . '",';
				$data .= '"role_name":"' . html_entity_decode($role_name) . '",';
				$data .= '"role_status":"' . $role_status . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}
	echo '{"records":[' . $data . ']}';
?>
