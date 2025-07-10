<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$id = null;
	if (array_key_exists('id', $_GET))
		$id = $_GET['id'];

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)  && $id) {
		pushQuestion($cnx, $user, $id);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "pushQuestion.php");
		echo 0;
	}
	close_db($cnx);

?>
