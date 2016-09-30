<?php
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charaset=UTF-8");
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){
		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("survey.php") && include_once("survey.php") ){
		$survey = new Survey($db);
	} else {
		echo 'Unable to include survey file.';
	}

    $config = json_decode(file_get_contents('php://input'));
		$data = '';
		$j = 1;
    for($i = 0; $i<count($config->cType); $i++) {

				$survey->config_type = $config->cType[$i];
				$stmt = $survey->readConfig();
        $num = $stmt->rowCount();
				$data .= '{"' . (string)$config->cType[$i] . 's":[' ;
        if($num>0) {
            $x = 1;

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

								extract($row);

                $data .= '{';
                    $data .= '"config_id":"' . $id . '",';
                    $data .= '"config_value":"' . $config_value . '",';
                    $data .= '"config_type":"' . $config_type . '",';
                    $data .= '"config_status":"' . $config_status . '",';
                    $data .= '"answer":""';
                $data .= '}';
                $data .= $x < $num ? ',' : '';
                $x++;
            }
        }
				$data .= ']}';
				$data .= $j < count($config->cType) ? ',' : '';
				$j++;
    }
    echo '{"records":['. $data .']}';
?>
