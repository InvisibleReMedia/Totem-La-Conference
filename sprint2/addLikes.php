<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$id = null;
	if (array_key_exists('id', $_GET))
		$id = $_GET['id'];
	$likes = null;
	if (array_key_exists('likes', $_GET))
		$likes = $_GET['likes'];

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)  && $id && $likes) {
		addLike($cnx, $user, $event, $id, $likes);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "addLikes.php");
		echo 0;
	}
	close_db($cnx);

?>
