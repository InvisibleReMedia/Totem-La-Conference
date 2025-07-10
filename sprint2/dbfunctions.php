<?php
	/*****************
		  Function : getAuthentName
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    &$greetings : out Civility
		    &$firstName : out first name
		    &$lastName : out last name
		  Output : Nothing
	*****************/

	function getAuthentName($cnx, $user, &$greetings, &$firstName, &$lastName) {
		$sql = "SELECT greetings, firstName, lastName FROM users WHERE id = ?";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $user);
			$stmt->bind_result($greetings, $firstName, $lastName);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getAuthentName", "dbfunctions.php");
			else {
				$stmt->fetch();

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getAuthentName", "dbfunctions.php");
		}

	}

	/*****************
		  Function : getEventTitle
		  Parameters : 
		    $cnx : DB connection
		    $event : event id
		    &$title : out event title
		  Output : Nothing
	*****************/

	function getEventTitle($cnx, $event, &$title) {
		$sql = "SELECT title FROM events WHERE id = ?";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $event);
			$stmt->bind_result($title);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getEventTitle", "dbfunctions.php");
			else {
				$stmt->fetch();

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getEventTitle", "dbfunctions.php");
		}

	}

	/*****************
		  Function : getQuestion
		  Parameters : 
		    $cnx : DB connection
		    $id : evqust id
		  Output : HTML draw question
	*****************/

	function getQuestion($cnx, $id) {
		$content = null;
		$likes = null;
		$date = null;
		$selected = null;
		$sql = "SELECT q.content AS content, e.likes AS likes, e.date AS date, e.moderation AS moderation, e.selected AS selected FROM questions AS q, evqust AS e WHERE e.id = ? AND e.id_question = q.id";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $id);
			$stmt->bind_result($content, $likes, $date, $moderation, $selected);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getQuestion", "dbfunctions.php");
			else {
				$stmt->fetch();

				include('drawQuestion.php');

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getQuestion", "dbfunctions.php");
		}

	}

	/*****************
		  Function : getQuestionAnimateur
		  Parameters : 
		    $cnx : DB connection
		    $id : evqust id
		  Output : HTML draw question
	*****************/

	function getQuestionAnimateur($cnx, $id) {
		$content = null;
		$likes = null;
		$date = null;
		$selected = null;
		$sql = "SELECT q.content AS content, e.likes AS likes, e.date AS date, e.moderation AS moderation, e.selected AS selected FROM questions AS q, evqust AS e WHERE e.id = ? AND e.id_question = q.id";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $id);
			$stmt->bind_result($content, $likes, $date, $moderation, $selected);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getQuestionAnimateur", "dbfunctions.php");
			else {
				$stmt->fetch();

				include('drawQuestionAnimateur.php');

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getQuestionAnimateur", "dbfunctions.php");
		}

	}

	/*****************
		  Function : getQuestionModerateur
		  Parameters : 
		    $cnx : DB connection
		    $id : evqust id
		  Output : HTML draw question
	*****************/

	function getQuestionModerateur($cnx, $id) {
		$content = null;
		$likes = null;
		$date = null;
		$selected = null;
		$moderated = null;
		$sql = "SELECT q.content AS content, e.likes AS likes, e.date AS date, e.moderation AS moderated, e.selected AS selected FROM questions AS q, evqust AS e WHERE e.id = ? AND e.id_question = q.id";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $id);
			$stmt->bind_result($content, $likes, $date, $moderated, $selected);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getQuestionModerateur", "dbfunctions.php");
			else {
				$stmt->fetch();

				include('drawQuestionModerateur.php');

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getQuestionModerateur", "dbfunctions.php");
		}

	}

	/*****************
		  Function : getQuestionContent
		  Parameters : 
		    $cnx : DB connection
		    $id : evqust id
		  Output : content question
	*****************/

	function getQuestionContent($cnx, $id) {
		$sql = "SELECT q.content AS content FROM questions AS q, evqust AS e WHERE e.id = ? AND e.id_question = q.id";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("i", $id);
			$stmt->bind_result($content);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getQuestionContent", "dbfunctions.php");
			else {
				$stmt->fetch();

				echo htmlspecialchars($content);

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getQuestionContent", "dbfunctions.php");
		}

	}

	/*****************
		  Function : addQuestion
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : event id
		    $question : question text
		    $source : source
		  Output : 1 or 0 (error)
	*****************/

	function addQuestion($cnx, $user, $event, $question, $source) {
		$sql = "CALL addQuestion(?, ?, ?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("iisi", $user, $event, $question, $source);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "addQuestion", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "addQuestion", "dbfunctions.php");
			echo 0;
		}

	}

	/*****************
		  Function : modifyQuestion
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : event id
		    $evqust : evqust id
		    $question : question text
		  Output : 1 or 0 (error)
	*****************/

	function modifyQuestion($cnx, $user, $event, $evqust, $question) {
		$sql = "CALL modifyQuestion(?, ?, ?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("iiis", $user, $event, $evqust, $question);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "modifyQuestion", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "modifyQuestion", "dbfunctions.php");
			echo 0;
		}

	}

	/*****************
		  Function : addLike
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : event id
		    $evqust : evqust id
		    $likes : nombre de likes à ajouter
		  Output : 1 or 0 (error)
	*****************/

	function addLike($cnx, $user, $event, $evqust, $likes) {
		$sql = "CALL addLikes(?, ?, ?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("iiii", $user, $event, $evqust, $likes);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "addLike", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "addLike", "dbfunctions.php");
			echo 0;
		}

	}

	/*****************
		  Function : applaud
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : event id
		  Output : 1 or 0 (error)
	*****************/

	function applaud($cnx, $user, $event) {
		$sql = "CALL applaud(?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ii", $user, $event);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "applaud", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "applaud", "dbfunctions.php");
			echo 0;
		}

	}

	/*****************
		  Function : closeQuestion
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : evqust id
		  Output : 1 or 0 (error)
	*****************/

	function closeQuestion($cnx, $user, $evqust) {
		$sql = "CALL closeQuestion(?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ii", $evqust, $user);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "closeQuestion", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "closeQuestion", "dbfunctions.php");
			echo 0;
		}

	}

	/*****************
		  Function : moderateQuestion
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : evqust id
		  Output : 1 or 0 (error)
	*****************/

	function moderateQuestion($cnx, $user, $evqust) {
		$sql = "CALL moderateQuestion(?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ii", $evqust, $user);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "moderateQuestion", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "moderateQuestion", "dbfunctions.php");
			echo 0;
		}

	}

	/*****************
		  Function : selectQuestion
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $evqust : evqust id
		  Output : 1 or 0 (error)
	*****************/

	function selectQuestion($cnx, $user, $evqust) {
		$sql = "CALL selectQuestion(?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ii", $evqust, $user);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "selectQuestion", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "selectQuestion", "dbfunctions.php");
			echo 0;
		}

	}
	
	/*****************
		  Function : pushQuestion
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $evqust : evqust id
		  Output : 1 or 0 (error)
	*****************/

	function pushQuestion($cnx, $user, $evqust) {
		$sql = "CALL pushQuestion(?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ii", $evqust, $user);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "pushQuestion", "dbfunctions.php");
				echo 0;
			} else {

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "pushQuestion", "dbfunctions.php");
			echo 0;
		}

	}
	
	/*****************
		  Function : getReview
		  Parameters : 
		    $cnx : DB connection
		    $last_date : previous date
		  Output : JSON output
	*****************/

	function getReview($cnx, $last_date) {
		$sql = "SELECT id_evqust AS id, type, infos, date AS last FROM review WHERE date >= ? ORDER BY last";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("s", $last_date);
			$stmt->bind_result($id, $type, $infos, $last);
			if (!$stmt->execute())
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getReview", "dbfunctions.php");
			else {

				$first = true;
				while($stmt->fetch()) {
					if ($first) $first = false; else echo ", " . PHP_EOL;
					echo "{ \"id\" : " . $id . ", \"type\" : \"" . $type . "\", \"infos\" : " . $infos . ", \"last\" : \"" . $last . "\" }" . PHP_EOL;
				}

			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getReview", "dbfunctions.php");
		}

	}

	/*****************
		  Function : disconnect
		  Parameters : 
		    $cnx : DB connection
		    $user : user id
		    $event : event id
		  Output : 1 or 0 (error)
	*****************/

	function disconnect($cnx, $user, $event) {
		$sql = "CALL disconnect(?, ?)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("ii", $user, $event);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "disconnect", "dbfunctions.php");
				echo 0;
			} else {

				if (array_key_exists('session', $_COOKIE)) {
					$_COOKIE['session'] = "";
				}

				echo 1;
			}
			$stmt->close();
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "disconnect", "dbfunctions.php");
			echo 0;
		}

	}
?>
