<?php
	include('logging.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getSession.php');
	include('dbfunctions.php');
	$cnx = connect_to_db('admin', true);
	
	if (!getSession($cnx, $user, $event)) {
				header('Location: ./login.php');
		} else {
	getAuthentName($cnx, $user, $greetings, $firstName, $lastName);

		$sql = "SELECT permissions FROM users WHERE id = " . $user;
			if ($fetch = $cnx->query($sql)) {
					if ($fetch->num_rows > 0) {
							$result = $fetch->fetch_assoc();
							$result = $result['permissions'];

							if (($result & 2) == 0) {
									header('Location: ./restrict.html');
							}
			
					}
			} else {
					logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "menu.php");
			}
			?><!DOCTYPE html><html
		lang="fr"
		><head
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Animateur TOTEM La conférence"><meta name="generator" content="aloha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" href="favicon.ico"/><title
				>Les commentaires - Animateur - TOTEM La conférence</title
			><link rel="stylesheet" href="css/a-horizontal-styles.css"/><link rel="stylesheet" href="css/a-vertical-styles.css" media="all and (orientation: landscape)"/><link href="css/model1.css" rel="stylesheet"/><style
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
					button:focus {
						outline-style:none;
						box-shadow:none;
						border-color:red;
					}
				</style>
			<style
				>
					.question {
						display:inline-block;
						border-radius:15px;
						background-color:white;
						border:1px solid yellow;
						margin:1px;
						padding-left:5px;
						padding-right:5px;
						box-shadow:1px 3px 3px 0px black;
						width:83px;
						overflow:hidden;
						font-family:Open-Sans;
						font-size:0.7em;
						color:black;
						transition:opacity 1s;
						opacity:1;
						cursor:pointer;
					}
					.move {
						opacity:0;
					}
					.heart {
						height:1em;
						cursor:pointer;
					}
					.heart:hover {
						height:1.1em;
					}
					.heart:focus {
						outline-style:none;
					}
					.trash {
						height:1em;
						cursor:pointer;
					}
					.trash:hover {
						height:1.1em;
					}
					.trash:focus {
						outline-style:none;
					}
					</style>
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
				style="height:40px"
				>&nbsp;</div
			><div
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
										><div
											class="vbox-element-b"
											><div
												style="display:inline-flex;height:100%;width:100%;width:auto;height:auto"
												><div
													style="width:1%"
													>&nbsp;</div
												><div
													style="width:98%;width:auto;height:auto"
													><div
														id="questions"
														><?php $sql = "SELECT e.id AS id, q.content AS content, e.likes AS likes, e.moderation AS moderation, NOW() AS date FROM questions AS q, evqust AS e WHERE e.id_event = " . $event . " AND e.id_question = q.id AND e.suppressed = 0 ORDER BY e.likes DESC";
																if ($fetch = $cnx->query($sql)) {
																		if (($count_rows = $fetch->num_rows) > 0) {
																				$num_line = 1;

																				while($row = $fetch->fetch_assoc()) {
					
																			$content = $row['content'];
																			$id = $row['id'];
																			$likes = $row['likes'];
																			$moderation = $row['moderation'];
																			include('drawQuestionAnimateur.php');
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
					fetch('./removeQuestion.php?id=' + id)
				}
				 var likes = []

				function sendLikes() {

					for(const u of likes) {
						fetch('./addLikes.php?id=' + u.id + '&likes=' + u.likes)
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
					await fetch('review.php?last_date=' + last_date)
						.then(res => res.json())
						.then(json => processReview(json))
						.catch(error => console.error(error))
				}

				async function processReview(json) {
					for(const u of json) {
						if (u.type == "QUESTION") {
							await fetch('./getQuestionAnimateur.php?id=' + u.id)
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
					fetch('./disconnect.php')
						.then(res => res.text())
						.then(text => {
							if (text == 1) {
								document.cookie = ""
							}
						})
				}
				function testSession() {
					fetch('./testSession.php')
						.then(res => res.text())
						.then(text => {
							if (text == 0) {
								document.location = "./logout.html"
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
