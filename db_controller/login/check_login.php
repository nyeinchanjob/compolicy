<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("login.php") && include_once("login.php") ){
		$login = new Login($db);
	} else {
		echo 'Unable to include login file.';
	}

	$data = json_decode(file_get_contents('php://input'));

	$login->username = $data->username;
	$login->password = $data->password;
  $stmt = $login->checkLogin();
	$num = $stmt->rowCount();
	$login_arr = array();
	if($num>0) {
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				extract($row);
				$login_arr['id'] = $id;
				$login_arr['username'] = $username;
				$login_arr['password'] = $password;
				$login_arr['user_id'] = $user_id;
				$login_arr['firstname'] = $first_name;
				$login_arr['lastname'] = $last_name;
				$login_arr['role_id'] = $role_id;
				$login_arr['rolename'] = $role_name;
				$login_arr['position'] = $position;
				$login_arr['status'] = $login_status;

		}
	}
  print_r(json_encode($login_arr));
?>
