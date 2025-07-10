<?php
	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$question = null;
	if (array_key_exists('question', $_POST))
		$question = $_POST['question'];
	$cnx = connect_to_db('admin');
	if (getSession($cnx, $user, $event) && $question) {
		addQuestion($cnx, $user, $event, $question);
	} else {
		echo 0;
	}

?>