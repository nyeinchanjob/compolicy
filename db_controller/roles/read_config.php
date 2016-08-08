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
		$config = new Role($db);
	} else {
		echo 'Unable to include config file.';
	}
	$data = json_decode(file_get_contents('php://input'));
	$confType = $data->config_type;
	$data = '';
	for($i = 0; $i<count($confType); $i++) {
		$config->type = $confType[$i];
		$value = $config->readMenuConfig();
		$num = $value->rowCount();

		if ($num>0) {
				$x = 1;
				while($val = $value->fetch(PDO::FETCH_ASSOC)) {
					extract($val);
					$data .= '{';
						$data .= '"type":"' . $confType[$i] . '",';
						$data .= '"data":[';
						$data .= '{';
							$data .= '"id":"' . $id . '",';
							$data .= '"config_value":"' . html_entity_decode($config_value) . '",';
							$data .= '"config_type":"' . html_entity_decode($config_type) . '",';
							$data .= '"config_status":"' . $config_status . '"';
						$data .= '}';
						$data .= ']';
					$data .= '}';

						$data .= $x < $num ? ',' : '';
						$x++;
				}
		}
	}

	echo '{"records":[' . $data . ']}';
?>
