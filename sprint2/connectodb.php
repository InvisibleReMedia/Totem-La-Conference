<?php
function connect_to_db($user, $redirect) {
	$mac = get_cfg_var("MySQL_machine_name");
	$db = get_cfg_var("MySQL_database_name");
	$userName = get_cfg_var("MySQL_user_name_" . $user);
	$password = get_cfg_var("MySQL_password_" . $user);
	$conn = new mysqli($mac, $userName, $password, $db);
	if ($conn->connect_error) {
		if ($redirect)
			header("Location: error.html?text=" . urlencode("db connection failed - reason:" . $conn->connect_error));
		else
			die("Connection error - reason :" . $conn->connect_error);
	}
	return $conn;
}

function close_db($cnx) {
	$cnx->close();
}
?>