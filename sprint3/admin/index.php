<?php	include('../logging.php');
	include('../connectodb.php');
	include('../getSession.php');
	include('../dbfunctions.php');

	$cnx = connect_to_db('admin', true);
	
	if (!getSession($cnx, $session, $user, $event)) {
				header('Location: ../login.php');
		} else {
	getAuthentName($cnx, $user, $greetings, $firstName, $lastName);

		$sql = "SELECT permissions FROM users WHERE id = " . $user;
			if ($fetch = $cnx->query($sql)) {
					if ($fetch->num_rows > 0) {
							$result = $fetch->fetch_assoc();
							$result = $result['permissions'];

							if (($result & 16) == 0) {
									header('Location: ../restrict.html');
									die('Redirection automatique dans quelques instants');
							}
			
					}
			} else {
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "question.php");
			}
			?><!DOCTYPE html><html
		lang="fr"
		><head
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Menu - Administration - TOTEM La conférence"><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="../fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="../favicon.png"/><title
				>Menu - Administration - TOTEM La conférence</title
			><link href="../css/model2.css" rel="stylesheet"/><link rel="stylesheet" href="../css/common-horizontal-c0-320px.css" media="all and (min-width:320px) and (orientation:landscape)"/><link rel="stylesheet" href="../css/common-horizontal-c0-700px.css" media="all and (min-width:700px) and (orientation:landscape)"/><link rel="stylesheet" href="../css/common-horizontal-c0-1000px.css" media="all and (min-width:1000px) and (orientation:landscape)"/><link rel="stylesheet" href="../css/common-vertical-c0-320px.css" media="all and (min-width:320px) and (orientation:portrait)"/><link rel="stylesheet" href="../css/common-vertical-c0-700px.css" media="all and (min-width:700px) and (orientation:portrait)"/><link rel="stylesheet" href="../css/common-vertical-c0-1000px.css" media="all and (min-width:1000px) and (orientation:portrait)"/><style
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
						width:95vw;
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
			><div
				style="height:50px"
				>&nbsp;</div
			><div
				class="vbox-a"
				><div
					class="vbox-element-b"
					><div
						class="hbox-c"
						><div
							class="hbox-element-d"
							><div
								class="vbox-e"
								><div
									class="vbox-element-f"
									>&nbsp;</div
								><div
									class="vbox-element-g hideSB"
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
											><input type="button" onclick="document.location='./users.php'" value="Aller à la page users"/><input type="button" onclick="document.location='./events.php'" value="Aller à la page events"/><input type="button" onclick="document.location='./evqust.php'" value="Aller à la page evqust"/><input type="button" onclick="document.location='./likes.php'" value="Aller à la page likes"/><input type="button" onclick="document.location='./questions.php'" value="Aller à la page questions"/><input type="button" onclick="document.location='./logs.php'" value="Aller à la page logs"/><input type="button" onclick="document.location='./review.php'" value="Aller à la page review"/><input type="button" onclick="document.location='./sessions.php'" value="Aller à la page sessions"/><input type="button" onclick="document.location='./questions-evqust.php'" value="Aller à la page questions et evqust"/><input type="button" onclick="document.location='../menu.php'" value="Aller à la page Retour"/></div
										></div
									></div
								></div
							></div
						></div
					></div
				></div
			><script
				language="JavaScript"
				>
				let d = document.getElementById("disconnect")
				d.addEventListener('click', function(e) { disconnect() })
				function disconnect() {
					fetch('../disconnect.php?session=<?php echo $session?>')
						.then(res => res.text())
						.then(text => {
							if (text == 1) {
								document.cookie = ""
							}
						})
				}
				function testSession() {
					fetch('../testSession.php?session=<?php echo $session?>')
						.then(res => res.text())
						.then(text => {
							if (text == 0) {
								document.location = "../logout.html"
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