<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$question = null;
	if (array_key_exists('question', $_POST))
		$question = $_POST['question'];
	$response = null;
	if (array_key_exists('id_response', $_POST))
		$response = $_POST['id_response'];
	$responseNumber = null;
	if (array_key_exists('responseNumber', $_POST))
		$responseNumber = $_POST['responseNumber'];
	$source = null;
	if (array_key_exists('source', $_POST))
		$source = $_POST['source'];

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)  && $question && $response && $responseNumber && isset($source)) {
		answerQuestion($cnx, $user, $event, $question, $response, $responseNumber, $source);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "answerQuestion.php");
		echo 0;
	}
	close_db($cnx);

?>
