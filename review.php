<?php

	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$cnx = connect_to_db('admin');
	$last_date = null;
	if (array_key_exists('last_date', $_GET))
		$last_date = $_GET['last_date'];
	echo "[" . PHP_EOL;
	if (getSession($cnx, $user, $event) && $last_date) {
		getReview($cnx, $last_date);
	}
	echo "]" . PHP_EOL;

?>