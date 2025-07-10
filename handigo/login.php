<?php	include('logging.php');
	include('connectodb.php');
	include('getSession.php');
	include('dbfunctions.php');

	$cnx = connect_to_db('admin', true);
	
	
	?><!DOCTYPE html><html
	lang="fr"
	><head
		><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Login - Conférence HANDIGO"><meta name="generator" content="talenha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="favicon.png"/><title
			>Page d'authentification - Conférence HANDIGO</title
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
					background-color:rgba(255,255,255,0.9);
				}
				label {
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
				.freeAccess {
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
				>&nbsp;</div
			><div
				class="vbox-element-c hideSB"
				><header
					style="display:inline-flex;height:30px;width:100%;display:inline-block;border-bottom:1px solid black;margin-bottom:20px"
					><div
						style="width:100%"
						><span
							class="title"
							>Login</span
						></div
					></header
				><div
					class="errorArea"
					style="display:inline-flex;height:30px;width:100%;height:auto"
					><div
						style="width:100%"
						><ul
							style="padding-left:20px;padding-right:20px;width:200px;background-color:white;color:black;font-family:Open-Sans, Arial, Helvetica;font-size:0.8em;font-weight:bold;display:block"
							id="msg"
							></ul
						></div
					></div
				><div
					class="loginArea"
					style="display:inline-flex;height:60px;width:100%"
					><div
						style="width:100%"
						><div
							class="flex-row"
							style="width:320px;height:100%;margin-left:auto;margin-right:auto;height:80px;vertical-align:middle"
							><div
								style="height:50%"
								><dl
									style="display:inline-flex;height:30px;width:100%"
									><div
										style="width:30%"
										><dt
											><label
												>Prénom</label
											></dt
										></div
									><div
										style="width:70%"
										><dt
											><input name="prénom" type="text" id="firstName" autocomplete="firstName" placeholder="Votre prénom"/></dt
										></div
									></dl
								></div
							><div
								style="height:50%"
								><dl
									style="display:inline-flex;height:30px;width:100%"
									><div
										style="width:30%"
										><dt
											><label
												>Nom</label
											></dt
										></div
									><div
										style="width:70%"
										><dt
											><input name="nom" type="text" id="lastName" autocomplete="lastName" placeholder="Votre nom"/></dt
										></div
									></dl
								></div
							></div
						></div
					></div
				><div
					style="height:20px"
					>&nbsp;</div
				><div
					class="flex-row eventArea"
					style="width:320px;height:100%;margin-left:auto;margin-right:auto;height:auto"
					><div
						style="height:100%"
						><ul
							class="eventList"
							id="eventList"
							><?php
							$sql = "SELECT id, title, date_start, date_end FROM events WHERE date_start < NOW() AND date_end > NOW() AND ISNULL(link) AND suppressed = 0";
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
											logging($cnx, "ERROR", "error query - reason:" . $cnx->error, "", "login.php");
									}
									?></ul
						></div
					></div
				><input name="événement" type="hidden" id="eventId" value="<?php if (isset($oneEventId)) { echo $oneEventId; } ?>"/><div
					class="flex-row"
					style="width:200px;height:100%;height:30px;margin-left:auto;margin-right:auto;margin-top:5px"
					><div
						style="height:100%"
						><input <?php if (isset($oneEventId)) { } else { echo "disabled='true'"; }?> style="width:200px" type="button" id="submit" value="<?php if (isset($oneEventId)) { echo "Participer à la conférence"; } else { echo "Valider"; }?>"/></div
					></div
				><div
					style="height:20px"
					>&nbsp;</div
				><button
					class="freeAccess"
					id="freeAccess"
					>Accès libre</button
				></div
			></div
		><script
			language="JavaScript"
			>
					var formItems = [
						document.getElementById("firstName"),
						document.getElementById("lastName"),
						document.getElementById("eventId") ]
					function createLink(testItem) {
						var listItem = document.createElement('li');
						var anchor = document.createElement('a');
						anchor.textContent = testItem.name + ' champ vide: remplissez le champ ' + testItem.name + '.';
						anchor.href = '#' + testItem.name;
						anchor.onclick = function() {
							testItem.focus();
						};
						listItem.appendChild(anchor);
						let msg = document.getElementById("msg")
						msg.appendChild(listItem);
					}

					function query() {
						let msg = document.getElementById("msg")
						msg.innerHTML = '';
						for(var i = 0; i < formItems.length; i++) {
							var testItem = formItems[i];
							if(testItem.value === '') {
								createLink(testItem);
							}
						}

						if(msg.innerHTML === '') {
							var qs = {
								"firstName" : formItems[0].value,
								"lastName" : formItems[1].value,
								"id_event" : formItems[2].value
							}
							var url = "verifyUser.php?" +
								Object.keys(qs)
									.map(x => encodeURIComponent(x) + '=' + encodeURIComponent(qs[x]))
									.join('&')
							fetch(url)
								.then(response => response.text())
								.then(text => {
									if (text) {
										let m = document.getElementById("msg")
										m.innerText = ''
										document.cookie = "session=" + text + "; expires=" + new Date(Date.now() + 1000*3600*24).toString()
										document.location = "menu.php"
									}
									else {
										let m = document.getElementById("msg")
										m.innerHTML = "<li>Erreur d'authentification</li>"
									}
								})
								.catch(error => {
									let m = document.getElementById("msg")
									m.innerHTML = "<li>Erreur d'accès au serveur</li>"
								})

						}
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
					document.getElementById("freeAccess").addEventListener('click', function(e) {
						document.location = "./index.php"
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
