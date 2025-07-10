<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event) ) {
		disconnect($cnx, $user, $event);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "disconnect.php");
		echo 0;
	}
	close_db($cnx);

?>
