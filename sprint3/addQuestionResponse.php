<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$question = null;
	if (array_key_exists('question', $_POST))
		$question = $_POST['question'];
	$response1 = null;
	if (array_key_exists('response1', $_POST))
		$response1 = $_POST['response1'];
	$response2 = null;
	if (array_key_exists('response2', $_POST))
		$response2 = $_POST['response2'];
	$response3 = null;
	if (array_key_exists('response3', $_POST))
		$response3 = $_POST['response3'];
	$source = null;
	if (array_key_exists('source', $_POST))
		$source = $_POST['source'];

	$cnx = connect_to_db('admin', false);
	if (getSession($cnx, $session, $user, $event)  && $question && $response1 && $response2 && $response3 && isset($source)) {
		addQuestionResponse($cnx, $user, $event, $question, $response1, $response2, $response3, $source);
	} else {
		logging($cnx, "warning", "connection without session or empty parameters", "getSession", "addQuestionResponse.php");
		echo 0;
	}
	close_db($cnx);

?>
