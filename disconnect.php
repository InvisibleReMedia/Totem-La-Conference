<?php
	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$cnx = connect_to_db('admin');
	if (getSession($cnx, $user, $event)) {
		disconnect($cnx, $user, $event);
	} else {
		echo 0;
	}
?>