<?php	include('logging.php');
	include('connectodb.php');
	include('getSession.php');
	include('dbfunctions.php');

	$cnx = connect_to_db('admin', true);
	
	if (!getSession($cnx, $session, $user, $event)) {
				header('Location: login.php');
		} else {
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
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "menu.php");
			}
		
			if (sizeof($roles) == 1) {
			
					if ($roles[0] == "participant") {
							header('Location: participant/discernement.php');
							die('Redirection automatique dans quelques instants');
					}
		
					if ($roles[0] == "animateur") {
							header('Location: ./source.php');
							die('Redirection automatique dans quelques instants');
					}
		
					if ($roles[0] == "client") {
							header('Location: client/discernement.php');
							die('Redirection automatique dans quelques instants');
					}
		
					if ($roles[0] == "moderateur") {
							header('Location: ./moderateurVisible.php');
							die('Redirection automatique dans quelques instants');
					}
		
					if ($roles[0] == "admin") {
							header('Location: ./admin/');
							die('Redirection automatique dans quelques instants');
					}
		
			}
		?><!DOCTYPE html><html
		lang="fr"
		><head
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Menu Conférence HANDIGO"><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="favicon.png"/><title
				>Menu - Conférence HANDIGO</title
			><link href="css/model2.css" rel="stylesheet"/><link rel="stylesheet" href="css/common-horizontal-c0-300px.css" media="all and (min-width:300px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-horizontal-c0-700px.css" media="all and (min-width:700px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-horizontal-c0-1000px.css" media="all and (min-width:1000px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-vertical-c0-300px.css" media="all and (min-width:300px) and (orientation:landscape)"/><link rel="stylesheet" href="css/common-vertical-c0-700px.css" media="all and (min-width:700px) and (orientation:landscape)"/><link rel="stylesheet" href="css/common-vertical-c0-1000px.css" media="all and (min-width:1000px) and (orientation:landscape)"/><style
				>
					.connectionName {
						font-family:Open-Sans;
						font-size:1em;
						padding-right:30px;
						vertical-align: bottom;
					}
					#popup {
						position:absolute;
						top:0px;
						left:0px;
						width:98vw;
						min-width:360px;
						z-index:1;
						background-color:white;
						padding-bottom:3px;
						border-bottom:1px solid black;
					}
					button {
						font-family:Open-Sans;
						font-size:1em;
						padding:2px;
						background-color:white;
						border-radius:15px;
						color:black;
						cursor:pointer;
					}
					button:focus {
						outline-style:none;
						box-shadow:none;
						border-color:red;
					}
					#disconnect {
						font-family: Open-Sans;
						font-size: 0.5em;
						background-color: yellow;
						border-radius: 15px;
						color: black;
						cursor: pointer;
					}
				</style>
			<style
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
					}</style>
			</head
		><body
			><div
				class="flex-row"
				style="width:100px;height:100%;position:absolute;top:0px;left:0px;z-index:1;clip-path: polygon(100% 0, 0 0, 0 100%);-webkit-clip-path: polygon(100% 0, 0 0, 0 100%);height:100px"
				><div
					style="height:100%;background-color:rgba(77, 51, 122,0.6);border:2px solid rgba(255,255,255,0.3);width:100%;height:100%"
					>&nbsp;</div
				></div
			><div
				id="popup"
				><div
					class="flex-row"
					style="width:100%;height:100%;float:right"
					><div
						style="height:100%"
						><div
							style="display:inline-flex;height:100%;width:100%"
							><div
								style="width:90%;text-align:right"
								><span
									class="connectionName"
									><?php echo $greetings . " " . $firstName . " " . $lastName?></span
								></div
							><div
								style="width:10%"
								><button
									id="disconnect"
									style="float:right"
									>Deconnexion</button
								></div
							></div
						></div
					></div
				></div
			><div
				style="height:50px"
				>&nbsp;</div
			><div
				class="vbox-a"
				><div
					class="vbox-element-b"
					>&nbsp;</div
				><div
					class="vbox-element-c hideSB"
					><header
						style="display:inline-flex;height:30px;width:100%;display:inline-block;border-bottom:1px solid black;margin-bottom:20px"
						><div
							style="width:100%"
							><span
								class="title"
								>Menu</span
							></div
						></header
					><div
						class="flex-row"
						style="width:290px;height:100%;max-height:210px;margin:0 auto"
						><div
							style="height:100%"
							><?php
										$pos = array_search("participant", $roles);
										if ($pos !== false) {
												?><input type="button" onclick="document.location='participant/discernement.php'" value="Aller à la page participant"/><?php
										}
									?><?php
										$pos = array_search("animateur", $roles);
										if ($pos !== false) {
												?><input type="button" onclick="document.location='./source.php'" value="Aller à la page animateur"/><?php
										}
									?><?php
										$pos = array_search("client", $roles);
										if ($pos !== false) {
												?><input type="button" onclick="document.location='client/discernement.php'" value="Aller à la page client"/><?php
										}
									?><?php
										$pos = array_search("moderateur", $roles);
										if ($pos !== false) {
												?><input type="button" onclick="document.location='./moderateurVisible.php'" value="Aller à la page moderateur"/><?php
										}
									?><?php
										$pos = array_search("admin", $roles);
										if ($pos !== false) {
												?><input type="button" onclick="document.location='./admin/'" value="Aller à la page admin"/><?php
										}
									?></div
						></div
					></div
				></div
			><div
				class="flex-row"
				style="width:100px;height:100%;float:right;z-index:1;clip-path: polygon(100% 0, 100% 100%, 0 100%);-webkit-clip-path: polygon(100% 0, 100% 100%, 0 100%);height:100px"
				><div
					style="height:100%;background-color:rgba(77, 51, 122,0.6);border:2px solid rgba(255,255,255,0.3);width:100%;height:100%"
					>&nbsp;</div
				></div
			><script
				language="JavaScript"
				>
				let d = document.getElementById("disconnect")
				d.addEventListener('click', function(e) { disconnect() })
				function disconnect() {
					fetch('disconnect.php?session=<?php echo $session?>')
						.then(res => res.text())
						.then(text => {
							if (text == 1) {
								document.cookie = ""
							}
						})
				}
				function testSession() {
					fetch('testSession.php?session=<?php echo $session?>')
						.then(res => res.text())
						.then(text => {
							if (text == 0) {
								document.location = "logout.html"
							}
						})
				}

						setInterval(testSession, 1500)
				</script>
			</body
		></html
	><?php
		
}

	close_db($cnx);
?>
