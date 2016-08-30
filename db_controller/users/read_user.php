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
	
	$para = json_decode(file_get_contents('php://input'));
	$user->issysadmin = $para->issysadmin;
	$role_list = $user->readAllRole();
	$role_num = $role_list->rowCount();
	$data = '';
	if($role_num>0) {
		$x = 1;
		while($role_row = $role_list->fetch(PDO::FETCH_ASSOC)) {
	 		extract($role_row);

				$data_id = $id;
				$user_role_name = $role_name;
				$user->role_id = $id;
				$user_list = $user->readAll();
				$num = $user_list->rowCount();
				if ($num>0) {
					$j = 1;
					$data .= '{';
					$data .= '"type":"' . html_entity_decode($role_name) . '",';
					$data .= '"data" :[';
					while($row = $user_list->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$data .= '{';
							$data .= '"id":"' . $id . '",';
							$data .= '"name":"' . html_entity_decode($name) . '",';
							$data .= '"role_id":"' . $data_id . '",';
							$data .= '"role_name":"' . $user_role_name . '",';
							$data .= '"user_status":"' . $user_status . '"';
						$data .= '}';

						$data .= $j < $num ? ',' : '';
						$j++;
					}
					$data .= ']}';
					$data .= $x < $role_num ? ',' : '';
				}
				$x++;
	 	}
	}
	echo '{"records":[' . $data . ']}';
?>
