
<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)) {
		echo 1;
	} else {
		logging($cnx, "warning", "connection without session", "getSession", "testSession.php");
		echo 0;
	}
	close_db($cnx);
?>