<?php
	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	$cnx = connect_to_db('admin');
	if (getSession($cnx, $user, $event))
		echo 1;
	else
		echo 0;
?>