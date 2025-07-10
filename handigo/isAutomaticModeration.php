<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)) {
		if (array_key_exists('relatedEvent', $_GET)) {
			$event = $_GET['relatedEvent'];
		}
		$sql = "SELECT JSON_EXTRACT(e.infos, '$.automaticModeration') as automaticModeration FROM events AS e WHERE e.id = " . $event;
		if ($result = $cnx->query($sql)) {
			$row = $result->fetch_assoc();
			echo $row['automaticModeration'];
		} else {
			logging($cnx, "ERROR", $cnx->error, "", "isAutomaticModeration.php");
			echo 0;
		}
		
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "isAutomaticModeration.php");
		echo 0;
	}
	close_db($cnx);

?>
