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
  $login->checkLogin();

  $login_arr[] = array(
    'id' => $login->id,
    'username' => $login->username,
    'password' => $login->password,
    'user_id' => $login->user_id,
    'firstname' => $login->fname,
    'lastname' => $login->lname,
    'role_id' => $login->role_id,
    'rolename' => $login->rname,
    'status' => $login->status
  );

  print_r(json_encode($login_arr));
?>
