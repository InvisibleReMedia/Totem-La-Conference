<?php

	use MongoDB\Client as Mongo;


	function logging($severity, $desc, $trace, $origin) {


		try {
			$client = new Mongo('mongodb://127.0.0.1:27017');
			$coll = $client->logging->logs;
		} catch(MongoDB\Driver\Exception\ConnectionTimeoutException $e) {

		}


		$result = $coll->insertOne( [ 'severity' => $severity,
		                              'desc' => $desc,
		                              'trace' => $trace,
		                              'origin' => $origin ]);





	}
?>
