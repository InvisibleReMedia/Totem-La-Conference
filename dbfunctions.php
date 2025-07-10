<?php
	function getAuthentName($cnx, $user, &$greetings, &$firstName, &$lastName) {
		$sql = "SELECT greetings, firstName, lastName FROM users WHERE id = ?";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $user);
			$stmt->execute();
			$stmt->bind_result($greetings, $firstName, $lastName);
			$stmt->fetch();
			$stmt->close();
		} else 
			die("error : " . $cnx->error);
	}

	function getEventTitle($cnx, $event, &$title) {
		$sql = "SELECT title FROM events WHERE id = ?";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $event);
			$stmt->execute();
			$stmt->bind_result($title);
			$stmt->fetch();
			$stmt->close();
		} else
			die("error : " . $cnx->error);
	}

	function getQuestion($cnx, $id) {
		if ($stmt = $cnx->prepare("SELECT e.id AS id, q.content AS content, e.likes AS likes, e.date AS date FROM questions AS q, evqust AS e WHERE e.id = ? AND e.id_question = q.id")) {
			$stmt->bind_param("i", $id);
			if ($stmt->execute()) {
				$content = null;
				$likes = null;
				$date = null;
				$stmt->bind_result($id, $content, $likes, $date);
				$stmt->fetch();
				include('new-drawQuestion.php');
				$stmt->close();
			} else
				echo 0;
		}
		else
			die("error : " . $cnx->error);
	}

	function addQuestion($cnx, $user, $event, $question) {
		if ($stmt = $cnx->prepare("CALL addQuestion(?, ?, ?)")) {
			$stmt->bind_param("iis", $user, $event, $question);
			if ($stmt->execute())
				echo 1; // ajouter les logs dans MongoDB
			else
				echo 0; // ajouter les logs dans MongoDB
			$stmt->close();
		} else
			echo $cnx->error;// ajouter les logs dans MongoDB\unindent
	}

	function addLike($cnx, $user, $event, $evqust, $likes) {
		if ($stmt = $cnx->prepare("CALL addLikes(?, ?, ?, ?)")) {
			$stmt->bind_param("iiii", $user, $event, $evqust, $likes);
			if ($stmt->execute())
				echo 1; // ajouter les logs dans MongoDB
			else
				echo $cnx->error; // ajouter les logs dans MongoDB
			$stmt->close();
		} else
			echo $cnx->error;// ajouter les logs dans MongoDB\unindent
	}

	function closeQuestion($cnx, $user, $evqust) {
		if ($stmt = $cnx->prepare("CALL closeQuestion(?, ?)")) {
			$stmt->bind_param("ii", $evqust, $user);
			if ($stmt->execute())
				echo 1; // ajouter les logs dans MongoDB
			else
				echo 0; // ajouter les logs dans MongoDB
			$stmt->close();
		} else
			echo $cnx->error;// ajouter les logs dans MongoDB\unindent
	}

	function getReview($cnx, $last_date) {
		if ($stmt = $cnx->prepare("SELECT id_evqust AS id, type, infos, date AS last FROM review WHERE date >= ? ORDER BY date")) {
			$stmt->bind_param("s", $last_date);
			$stmt->execute();
			$stmt->bind_result($id, $type, $infos, $last);
			$first = true;
			while($stmt->fetch()) {
				if ($first) $first = false; else echo "," . PHP_EOL;
				echo "{ \"id\" : " . $id . ", \"type\" : \"" . $type . "\", \"infos\" : " . $infos . ", \"last\" : \"" . $last . "\" }" . PHP_EOL;

			}
			$stmt->close();
 		}
	}

	function disconnect($cnx, $user, $event) {
		if ($stmt = $cnx->prepare("CALL disconnect(?, ?)")) {
			$stmt->bind_param("ii", $user, $event);
			if ($stmt->execute()) {
				if (array_key_exists('session', $_COOKIE)) {
					$_COOKIE['session'] = "";
				}
				echo 1;
			}
			else
				echo 0;
			$stmt->close();
		} else
			echo $cnx->error;
	}
?>