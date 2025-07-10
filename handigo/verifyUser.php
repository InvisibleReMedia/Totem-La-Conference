<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('dbfunctions.php');
	$firstName = null;
	if (array_key_exists('firstName', $_GET))
		$firstName = $_GET['firstName'];
	$lastName = null;
	if (array_key_exists('lastName', $_GET))
		$lastName = $_GET['lastName'];
	$event = null;
	if (array_key_exists('id_event', $_GET))
		$event = $_GET['id_event'];

	$cnx = connect_to_db('admin', false);
	if ($firstName && $lastName && $event) {
		$sql = "SELECT verifyUser(?, ?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ssi", $firstName, $lastName, $event);
			$stmt->bind_result($session);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "", "verifyUser.php");
			} else {
				if ($stmt->fetch()) {
					$result = true;
					echo $session;
				} else
					$result = false;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "", "verifyUser.php");
		}

	} else {
		logging($cnx, "warning", "invalid login parameters", "", "verifyUser.php");
	}
	close_db($cnx);
?>
