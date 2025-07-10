<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$last_date = null;
	if (array_key_exists('last_date', $_GET))
		$last_date = $_GET['last_date'];

	$cnx = connect_to_db('admin', false);
	echo "[" . PHP_EOL;
	if (getSession($cnx, $session, $user, $event)  && $last_date) {
		if (array_key_exists('relatedEvent', $_GET)) {
			$relatedEvent = $_GET['relatedEvent'];
		} else {
			$relatedEvent = $event;
		}
		getReview($cnx, $event, $relatedEvent, $last_date);
	} else {
		logging($cnx, "warning", "connection without session", "getSession", "review.php");
	}
	echo "]" . PHP_EOL;
	close_db($cnx);
?>
