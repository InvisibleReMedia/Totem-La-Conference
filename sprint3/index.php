<?php	include('logging.php');
	include('connectodb.php');
	include('getSession.php');
	include('dbfunctions.php');

	$cnx = connect_to_db('admin', true);
	
	
	?><!DOCTYPE html><html
	lang="fr"
	><head
		><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Web application pour une interactivité à fort impact sociétal. Notre objectif est de répondre à vos besoins en interactivité digitale et contribuer à votre politique RSE en matière d'inclusion des personnes en situation de handicap."><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="favicon.png"/><title
			>Bienvenue à TOTEM La conférence</title
		><link href="css/model2.css" rel="stylesheet"/><link rel="stylesheet" href="css/common-horizontal-c0-320px.css" media="all and (min-width:320px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-horizontal-c0-700px.css" media="all and (min-width:700px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-horizontal-c0-1000px.css" media="all and (min-width:1000px) and (orientation:portrait)"/><link rel="stylesheet" href="css/common-vertical-c0-320px.css" media="all and (min-width:320px) and (orientation:landscape)"/><link rel="stylesheet" href="css/common-vertical-c0-700px.css" media="all and (min-width:700px) and (orientation:landscape)"/><link rel="stylesheet" href="css/common-vertical-c0-1000px.css" media="all and (min-width:1000px) and (orientation:landscape)"/><style
			>
					.errorArea {
						font-family:Open-Sans, Arial, Helvetica;
						font-size:1em;
					}
					.loginArea {
						margin-top:5px;
					}
					.eventArea {
						margin-top:5px;
					}
					.eventList {
						position:relative;
						padding:0;
						font-family:Open-Sans;
						font-size:0.8em;
						box-sizing:content-box;
						list-style:none;
						width:100%;
						vertical-align:middle;
					}
					.eventItem {
						display:inline-block;
						background-color:white;
						color:black;
						border:1px solid black;
						border-radius:15px;
						box-shadow:1px 3px 3px 0px black;
						text-align:center;
						overflow:hidden;
						transition: background-color 1s;
					}
					.eventItem:active {
						background-color:rgba(249,177,51,0.7);
					}
					dt {
						text-decoration:underline;
						font-family:Open-Sans, Arial, Helvetica;
						font-size:0.8em;
					}
					.selectEventItem {
						cursor:pointer;
					}
					.selected {
						color:white;
						background-color:black;
					}
					.memberAccess {
						font-family:Open-Sans;
						font-size:0.5em;
						background-color:yellow;
						border-radius:15px;
						color:black;
						cursor:pointer;
					}</style>
		</head
	><body
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
											>Bienvenue</span
										></div
									></header
								><div
									style="height:20px"
									>&nbsp;</div
								><div
									class="flex-row eventArea"
									style="width:100%;height:100%;margin-left:auto;margin-right:auto;height:auto"
									><div
										style="height:100%"
										><ul
											class="eventList"
											id="eventList"
											><?php
											$sql = "SELECT id, title, date_start, date_end FROM events WHERE date_start > NOW() AND suppressed = 0";
													if ($fetch = $cnx->query($sql)) {
															if (($count_rows = $fetch->num_rows) > 0) {
																	if ($count_rows == 1) {
														$row = $fetch->fetch_assoc();
														$oneEventId = $row['id'];
													} else {
																	while($row = $fetch->fetch_assoc()) {
																			?><li
															class="eventItem"
															style="display:inline-flex;height:80px;width:100%;width:90px"
															><div
																style="width:100%"
																><div
																	class="flex-row"
																	style="width:90px;height:100%;font-size:0.8em"
																	><div
																		style="height:25%"
																		><?php echo htmlspecialchars($row['title']);?></div
																	><div
																		style="height:20%"
																		><?php 
																					$d = strtotime($row['date_start']);
																					echo "du " . date("d/m", $d) . " à " . date("H:i", $d);?></div
																	><div
																		style="height:20%"
																		><?php 
																					$d = strtotime($row['date_end']);
																					echo "au " . date("d/m", $d) . " à " . date("H:i", $d);?></div
																	><div
																		style="height:35%"
																		><div
																			class="selectEventItem"
																			data-id="<?php echo $row['id']?>"
																			>Sélectionner cet événement</div
																		></div
																	></div
																></div
															></li
														><?php

																	}
																	}
															} else {
																	$noEvents = true;
															}
													} else {
															logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "index.php");
													}
													?></ul
										></div
									></div
								><input type="hidden" id="eventId" value="<?php if (isset($oneEventId)) { echo $oneEventId; } ?>"/><div
									class="flex-row"
									style="width:100%;height:100%;height:30px;margin-top:5px"
									><div
										style="height:100%;width:200px;margin-left:auto;margin-right:auto"
										><input <?php if (isset($oneEventId)) { } else { echo "disabled='true'"; }?> type="button" style="width:200px" id="submit" value="<?php if (isset($oneEventId)) { echo "Participer à la conférence"; } else { echo "Valider"; }?>"/></div
									></div
								><div
									style="height:20px"
									>&nbsp;</div
								><button
									class="memberAccess"
									id="memberAccess"
									>Accès membre</button
								></div
							></div
						></div
					></div
				></div
			></div
		><script
			language="JavaScript"
			>
			function query() {
					var event = document.getElementById("eventId")
					var qs = {
						"id_event" : event.value
					}
					var url = "createUser.php?" +
						Object.keys(qs)
							.map(x => encodeURIComponent(x) + '=' + encodeURIComponent(qs[x]))
							.join('&')
						fetch(url)
							.then(response => response.text())
							.then(text => {
								if (text) {
									document.cookie = "session=" + text + "; expires=" + new Date(Date.now() + 1000*3600*24).toString()
									document.location = "question.php"
								}
								else {
									alert("Nous sommes désolés, une erreur inattendue est survenue. Réessayez")
								}
							})
							.catch(error => {
								console.log(error)
								alert("Nous sommes désolés, il y a eu un problème réseau. Réessayez")
							})
					}
					let b = document.getElementById('submit')
					b.addEventListener('click', query)
					var currentEvent = false
					let l = document.getElementsByClassName('selectEventItem')
					for(const x of l) {
						x.addEventListener('click', function(e) {
							if (currentEvent) {
								currentEvent.classList.toggle('selected')
								currentEvent.innerText = "Sélectionnez cet événement"
							}
							let v = document.getElementById('eventId')
							v.value = x.dataset.id
							x.classList.toggle('selected')
							x.innerText = "Evénement sélectionné"
							currentEvent = x
							b.disabled = false
						})
					}
					document.getElementById("memberAccess").addEventListener('click', function(e) {
						document.location = "./login.php"
					})
					<?php
						if (isset($noEvents))
							echo "document.getElementById('eventList').innerHTML = \"<li>Il n'y a aucun événement pour le moment.</li>\"";
					?>
			</script>
		</body
	></html
><?php
		
	close_db($cnx);
?>
