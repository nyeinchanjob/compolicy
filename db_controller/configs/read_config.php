<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){
		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("config.php") && include_once("config.php") ){
		$config = new Config($db);
	} else {
		echo 'Unable to include config file.';
	}

	 $confType = $config->readAllType();
	 $config_num = $confType->rowCount();
	 $data = '';
	 if($config_num>0) {
	 	$x = 1;
	 	while($confrow = $confType->fetch(PDO::FETCH_ASSOC)) {
	 		extract($confrow);

			$data .= '{';
				$data .= '"type":"' . html_entity_decode($config_type) . '",';
				$data .= '"data" :[';

				$config->type = $config_type;
				$conf = $config->readAll();
				$num = $conf->rowCount();
				if ($num>0) {
					$j = 1;
					while($row = $conf->fetch(PDO::FETCH_ASSOC)){
						extract($row);
						$data .= '{';
							$data .= '"id":"' . $id . '",';
							$data .= '"config_value":"' . html_entity_decode($config_value) . '",';
							$data .= '"config_type":"' . html_entity_decode($config_type) . '",';
							$data .= '"config_status":"' . $config_status . '"';
						$data .= '}';

						$data .= $j < $num ? ',' : '';
						$j++;
					}
				}
			$data .= ']}';
			$data .= $x < $config_num ? ',' : '';
			$x++;
	 	}
	}
	echo '{"records":[' . $data . ']}';
?>
