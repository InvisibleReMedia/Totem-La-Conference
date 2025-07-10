<?php

	include('logging.php');

	include('connectodb.php');// connection method to db is not browsable

	include('getSession.php');

	include('dbfunctions.php');

	$cnx = connect_to_db('admin', false);

	if (getSession($cnx, $session, $user, $event)) {

$sql = "SELECT permissions FROM users WHERE id = " . $user;

		if ($fetch = $cnx->query($sql)) {

				if ($fetch->num_rows > 0) {

						$result = $fetch->fetch_assoc();

						$result = $result['permissions'];



						if (($result & 16) == 0) {

								header('Location: ./restrict.html');

								die('Redirection automatique dans quelques instants');

						}

			

				}

		} else {

				logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "question.php");

		}

		$sql = "UPDATE evqust SET moderation = 0";

		if ($fetch = $cnx->query($sql)) {

				if ($fetch->num_rows > 0) {

						$result = $fetch->fetch_assoc();

						echo 1

				}

		} else {

				logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "clear", "clear.php");

		}

			} else {

 logging($cnx, "warning", "connection without session or empty parameters", "getSession", "reset.php");

 echo 0;

	}

	close_db($cnx);



?>

