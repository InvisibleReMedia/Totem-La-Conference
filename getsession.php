<?php
	function getSession($cnx, &$user, &$event) {
		// get the cookie
		if (array_key_exists('session', $_COOKIE)) {
			$session = $_COOKIE['session'];
		} else {
			return false;
		}
		// read session table
		if ($stmt = $cnx->prepare("SELECT id_user, id_event FROM sessions WHERE session = ? AND ISNULL(date_end)")) {
			$stmt->bind_param("s", $session);
			// test session
			$stmt->execute();
			$stmt->bind_result($user, $event);
			if ($stmt->fetch())
				return true;
			else
				return false;
		}
	}
?>