<?php	include('../logging.php');
	include('../connectodb.php');
	include('../getSession.php');
	include('../dbfunctions.php');

	$cnx = connect_to_db('admin', true);
	
	if (!getSession($cnx, $session, $user, $event)) {
				header('Location: ../login.php');
		} else {
	getAuthentName($cnx, $user, $greetings, $firstName, $lastName);

		$sql = "SELECT JSON_EXTRACT(e.infos, \"$.pages[7]\") AS numPage FROM events AS e WHERE e.id = " . $event;
			if ($fetch = $cnx->query($sql)) {
					if ($fetch->num_rows > 0) {
							$result = $fetch->fetch_assoc();
				
				$relatedEvent = $result['numPage'];

					}
			} else {
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "events", "fin.php");
			}
			$sql = "SELECT permissions FROM users WHERE id = " . $user;
			if ($fetch = $cnx->query($sql)) {
					if ($fetch->num_rows > 0) {
							$result = $fetch->fetch_assoc();
							$result = $result['permissions'];

							if (($result & 4) == 0) {
									header('Location: ./restrict.html');
									die('Redirection automatique dans quelques instants');
							}
			
					}
			} else {
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "question.php");
			}
			?><!DOCTYPE html><html
		lang="fr"
		><head
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Web application pour une interactivité à fort impact sociétal. Notre objectif est de répondre à vos besoins en interactivité digitale et contribuer à votre politique RSE en matière d'inclusion des personnes en situation de handicap."><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="../fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="../favicon.png"/><title
				>Le mot de la fin - Conférence HANDIGO</title
			><link href="../css/model2.css" rel="stylesheet"/><link rel="stylesheet" href="../css/common-horizontal-c0-300px.css" media="all and (min-width:300px) and (orientation:portrait)"/><link rel="stylesheet" href="../css/common-horizontal-c0-700px.css" media="all and (min-width:700px) and (orientation:portrait)"/><link rel="stylesheet" href="../css/common-horizontal-c0-1000px.css" media="all and (min-width:1000px) and (orientation:portrait)"/><link rel="stylesheet" href="../css/common-vertical-c0-300px.css" media="all and (min-width:300px) and (orientation:landscape)"/><link rel="stylesheet" href="../css/common-vertical-c0-700px.css" media="all and (min-width:700px) and (orientation:landscape)"/><link rel="stylesheet" href="../css/common-vertical-c0-1000px.css" media="all and (min-width:1000px) and (orientation:landscape)"/><style
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
					textarea {
						box-sizing:border-box;
						resize:none;
						border-radius:15px;
						background-color:white;
						color:black;
						width:100%;
						height:50px;
						font-family:Open-Sans;
						font-size:1em;
						overflow:hidden;
					}
					textarea:focus {
						outline-style: none;
						box-shadow:0px 3px 3px 0px black;
					}
					</style>
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
					><div
						style="height:20px"
						>&nbsp;</div
					><div
						style="display:inline-flex;height:50px;width:100%;padding-top:20px"
						><div
							style="width:2%"
							>&nbsp;</div
						><div
							style="width:12%"
							><a
								title="Discernement"
								href="discernement.php"
								><div
									class="cinematic"
									>1</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Témoignages"
								href="temoignages.php"
								><div
									class="cinematic"
									>2</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Expériences"
								href="experiences.php"
								><div
									class="cinematic"
									>3</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Connaissances"
								href="connaissances.php"
								><div
									class="cinematic"
									>4</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Rêver"
								href="rever.php"
								><div
									class="cinematic"
									>5</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Feuille de route"
								href="route.php"
								><div
									class="cinematic"
									>6</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Gagne à être connu"
								href="gagne.php"
								><div
									class="cinematic"
									>7</div
								></a
							></div
						><div
							style="width:12%"
							><a
								title="Mot de la fin"
								href="fin.php"
								><div
									class="cinematic"
									>8</div
								></a
							></div
						><div
							style="width:2%"
							>&nbsp;</div
						></div
					><div
						style="height:40px"
						>&nbsp;</div
					><header
						style="display:inline-flex;height:30px;width:100%;display:inline-block;border-bottom:1px solid black;margin-bottom:20px"
						><div
							style="width:100%"
							><span
								class="title"
								>Le mot de la fin</span
							></div
						></header
					><div
						style="border-left:2px solid blue;width:100%;padding:10px;font-family:Open-Sans;font-size:14pt;line-height:20pt;margin-bottom:30px"
						><span
							style="font-size:larger"
							><strong
								>Qui est le plus handicapé ?</strong
							></span
						><br/><span
							style="height:10pt"
							></span
						><br/>Celui qui l’ignore ! Et celui qui n’en tient pas compte !  <br/><span
							style="height:10pt"
							></span
						><br/><span
							style="background-color:blue;color:white"
							>Quel est le mot que vous retenez et partagez-le à l'ensemble du groupe ?</span
						></div
					><div
						class="flex-row"
						style="width:320px;height:100%;height:311px;margin:0 auto"
						><div
							style="height:100%"
							><div
								class="flex-row"
								style="width:200px;height:100%;margin:0 auto"
								><div
									style="height:100%;margin-top:10px"
									><div
										style="display:inline-flex;height:275px;width:100%;width:200px;margin:0 auto"
										><div
											style="width:1%"
											>&nbsp;</div
										><div
											style="width:98%"
											><div
												class="flex-row"
												style="width:100%;height:100%"
												><div
													style="height:20%"
													><textarea
														id="textArea"
														maxlength="160"
														placeholder="Saisissez votre texte..."
														></textarea
													></div
												><div
													style="height:20%"
													><textarea
														id="textAreaResponse1"
														maxlength="160"
														placeholder="Saisissez votre réponse 1..."
														></textarea
													></div
												><div
													style="height:20%"
													><textarea
														id="textAreaResponse2"
														maxlength="160"
														placeholder="Saisissez votre réponse 2..."
														></textarea
													></div
												><div
													style="height:20%"
													><textarea
														id="textAreaResponse3"
														maxlength="160"
														placeholder="Saisissez votre réponse 3..."
														></textarea
													></div
												><div
													style="height:20%;float:right"
													><input type="button" id="send" value="Envoyer" onclick="sendQuestionResponse('#textArea', '#textAreaResponse1',  '#textAreaResponse2', '#textAreaResponse3')"/></div
												></div
											></div
										><div
											style="width:1%"
											>&nbsp;</div
										></div
									></div
								></div
							></div
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
				 defer
				language="JavaScript"
				>

				function load() {
	
				}


				function sendQuestion(name) {
					let d = document.querySelector(name)
					let data = new FormData()
					// si il y a du texte dans la question...
					if (d.value.length > 1) {
						data.append("question", d.value)
						data.append("source", 1)
						fetch('./addQuestion.php?session=<?php echo $session ?>', {
							method : 'POST',
							body : data
						}).then(res => res.text())
							.then(text => {
								d.value = ""
						}).catch(error => console.log(error))
					}
				}
				function sendQuestionResponse(name, r1, r2, r3) {
					let d = document.querySelector(name)
					let d1 = document.querySelector(r1)
					let d2 = document.querySelector(r2)
					let d3 = document.querySelector(r3)
					let data = new FormData()
					// si il y a du texte dans la question...
					if (d.value.length > 1) {
						data.append("question", d.value)
						data.append("response1", d1.value)
						data.append("response2", d2.value)
						data.append("response3", d3.value)
						data.append("source", 1)
						fetch('./addQuestionResponse.php?session=<?php echo $session ?>', {
							method : 'POST',
							body : data
						}).then(res => res.text())
							.then(text => {
								d.value = ""
								d1.value = ""
								d2.value = ""
								d3.value = ""
						}).catch(error => console.log(error))
					}
				}



				load()

				</script>
			<script
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
