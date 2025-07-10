<?php

	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$id = null;
	if (array_key_exists('id', $_GET))
		$id = $_GET['id'];
	$cnx = connect_to_db('admin');
	if (getSession($cnx, $user, $event) && $id) {
		getQuestion($cnx, $id);
	} else {
		echo 0;
	}

?>