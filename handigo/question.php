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

							if (($result & 1) == 0) {
									header('Location: ./restrict.html');
									die('Redirection automatique dans quelques instants');
							}
			
					}
			} else {
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "question.php");
			}
			?><!DOCTYPE html><html lang="fr">
		<head
		><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Web application pour une interactivité à fort impact sociétal. Notre objectif est de répondre à vos besoins en interactivité digitale et contribuer à votre politique RSE en matière d'inclusion des personnes en situation de handicap."><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="favicon.png"/><title
			>Bienvenue à la conférence HANDIGO</title
		><link href="css/model2.css" rel="stylesheet"/><link rel="stylesheet" href="css/common-horizontal-c0-320px.css" media="all and (min-width:320px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-horizontal-c0-700px.css" media="all and (min-width:700px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-horizontal-c0-1000px.css" media="all and (min-width:1000px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-vertical-c0-320px.css" media="all and (min-width:320px) and (orientation:landscape)"/><link rel="stylesheet" href="css/common-vertical-c0-700px.css" media="all and (min-width:700px) and (orientation:landscape)"/><link rel="stylesheet" href="css/common-vertical-c0-1000px.css" media="all and (min-width:1000px) and (orientation:landscape)"/><style
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
					a {
						text-decoration:none;
					}
					.cinematic {
						padding-top:20px;
						text-align:center;
						font-size:12pt;
						font-family:Open-Sans;
						width:100%;
						height:100%;
						color:black;
						background-color:rgba(0,0,255,0.3);
						clip-path: polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%);
					}
					.cinematic:hover {
						background-color:blue;
						color:white;
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
					height:200px;
					font-family:Open-Sans;
					font-size:1em;
					overflow:hidden;
				}
				textarea:focus {
					outline-style: none;
					box-shadow:0px 3px 3px 0px black;
				}
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
				.hidden {
					display:none;
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
				.selected {
					background-color:rgba(20, 134, 255,0.7);
					color:black;
				}
				.vueMeter {
					box-sizing:border-box;
					position:relative;
					top:0;
					width:100%;
					height:100%;
					border-top:2px solid black;
					background-color:white;
				}
				</style>
		</head
	>
		<body
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
							>Bienvenue</span
						></div
					></header
				><div
					style="height:20px"
					>&nbsp;</div
				><div
					style="display:inline-flex;height:50px;width:100%"
					><div
						style="width:2%"
						>&nbsp;</div
					><div
						style="width:12%"
						><a
							href="/discernement.php"
							><div
								class="cinematic"
								>1</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/temoignages.php"
							><div
								class="cinematic"
								>2</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/experiences.php"
							><div
								class="cinematic"
								>3</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/connaissances.php"
							><div
								class="cinematic"
								>4</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/rever.php"
							><div
								class="cinematic"
								>5</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/route.php"
							><div
								class="cinematic"
								>6</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/gagne.php"
							><div
								class="cinematic"
								>7</div
							></a
						></div
					><div
						style="width:12%"
						><a
							href="/fin.php"
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
					style="height:50px"
					>&nbsp;</div
				><div
					class="flex-row"
					style="width:100%;height:100%;margin:0 auto;height:auto"
					><div
						style="height:100%"
						><div
							class="flex-row"
							style="width:200px;height:100%;height:24px;margin:0 auto;margin-bottom:10px"
							><div
								style="height:100%"
								><div
									style="display:inline-flex;height:24px;width:100%;width:200px;margin:0 auto"
									><div
										style="width:5%"
										>&nbsp;</div
									><div
										style="width:90%"
										><input type="button" id="contribute" value="Contribuer"/></div
									><div
										style="width:5%"
										>&nbsp;</div
									></div
								></div
							></div
						><div
							class="contributeArea hidden"
							><div
								class="flex-row"
								style="width:200px;height:100%;margin:0 auto"
								><div
									style="height:100%;margin-top:10px"
									><div
										style="display:inline-flex;height:255px;width:100%;width:200px;margin:0 auto"
										><div
											style="width:1%"
											>&nbsp;</div
										><div
											style="width:98%"
											><div
												class="flex-row"
												style="width:100%;height:100%"
												><div
													style="height:80%"
													><textarea
														id="textArea"
														maxlength="160"
														placeholder="Saisissez votre texte..."
														></textarea
													></div
												><div
													style="height:20%;float:right"
													><input type="button" id="send" value="Envoyer" onclick="sendQuestion('#textArea')"/></div
												></div
											></div
										><div
											style="width:1%"
											>&nbsp;</div
										></div
									></div
								></div
							></div
						><div
							class="flex-row"
							style="width:200px;height:100%;height:190px;margin:0 auto;margin-bottom:20px"
							><div
								style="height:30%"
								><div
									style="display:inline-flex;height:47.4px;width:100%;width:200px;margin:0 auto;margin-bottom:10px"
									><div
										style="width:5%"
										>&nbsp;</div
									><div
										style="width:90%"
										><input type="button" id="btnApplaud" value="Applaudir les artistes"/></div
									><div
										style="width:5%"
										>&nbsp;</div
									></div
								></div
							><div
								style="height:70%"
								><div
									style="display:inline-flex;height:114px;width:100%;width:200px;margin:10px auto 0"
									><div
										style="width:5%"
										>&nbsp;</div
									><div
										style="width:65%;border-right:1px solid black;border-bottom:1px solid black;border-radius:15px;overflow:hidden"
										><img src="images/applaud.png" style="height:2.4em;vertical-align:middle"/>&nbsp;&nbsp;<span
											style="font-family:Open-Sans;font-size:2.4em"
											id="counterApplaud"
											></span
										></div
									><div
										style="width:5%"
										>&nbsp;</div
									><div
										style="width:20%;background:linear-gradient(green,yellow);margin:0 20px 0;border:2px solid black;border-top:unset;overflow:hidden"
										><div
											class="vueMeter"
											id="vueMeter"
											>&nbsp;</div
										></div
									><div
										style="width:5%"
										>&nbsp;</div
									></div
								></div
							></div
						><audio
							style="display:none"
							id="soundApplaud"
							><source
								src="sounds/applaud.mp3"
								></audio
							></div
						></div
					><div
						class="flex-row"
						style="width:100%;height:100%;margin:0 auto;height:auto"
						><div
							style="height:100%"
							><div
								style="display:inline-flex;height:100%;width:100%;width:auto;height:auto"
								><div
									style="width:1%"
									>&nbsp;</div
								><div
									style="width:98%;width:auto;height:auto"
									><div
										id="questions"
										><?php $sql = "SELECT e.id AS id, q.content AS content, e.likes AS likes, e.moderation AS moderation, e.selected AS selected, e.source AS source, e.id_question AS idQuestion, e.id_response AS myResponse, e.responseNumber AS responseNumber, (SELECT r.id FROM questionresponses AS r WHERE r.question = e.id_question AND r.id_event = e.id_event) AS response, (SELECT q1.response1 FROM questionresponses AS q1 WHERE q1.question = e.id_question AND q1.id_event = e.id_event) AS response1, (SELECT q2.response2 FROM questionresponses AS q2 WHERE q2.question = e.id_question AND q2.id_event = e.id_event) AS response2, (SELECT q3.response3 FROM questionresponses AS q3 WHERE q3.question = e.id_question AND q3.id_event = e.id_event) AS response3, NOW() AS date FROM questions AS q, evqust AS e WHERE e.id_event = " . $event . " AND e.id_question = q.id AND e.suppressed = 0 AND e.moderation = 1 ORDER BY e.source DESC, e.likes DESC";
												if ($fetch = $cnx->query($sql)) {
														if (($count_rows = $fetch->num_rows) > 0) {
																$num_line = 1;

																while($row = $fetch->fetch_assoc()) {
					
															$content = $row['content'];
															$id = $row['id'];
															$likes = $row['likes'];
															$moderation = $row['moderation'];
															$selected = $row['selected'];
															$questionSource = $row['source'];
															$idQuestion = $row['idQuestion'];
															$myResponse = $row['myResponse'];
															$responseNumber = $row['responseNumber'];
															$response = $row['response'];
															$response1 = $row['response1'];
															$response2 = $row['response2'];
															$response3 = $row['response3'];
															include('drawQuestion.php');
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
			><script
				 defer
				language="JavaScript"
				>let b = document.getElementById("contribute")
					var c = document.getElementsByClassName("contributeArea")
					c = c[0]
					b.addEventListener('click', function(e) {
						c.classList.toggle("hidden")
					})
					b = document.getElementById("btnApplaud")
					b.addEventListener('click', applaud)

				function load() {
						setInterval(async function(e) { await getReview() }, 2000)
					setInterval(sendLikes, 3000)
		
				}


				function sendQuestion(name) {
					let d = document.querySelector(name)
					let data = new FormData()
					// si il y a du texte dans la question...
					if (d.value.length > 1) {
						data.append("question", d.value)
						data.append("source", 0)
						fetch('./addQuestion.php?session=<?php echo $session ?>', {
							method : 'POST',
							body : data
						}).then(res => res.text())
							.then(text => {
								let x = document.getElementsByClassName("contributeArea")
								x = x[0]
								x.classList.toggle("hidden")
								d.value = ""
						}).catch(error => console.log(error))
					}
				}
				let totalApplaud = <?php
				$sql = "SELECT COUNT(*) as count FROM review AS r, evqust AS e WHERE `type` = 'APPLAUD' AND r.id_evqust = e.id AND e.id_event = " . $event;
						if ($fetch = $cnx->query($sql)) {
								if ($fetch->num_rows > 0) {
										$result = $fetch->fetch_assoc();
				
								echo $result['count'];
	
								}
						} else {
								logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "applaud", "question.php");
						}
		
					?>;

				b = document.getElementById("counterApplaud")
				b.innerText = totalApplaud

				function applaud() {
					fetch('./applaud.php?session=<?php echo $session?>')
					let a = document.getElementById("soundApplaud")
					try { a.play() } catch(e) {}
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
							await fetch('./getQuestion.php?session=<?php echo $session?>&id=' + u.id)
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
								if (u.infos.moderated == 0) {
									x[0].style.display = "none";
								} else {
									x[0].style.display = "inline-block";
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
						} else if (u.type == "APPLAUD") {
								var transitionInterval
								if (totalApplaud < u.infos.counter) {
									totalApplaud = u.infos.counter
									let d = document.getElementById("counterApplaud")
									d.innerText = totalApplaud
				                  let v = document.getElementById("vueMeter")
				                  v.style.height = `${100 - u.infos.vueMeter * 100}%`
				                  if (transitionInterval)
				                    clearInterval(transitionInterval)
				                  transitionInterval = setInterval(function() {
				                    let v = document.getElementById("vueMeter")
				                    if (parseInt(v.style.height) < 100) {
				                      v.style.height = `${parseInt(v.style.height) + 1}%`
				                    }
				                    else {
				                      v.style.height = "100%"
				                      clearInterval(transitionInterval)
				                      transitionInterval = null
				                    }
				                  }, 300)

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

