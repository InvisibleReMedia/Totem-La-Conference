<?php
	function logging($cnx, $severity, $desc, $trace, $origin) {
		if ($cnx) {
			$sql = "INSERT INTO logs(severity,`desc`,trace,origin) VALUES('" . $severity . "','" . addslashes($desc) . "','" . addslashes($trace) . "','" . $origin . "')";
			$cnx->query($sql);
		}
	}
?>
