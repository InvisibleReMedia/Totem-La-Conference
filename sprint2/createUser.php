<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('dbfunctions.php');
	$event = null;
	if (array_key_exists('id_event', $_GET))
		$event = $_GET['id_event'];

	$cnx = connect_to_db('admin', false);
	if ($event) {
		$sql = "SELECT createUser(?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $event);
			$stmt->bind_result($session);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "", "createUser.php");
			} else {
				if ($stmt->fetch()) {
					$result = true;
					echo $session;
				} else
					$result = false;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "", "createUser.php");
		}

	} else {
		logging($cnx, "warning", "invalid login parameters", "", "createUser.php");
	}
	close_db($cnx);
?>
