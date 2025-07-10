<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$id = null;
	if (array_key_exists('id', $_POST))
		$id = $_POST['id'];
	$question = null;
	if (array_key_exists('question', $_POST))
		$question = $_POST['question'];

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)  && $id && $question) {
		modifyQuestion($cnx, $user, $event, $id, $question);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "modifyQuestion.php");
		echo 0;
	}
	close_db($cnx);

?>
