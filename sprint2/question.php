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
		><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Participant TOTEM La conférence"><meta name="generator" content="aloha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="favicon.png"/><title
			>Contribuez - Participant - TOTEM La conférence</title
		><link rel="stylesheet" href="css/s-horizontal-styles.css"/><link rel="stylesheet" href="css/s-vertical-styles.css" media="all and (orientation: landscape)"/><link href="css/model1.css" rel="stylesheet"/><style
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
					margin-top:5px;
					margin-bottom:5px;
					padding-top:8px;
					padding-bottom:8px;
					padding-left:8px;
					padding-right:7px;
					box-shadow:1px 3px 3px 0px black;
					width:320px;
					overflow:hidden;
					font-family:Open-Sans;
					font-size:1.4em;
					color:black;
					transition:opacity 1s;
					opacity:1;
					cursor:pointer;
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
				</style>
		</head
	>
		<body
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
			class="vbox-e"
			><div
				class="vbox-element-f"
				><div
					class="hbox-g"
					><div
						class="hbox-element-h"
						><div
							class="vbox-i"
							><div
								class="vbox-element-j"
								>&nbsp;</div
							><div
								class="vbox-element-k hideSB"
								><div
									class="vbox-a"
									><div
										class="vbox-element-b"
										><div
											class="flex-row"
											style="width:200px;height:100%;height:24px;margin:0 auto;margin-bottom:10px"
											><div
												style="height:100%"
												><div
													style="display:inline-flex;height:24px;width:100%;width:200px;margin:0 auto"
													><div
														style="width:1%"
														>&nbsp;</div
													><div
														style="width:98%"
														><input type="button" id="contribute" value="Contribuer"/></div
													><div
														style="width:1%"
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
											style="width:200px;height:100%;height:48px;margin:0 auto;margin-bottom:20px"
											><div
												style="height:50%"
												><div
													style="display:inline-flex;height:24px;width:100%;width:200px;margin:0 auto"
													><div
														style="width:1%"
														>&nbsp;</div
													><div
														style="width:98%"
														><input type="button" id="btnApplaud" value="Applaudir les artistes"/></div
													><div
														style="width:1%"
														>&nbsp;</div
													></div
												></div
											><div
												style="height:50%"
												><div
													style="display:inline-flex;height:24px;width:100%;width:200px;margin:0 auto"
													><div
														style="width:1%"
														>&nbsp;</div
													><div
														style="width:98%"
														><span
															style="font-family:Open-Sans;font-size:1.4em"
															id="counterApplaud"
															></span
														>&nbsp;&nbsp;<img src="images/applaud.png" style="height:1.4em;vertical-align:middle"/></div
													><div
														style="width:1%"
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
										class="vbox-c"
										><div
											class="vbox-element-d"
											><div
												style="display:inline-flex;height:100%;width:100%;width:auto;height:auto"
												><div
													style="width:1%"
													>&nbsp;</div
												><div
													style="width:98%;width:auto;height:auto"
													><iframe
														width="100%"
														style="margin:0 auto"
														src="https://www.youtube.com/embed/nGYjEm3NYLs"
														title="YouTube video player"
														frameborder="0"
														allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
														 allowfullscreen
														></iframe
													><br/><div
														id="questions"
														><?php $sql = "SELECT e.id AS id, q.content AS content, e.likes AS likes, e.moderation AS moderation, e.selected AS selected, NOW() AS date FROM questions AS q, evqust AS e WHERE e.id_event = " . $event . " AND e.id_question = q.id AND e.suppressed = 0 AND e.moderation = 1 ORDER BY e.source DESC, e.likes DESC";
																if ($fetch = $cnx->query($sql)) {
																		if (($count_rows = $fetch->num_rows) > 0) {
																				$num_line = 1;

																				while($row = $fetch->fetch_assoc()) {
					
																			$content = $row['content'];
																			$id = $row['id'];
																			$likes = $row['likes'];
																			$moderation = $row['moderation'];
																			$selected = $row['selected'];
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
			
							await fetch('./isAutomaticModeration.php?session=<?php echo $session?>')
								.then(res => res.text())
								.then(async text => {
									if (parseInt(text) === 1) {await fetch('./getQuestion.php?session=<?php echo $session?>&id=' + u.id)
								.then(res => res.text())
								.then(text => {
									let x = document.getElementsByName('aQuestion')
									x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == u.id)
									if (x.length == 0) {
										let d = document.getElementById("questions")
										d.insertAdjacentHTML('beforeEnd', text)
									}
								})
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
							if (u.infos.moderated == 0) {
									if (x.length > 0)
										x[0].parentNode.removeChild(x[0])
								} else {await fetch('./getQuestion.php?session=<?php echo $session?>&id=' + u.id)
								.then(res => res.text())
								.then(text => {
									let x = document.getElementsByName('aQuestion')
									x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == u.id)
									if (x.length == 0) {
										let d = document.getElementById("questions")
										d.insertAdjacentHTML('beforeEnd', text)
									}
								})
									sorting()
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
								if (totalApplaud < u.infos.counter) {
									totalApplaud = u.infos.counter
									let d = document.getElementById("counterApplaud")
									d.innerText = totalApplaud
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

