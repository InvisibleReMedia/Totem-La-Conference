<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$question = null;
	if (array_key_exists('question', $_POST))
		$question = $_POST['question'];
	$source = null;
	if (array_key_exists('source', $_POST))
		$source = $_POST['source'];

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)  && $question && isset($source)) {
		if (array_key_exists('relatedEvent', $_GET)) {
			$event = $_GET['relatedEvent'];
		}
		addQuestion($cnx, $user, $event, $question, $source);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "addQuestion.php");
		echo 0;
	}
	close_db($cnx);

?>
