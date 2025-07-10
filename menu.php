<?php
include('mongodb.php');
include('connectodb.php');// connection method to db is not browsable
include('getsession.php');
include('dbfunctions.php');
$cnx = connect_to_db('admin', true);
if (!getSession($cnx, $user, $event)) {
	header('Location: ./login.php');
}
getAuthentName($cnx, $user, $greetings, $firstName, $lastName);


$roles = [];
$sql = "SELECT permissions FROM users WHERE id = " . $user;
if ($fetch = $cnx->query($sql)) {
	if ($fetch->num_rows > 0) {
		$result = $fetch->fetch_assoc();
		$result = $result['permissions'];

		if (($result & 1) == 1) {
			$roles[] = "participant";
		}

		if (($result & 2) == 2) {
			$roles[] = "animateur";
		}

		if (($result & 4) == 4) {
			$roles[] = "client";
		}

		if (($result & 8) == 8) {
			$roles[] = "moderateur";
		}

		if (($result & 16) == 16) {
			$roles[] = "admin";
		}

	}
	} else {
		logging("ERROR", "error query - reason:" . $cnx->error, "", "");
	}

	if (sizeof($roles) == 1) {

		if ($roles[0] == "participant") {
			header('Location: ./question.php');
		}

		if ($roles[0] == "animateur") {
			header('Location: ./animateur.php');
		}

		if ($roles[0] == "client") {
			header('Location: ./client.php');
		}

		if ($roles[0] == "moderateur") {
			header('Location: ./moderateur.php');
		}

		if ($roles[0] == "admin") {
			header('Location: ./admin/');
		}

	}

	?><!DOCTYPE html><html
		lang="fr"
		><head
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Menu TOTEM La conférence"><meta name="keywords" content="IHACOM TOTEM La conférence"><meta name="generator" content="aloha - a programming language"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" href="favicon.ico"/><title
				>Menu - TOTEM La conférence</title
			><link href="css/model2.css" rel="stylesheet"/><style
				>
				.connectionName {
				font-family:Open-Sans;
				font-size:0.5em;
				padding-right:30px;
				vertical-align: bottom;
				}
				#popup {
				position:absolute;
				top:0px;
				left:0px;
				width:100%;
				min-width:360px;
				z-index:1;
				background-color:white;
				padding-bottom:3px;
				border-bottom:1px solid black;
				}
				button {
				font-family:Open-Sans;
				font-size:0.5em;
				background-color:yellow;
				border-radius:15px;
				color:black;
				cursor:pointer;
				}
				</style
			><style
				>
				input[type=button] {
				border-radius:15px;
				background-color:yellow;
				color:black;
				width:100%;
				margin-top:10px;
				margin-bottom:10px;
				font-family:Open-Sans;
				cursor:pointer;
				}</style
			></head
		><body
			><div
				id="popup"
				><div
					class="flex-row"
					style="width:300px;height:100%;float:right"
					><div
						style="height:100%"
						><div
							style="display:inline-flex;height:100%;width:100%"
							><div
								style="width:60%;text-align:right"
								><span
									class="connectionName"
									><?php echo $greetings . " " . $firstName . " " . $lastName?></span
								></div
							><div
								style="width:40%"
								><button
									id="disconnect"
									>Deconnexion</button
								></div
							></div
						></div
					></div
				></div
			><header
				style="display:inline-flex;height:250px;width:100%;border-bottom:1px solid white;min-width:230px;margin-bottom:20px"
				><div
					style="width:30%"
					><img src="images/totem-stand-04.jpeg" alt="TOTEM - La conférence" title="TOTEM - La conférence"/></div
				><div
					style="width:70%;text-align:right"
					><span
						class="title"
						>Menu</span
					></div
				></header
			><div
				class="flex-row"
				style="width:300px;height:100%;margin:0 auto"
				><div
					style="height:100%"
					><?php
					$pos = array_search("participant", $roles);
					if ($pos !== false) {
						?><input type="button" onclick="document.location='./question.php'" value="Aller à la page participant"/><?php
					}
					?><?php
					$pos = array_search("animateur", $roles);
					if ($pos !== false) {
						?><input type="button" onclick="document.location='./animateur.php'" value="Aller à la page animateur"/><?php
					}
					?><?php
					$pos = array_search("client", $roles);
					if ($pos !== false) {
						?><input type="button" onclick="document.location='./client.php'" value="Aller à la page client"/><?php
					}
					?><?php
					$pos = array_search("moderateur", $roles);
					if ($pos !== false) {
						?><input type="button" onclick="document.location='./moderateur.php'" value="Aller à la page moderateur"/><?php
					}
					?><?php
					$pos = array_search("admin", $roles);
					if ($pos !== false) {
						?><input type="button" onclick="document.location='./admin/'" value="Aller à la page admin"/><?php
					}
					?></div
				></div
			><script
				language="JavaScript"
				>
				let d = document.getElementById("disconnect")
				d.addEventListener('click', function(e) { disconnect() })
				function disconnect() {
				fetch('./disconnect.php')
				.then(res => res.text())
				.then(text => {
				if (text == 1) {
				document.cookie = ""
				}
				})
				}</script
			></body
		></html
	><?php

	?>