<?php
	function getSession($cnx, &$session, &$user, &$event) {
		// get the cookie
		if (array_key_exists('session', $_COOKIE)) {
			$session = $_COOKIE['session'];
		} else {
			if (array_key_exists('session', $_GET)) {
				$session = $_GET['session'];
			} else {
				return false;
			}
		}
		// read session table
		$sql = "SELECT id_user, id_event FROM sessions WHERE session = ? AND ISNULL(date_end)";
		if ($stmt = $cnx->prepare($sql)) {
			$stmt->bind_param("s", $session);
			$stmt->bind_result($user, $event);
			if (!$stmt->execute()) {
				logging($cnx, "ERROR", "execute failed - reason:" . $stmt->error, "getSession", "getSession.php");
				return false;
			} else {
				if ($stmt->fetch()) {
					$result = true;

				} else
					$result = false;
			}
			$stmt->close();
			return $result;
		} else {
			logging($cnx, "ERROR", "error stmt - reason:" . $cnx->error, "getSession", "getSession.php");
			return false;
		}

	}
?>