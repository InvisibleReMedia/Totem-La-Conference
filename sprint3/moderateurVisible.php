<?php	include('logging.php');
	include('connectodb.php');
	include('getSession.php');
	include('dbfunctions.php');

	$cnx = connect_to_db('admin', true);
	
	if (!getSession($cnx, $session, $user, $event)) {
				header('Location: login.php');
		} else {
	getAuthentName($cnx, $user, $greetings, $firstName, $lastName);

		$sql = "SELECT permissions FROM users WHERE id = " . $user;
			if ($fetch = $cnx->query($sql)) {
					if ($fetch->num_rows > 0) {
							$result = $fetch->fetch_assoc();
							$result = $result['permissions'];

							if (($result & 8) == 0) {
									header('Location: ./restrict.html');
									die('Redirection automatique dans quelques instants');
							}
			
					}
			} else {
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "question.php");
			}
			?><!DOCTYPE html><html lang="fr">
		<head
		><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Modérateur TOTEM La conférence"><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="favicon.png"/><title
			>Messages visibles - Modérateur - TOTEM La conférence</title
		><link href="css/model1.css" rel="stylesheet"/><link rel="stylesheet" href="css/modere-horizontal-c0-320px.css" media="all and (min-width:320px) and (orientation:portrait)"/><link rel="stylesheet" href="css/modere-horizontal-c0-700px.css" media="all and (min-width:700px) and (orientation:portrait)"/><link rel="stylesheet" href="css/modere-horizontal-c0-1000px.css" media="all and (min-width:1000px) and (orientation:portrait)"/><link rel="stylesheet" href="css/modere-vertical-c0-320px.css" media="all and (min-width:320px) and (orientation:landscape)"/><link rel="stylesheet" href="css/modere-vertical-c0-700px.css" media="all and (min-width:700px) and (orientation:landscape)"/><link rel="stylesheet" href="css/modere-vertical-c0-1000px.css" media="all and (min-width:1000px) and (orientation:landscape)"/><style
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
				.question {
					display:inline-block;
					background-color:white;
					border:1px solid blue;
					border-radius:15px;
					float:right;
					width:90%;
					margin-top:5px;
					margin-bottom:5px;
					padding-top:8px;
					padding-bottom:8px;
					padding-left:8px;
					padding-right:7px;
					box-shadow:1px 3px 3px 0px black;
					overflow:hidden;
					font-family:Open-Sans;
					font-size:1.4em;
					color:black;
					transition:opacity 1s;
					opacity:1;
					cursor:pointer;
				}
				.question1 {
					display:inline-block;
					background-color:white;
					border:1px solid blue;
					border-radius:15px;
					margin-right:30px;
					float:left;
					width:90%;
					margin-top:5px;
					margin-bottom:5px;
					padding-top:8px;
					padding-bottom:8px;
					padding-left:8px;
					padding-right:7px;
					box-shadow:1px 3px 3px 0px black;
					overflow:hidden;
					font-family:Open-Sans;
					font-size:1.4em;
					color:black;
					transition:opacity 1s;
					opacity:1;
					cursor:pointer;
				}
				.response {
					border:2px outset rgba(200,107,65,1.0);
					background-color:white;
					color:rgba(200,107,65,1.0);
					border-radius:5px;
					margin:2px;
					cursor:pointer;
				}
				.response:hover {
			     	border:2px outset red;
				}
				.response:active {
					border:2px inset red;
					background-color:rgba(200,107,65,1.0);
					color:white;
				}
				.move {
					opacity:0;
				}
				input[type=button] {
					border-radius:15px;
					background-color:white;
					color:black;
					font-family:Open-Sans;
					font-size:1em;
					cursor:pointer;
				}
				input[type=button]:focus {
					outline-style:none;
					box-shadow:0px 3px 3px 0px black;
				}
				.heart {
					padding-left:2px;
					padding-right:2px;
					height:1.4em;
					cursor:pointer;
				}
				.heart:hover {
					height:1.5em;
				}
				.heart:focus {
					outline-style:none;
				}
				.trash {
					height:1.4em;
					cursor:pointer;
				}
				.trash:hover {
					height:1.5em;
				}
				.trash:focus {
					outline-style:none;
				}
				.modify {
					height:1.4em;
					cursor:pointer;
				}
				.modify:hover {
					height:1.5em;
				}
				.modify:focus {
					outline-style:none;
				}
				.push {
					height:1.4em;
					cursor:pointer;
				}
				.push:hover {
					height:1.5em;
				}
				.push:focus {
					outline-style:none;
				}
				.selected {
					background-color:rgba(20, 134, 255,0.7);
					color:black;
				}
				</style>
		</head
	>
		<body>
		<div
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
	>
		<div
		class="vbox-c"
		><div
			class="vbox-element-d"
			><div
				class="hbox-e"
				><div
					class="hbox-element-f"
					><div
						class="vbox-g"
						><div
							class="vbox-element-h"
							>&nbsp;</div
						><div
							class="vbox-element-i hideSB"
							><div
								class="vbox-a"
								id="messagesArea"
								><div
									class="vbox-element-b"
									><div
										style="display:inline-flex;height:30px;width:100%;width:320px"
										><div
											style="width:30%"
											><input
												type="button"
												value="Visible"
												onclick="document.location = './moderateurVisible.php'"
												></div
											><div
												style="width:40%"
												><input
													type="button"
													value="Modéré"
													onclick="document.location = './moderateurHidden.php'"
													></div
												><div
													style="width:30%"
													><input type="button" value="Retour" onclick="document.location = 'menu.php'"/></div
												></div
											><div
												style="display:inline-flex;height:30px;width:100%;width:320px"
												><div
													style="width:30%"
													><input type="button" value="Valider" onclick="validateModeration()"/></div
												><div
													style="width:40%"
													><input type="button" value="Tout cocher" onclick="moderateAll(1)"/></div
												><div
													style="width:30%"
													><input type="button" value="Tout décocher" onclick="moderateAll(0)"/></div
												></div
											><div
												style="display:inline-flex;height:100%;width:100%;width:auto;height:auto"
												><div
													style="width:1%"
													>&nbsp;</div
												><div
													style="width:98%;width:auto;height:auto"
													><div
														id="questions"
														><?php $sql = "SELECT e.id AS id, q.content AS content, e.likes AS likes, e.moderation AS moderated, e.selected AS selected, e.source AS source, e.id_response AS myResponse, e.responseNumber AS responseNumber, (SELECT r.id FROM questionresponses AS r WHERE r.question = e.id_question AND r.id_event = e.id_event) AS response, (SELECT q1.response1 FROM questionresponses AS q1 WHERE q1.question = e.id_question AND q1.id_event = e.id_event) AS response1, (SELECT q2.response2 FROM questionresponses AS q2 WHERE q2.question = e.id_question AND q2.id_event = e.id_event) AS response2, (SELECT q3.response3 FROM questionresponses AS q3 WHERE q3.question = e.id_question AND q3.id_event = e.id_event) AS response3, NOW() AS date FROM questions AS q, evqust AS e WHERE e.id_event = " . $event . " AND e.id_question = q.id AND e.suppressed = 0 AND e.moderation = 1 ORDER BY e.source DESC, e.likes DESC";
																if ($fetch = $cnx->query($sql)) {
																		if (($count_rows = $fetch->num_rows) > 0) {
																				$num_line = 1;

																				while($row = $fetch->fetch_assoc()) {
					
																			$content = $row['content'];
																			$id = $row['id'];
																			$likes = $row['likes'];
																			$moderated = $row['moderated'];
																			$selected = $row['selected'];
																			$questionSource = $row['source'];
																			$myResponse = $row['myResponse'];
																			$responseNumber = $row['responseNumber'];
																			$response = $row['response'];
																			$response1 = $row['response1'];
																			$response2 = $row['response2'];
																			$response3 = $row['response3'];
																			include('drawQuestionModerateur.php');
																			if ($num_line == $count_rows) {
																					$last_date = $row['date'];
																			}
																			++$num_line;
	
																				}
				
																		} else {
				
																		$sql = "SELECT NOW() AS date";
																		if ($result = $cnx->query($sql)) {
																				$row = $result->fetch_assoc();
																				$last_date = $row['date'];
																		}
	
																		}
																} else {
																		logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "question.php");
																}
																?></div
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
					></div
				></div
			><script
				 defer
				language="JavaScript"
				>

				function load() {
						setInterval(async function(e) { await getReview() }, 2000)
					setInterval(sendLikes, 3000)
		
				}


				function removeQuestion(id) {
					fetch('./removeQuestion.php?session=<?php echo $session ?>&id=' + id)
				}

				function validateModeration() {
					let l = document.getElementsByName('moderated')
					for(const q of l) {
						if (q.checked) {
							moderateQuestion(q.dataset.evqust)
						}
					}
				}

				function moderateAll(value) {
					let l = document.getElementsByName('moderated')
					for(const q of l) {
						q.checked = value
					}
				}

				function moderateQuestion(id) {
					fetch('./moderateQuestion.php?session=<?php echo $session ?>&id=' + id)
				}

				function pushQuestion(id) {
					fetch('./pushQuestion.php?session=<?php echo $session ?>&id=' + id)
				}

				function modifyQuestion(id) {
					let x = document.getElementsByName('questionContent')
					x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == id)
					if (x.length == 1) {
						let d = x[0]
						let data = new FormData()
						// si il y a du texte dans la question...
						if (d.innerText.length > 1) {
				 			data.append("id", id)
				 			data.append("question", d.innerText)
							fetch('./modifyQuestion.php?session=<?php echo $session ?>', {
								method : 'POST',
								body : data
							}).catch(error => console.log(error))
						}
					}
				}
				 var likes = []

				function sendLikes() {

					for(const u of likes) {
						fetch('./addLikes.php?session=<?php echo $session?>&id=' + u.id + '&likes=' + u.likes)
					}
					likes = []
				}
				function addLikes(id) {
					let v = likes.find(a => a.id == id)
					if (v) {
				 v.likes = v.likes + 1
					} else {
				 likes.push({ "id" : id, "likes" : 1 })
					}
				}

				function sendResponse(id, resp, num, source) {
					let data = new FormData()
					data.append("question", id)
					data.append("id_response", resp)
					data.append("responseNumber",num)
					data.append("source", source)
					fetch('./answerQuestion.php?session=<?php echo $session ?>', {
						method : 'POST',
						body : data
					}).catch(error => console.log(error))
				}




				var sortingTimer = false
				function moveUp() {
				 	if (sortingTimer) {
				 		sortingTimer = false
						let redo = false
						let counter = 0
						let l = document.getElementsByName("likes")
						let previous = l[0];
						for(let index = 1; index < l.length; ++index) {
							if (parseInt(previous.innerText) < parseInt(l[index].innerText)) {
								redo = true
								++counter
								this.current = l[index].parentNode
								this.previous = previous
								this.value = parseInt(this.previous.innerText)
								if (!this.current.classList.contains("move")) {
									this.current.classList.add("move")
									setTimeout(function(e) {
										// ensures that previous element has not been removed
										if (this.previous.parentNode) {
											// ensure you don't up the previous itself
											if (parseInt(this.previous.innerText) == this.value) {
												let parent = this.current.parentNode
												parent.removeChild(this.current)
												parent.insertBefore(this.current, this.previous.parentNode)
												this.current.classList.remove("move")
											} else {
												this.current.classList.remove("move")
											}
										} else {
											this.current.classList.remove("move")
										}
									}, 500)
								} else {
									this.current.classList.remove("move")
								}
							}
							previous = l[index]
						}
						if (redo) {
							sortingTimer = setTimeout(function(e) {
								moveUp()
							}, counter * 500)
						} else {
							// display all elements
							for(const x of l) {
								x.parentNode.classList.remove("move")
							}
						}
					} else {
						setTimeout(function(e) {
							moveUp()
						}, 1000)
					}
				}

				function sorting() {
					if (!sortingTimer)
						sortingTimer = setTimeout(function(e) {
							moveUp()
						}, 500)
				}

				var last_date = "<?php echo $last_date ?>"
				async function getReview() {
					await fetch('review.php?session=<?php echo $session ?>&last_date=' + last_date)
						.then(res => res.json())
						.then(json => processReview(json))
						.catch(error => console.error(error))
				}

				async function processReview(json) {
					for(const u of json) {
						if (u.type == "QUESTION") {
							await fetch('./getQuestionModerateur.php?session=<?php echo $session?>&id=' + u.id)
								.then(res => res.text())
								.then(text => {
									let x = document.getElementsByName('aQuestion')
									x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == u.id)
									if (x.length == 0) {
										let d = document.getElementById("questions")
										d.insertAdjacentHTML('beforeEnd', text)
									}
								})
						} else if (u.type == "LIKES") {
							let x = document.getElementsByName('likes');
							let evqust = u.id;
							x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == evqust);
							if (x.length > 0) {
								if (parseInt(x[0].innerText) < u.infos.totalLikes) {
									x[0].innerText = u.infos.totalLikes;
									sorting()
								}
							}
						} else if (u.type == "SUPPRESSED") {
							let x = document.getElementsByName('aQuestion');
							let evqust = u.id;
							x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == evqust);
							if (x.length > 0)
								x[0].parentNode.removeChild(x[0])
						} else if (u.type == "MODERATED") {
							let x = document.getElementsByName('aQuestion');
							let evqust = u.id;
							x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == evqust);
							if (x.length > 0) {
								let l = x[0].childNodes[1]
								if (u.infos.moderated == 0) {
									l.innerText = "HIDDEN"
								} else {
									l.innerText = "VISIBLE"
								}
							}
						} else if (u.type == "SELECT") {
							let x = document.getElementsByName('aQuestion');
							let evqust = u.id;
							x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == evqust);
							if (x.length > 0) {
								if (u.infos.selected == 0) {
									if (x[0].classList.contains('selected'))
										x[0].classList.remove('selected')
								} else {
									if (!x[0].classList.contains('selected'))
										x[0].classList.add('selected')
								}
							}
						} else if (u.type == "UPDATE") {
							let x = document.getElementsByName('questionContent');
							let evqust = u.id;
							x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == evqust);
							if (x.length > 0) {
								await fetch('./getQuestionContent.php?session=<?php echo $session?>&id=' + u.id)
								.then(res => res.text())
								.then(text => {
									x[0].innerText = text
								})
							}
						}
						last_date = u.last;
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
	>
		<?php
		
}

	close_db($cnx);
?>

