<?php
	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$id = null;
	if (array_key_exists('id', $_GET))
		$id = $_GET['id'];
	$likes = null;
	if (array_key_exists('likes', $_GET))
		$likes = $_GET['likes'];
	$cnx = connect_to_db('admin');
	if (getSession($cnx, $user, $event) && $id && $likes) {
		addLike($cnx, $user, $event, $id, $likes);
	} else {
		echo 0;
	}

?>