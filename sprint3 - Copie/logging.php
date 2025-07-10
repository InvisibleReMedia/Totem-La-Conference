<?php
	function logging($cnx, $severity, $desc, $trace, $origin) {
		if ($cnx) {
			$sql = "INSERT INTO logs(severity,`desc`,trace,origin) VALUES('" . $severity . "','" . $desc . "','" . $trace . "','" . $origin . "')";
			$cnx->query($sql);
		}
	}
?>