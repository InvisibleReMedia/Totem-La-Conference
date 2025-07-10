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
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Review - Administration TOTEM La conférence"><meta name="generator" content="aloha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="../fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="../favicon.png"/><title
				>Review - Administration - TOTEM La conférence</title
			><link rel="stylesheet" href="../css/d-horizontal-styles.css"/><link rel="stylesheet" href="../css/d-vertical-styles.css" media="all and (orientation: landscape)"/><link href="../css/model1.css" rel="stylesheet"/><style
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
							table {
								box-sizing: content-box;
								width: 100%;
								border: 1px solid black;
							}
							th, thead {
								background-color: #604000;
								color: white;
							}
							td {
								border: 1px solid black;
							}
							.group {
								display:block;
								box-sizing:content-box;
							}
							.group>div {
								display:inline-block;
								padding-bottom:4px;
							}
							.group>div>div {
								width:200px;
								height:30px;
								text-align:center;
								padding-top:15px;
								padding-bottom:0px;
								display:inline-block;
								border:1px solid black;
								background-color:#802040;
								color:white;
							}
							input, label {
								line-height:1.4em;
							}
							.hidden {
								display:none;
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
									><div
										><button
											onclick="document.location = 'index.php'"
											>Retour</button
										><button
											onclick="toggle('newRecord', 'hidden')"
											>Nouveau review
												</button
										><?php
											// initialization
											$nbr_pages = 0;
											if (array_key_exists('option', $_GET)) {
												$option = $_GET['option'];
											} else {
												$option = "";
											}
											if (!($option == "first" || $option == "last" || $option == "")) {
												die("option");
											}
											if (array_key_exists('num_page', $_GET)) {
												$num_page = $_GET['num_page']; // verify integer with ctype_digit(strval($value))
											} else {
												$num_page = "1";
											}
											if (!ctype_digit(strval($num_page))) {
												die("numpage");
											}
											if (array_key_exists('lines_per_page', $_GET)) {
												$lines_per_page = $_GET['lines_per_page']; // verify integer with ctype_digit(strval($value))
											} else {
												$lines_per_page = "10";
											}
											if (!ctype_digit(strval($lines_per_page))) {
												die("lines_per_page");
											}


											?><a
											href="review.php?&option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"
											>Actualiser</a
										><form
											method="POST"
											action="review.php?&option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"
											><div
												id="newRecord"
												class="group hidden"
												><div
													><div
														><label
															>Date</label
														></div
													><div
														><div
															class="hidden"
															style="position:absolute;left:0px;top:0px;background-color:LightBlue;color:black"
															name="calendar"
															data-ref="0"
															><table
																style="margin:0 auto"
																><tr
																	><td
																		style="float:left"
																		><b
																			><span
																				style="text-decoration:underline"
																				onmouseenter="this.style.color='red'"
																				onmouseleave="this.style.color='black'"
																				onclick="earlyYear('cyear', 0)"
																				>&lt;</span
																			></b
																		></td
																	><td
																		style="text-align:center"
																		name="cyear"
																		data-ref="0"
																		></td
																	><td
																		style="float:right"
																		><b
																			><span
																				style="text-decoration:underline"
																				onmouseenter="this.style.color='red'"
																				onmouseleave="this.style.color='black'"
																				onclick="laterYear('cyear', 0)"
																				>&gt;</span
																			></b
																		></td
																	></tr
																><tr
																	><td
																		style="float:left"
																		><b
																			><span
																				style="text-decoration:underline"
																				onmouseenter="this.style.color='red'"
																				onmouseleave="this.style.color='black'"
																				onclick="earlyMonth('cmonth', 0)"
																				>&lt;</span
																			></b
																		></td
																	><td
																		style="text-align:center"
																		name="cmonth"
																		data-ref="0"
																		></td
																	><td
																		style="float:right"
																		><b
																			><span
																				style="text-decoration:underline"
																				onmouseenter="this.style.color='red'"
																				onmouseleave="this.style.color='black'"
																				onclick="laterMonth('cmonth', 0)"
																				>&gt;</span
																			></b
																		></td
																	></tr
																><tr
																	><td
																		colspan="3"
																		style="margin:0 auto"
																		name="days"
																		data-ref="0"
																		></td
																	></tr
																><tr
																	><td
																		colspan="3"
																		style="margin:0 auto"
																		><input type="button" value="Fermer" onclick="closeCalendar(0)"/></td
																	></tr
																></table
															></div
														><input type="text" style="width:140px" name="date" data-ref="0"/><img src="date.png" style="position:relative;left:10px;top:5px;max-height:15px;cursor:pointer" onclick="loadCalendar('date',0);openCalendar(0)"/></div
													></div
												><div
													><div
														><label
															>Evqust</label
														></div
													><div
														><input type="text" name="id_evqust"/></div
													></div
												><div
													><div
														><label
															>Utilisateur</label
														></div
													><div
														><input type="text" name="id_user"/></div
													></div
												><div
													><div
														><label
															>Type</label
														></div
													><div
														><input type="text" name="type"/></div
													></div
												><div
													><div
														><label
															>Description</label
														></div
													><div
														><input type="text" name="description"/></div
													></div
												><div
													><div
														><label
															>Infos</label
														></div
													><div
														><input type="text" name="infos"/></div
													></div
												><div
													><div
														><input type="submit" value="Créer"/></div
													></div
												></div
											></form
										><?php
											if (!empty($_POST)) {
												$postEnabled = true;

												if (array_key_exists('id', $_POST)) {
													$insert = false;
													$id = $_POST['id'];
												} else {
													$insert = true;
												}

												if (array_key_exists('date', $_POST)) {
													$date = $_POST['date'];
												} else {
													die('date');
												}

												if (array_key_exists('id_evqust', $_POST)) {
													$id_evqust = $_POST['id_evqust'];
												} else {
													die('id_evqust');
												}

												if (array_key_exists('id_user', $_POST)) {
													$id_user = $_POST['id_user'];
												} else {
													die('id_user');
												}

												if (array_key_exists('type', $_POST)) {
													$type = $_POST['type'];
												} else {
													die('type');
												}

												if (array_key_exists('description', $_POST)) {
													$description = $_POST['description'];
												} else {
													die('description');
												}

												if (array_key_exists('infos', $_POST)) {
													$infos = $_POST['infos'];
												} else {
													die('infos');
												}

											} else {
												$postEnabled = false;
											}

											//init

											if ($postEnabled) {
												if ($insert) {
													$sql = "INSERT INTO review(date, id_evqust, id_user, type, description, infos) VALUES (?, ?, ?, ?, ?, ?)";
												} else {
													$sql = "UPDATE review SET date = ?, id_evqust = ?, id_user = ?, type = ?, description = ?, infos = ? WHERE id = ?";
												}
												if ($stmt = $cnx->prepare($sql)) {
													if ($insert) {
														$stmt->bind_param("siisss", $date, $id_evqust, $id_user, $type, $description, $infos);
													} else {
														$stmt->bind_param("siisssi", $date, $id_evqust, $id_user, $type, $description, $infos, $id);
													}
													if (!$stmt->execute()) {
														echo $stmt->error;
													}
													$stmt->close();
												} else
													echo $cnx->error;
												}

												$sql = "SELECT COUNT(*) FROM review";
												if ($query = $cnx->query($sql)) {
													// verify that result contains the nbr_pages
													// after SQL select
													// nbr_pages = count the number of lines (num_rows)
													// divide by lines_per_page, rounded by lines_per_page
													// add 1
													$result = $query->fetch_row();
													$nbr_pages = ($result[0] - $result[0] % $lines_per_page) / $lines_per_page + 1;
													// if option == "last" skip lines (nbr_pages-1)*lines_per_page
													// else
													// skip lines is (num_page * lines_per_page)
													if ($option == "last") {
														$skip_lines = ($nbr_pages-1) * $lines_per_page;
														$num_page = $nbr_pages;
													} else if ($option == "first") {
														$skip_lines = 0;
														$num_page = 1;
													} else {
														$skip_lines = (($num_page-1) * $lines_per_page);
													}

													$sql = "SELECT id, date, id_evqust, id_user, type, description, infos FROM review" . " LIMIT " . strval($lines_per_page) . " OFFSET " . strval($skip_lines);
													if ($result = $cnx->query($sql)) {
														if ($result->num_rows > 0) {?><table
											><caption
												>Review
																			</caption
											><thead
												><tr
													><!--make a static loop of fields--><td
														>&nbsp;</td
													><td
														style="width:10%"
														>id</td
													><td
														style="width:10%"
														>Date</td
													><td
														style="width:5%"
														>Evqust</td
													><td
														style="width:5%"
														>Utilisateur</td
													><td
														style="width:10%"
														>Type</td
													><td
														style="width:20%"
														>Description</td
													><td
														style="width:20%"
														>Infos</td
													></tr
												></thead
											><tbody
												><!--make a dynamic loop of values--><?php
																	$num_line = 1;
																	while($row = $result->fetch_assoc()) {
																		?><tr
													><td
														><a
															href="#"
															onclick="toggle('record<?php echo $num_line?>', 'hidden')"
															>&gt;</a
														></td
													><td
														><?php echo htmlspecialchars($row['id']) ?></td
													><td
														><?php echo htmlspecialchars($row['date']) ?></td
													><td
														><?php echo htmlspecialchars($row['id_evqust']) ?></td
													><td
														><?php echo htmlspecialchars($row['id_user']) ?></td
													><td
														><?php echo htmlspecialchars($row['type']) ?></td
													><td
														><?php echo htmlspecialchars($row['description']) ?></td
													><td
														><?php echo htmlspecialchars($row['infos']) ?></td
													></tr
												><tr
													id="record<?php echo $num_line?>"
													class="hidden"
													><td
														colspan="8"
														><form
															method="POST"
															action="?&option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"
															><div
																class="group"
																><input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']) ?>"/><div
																	><div
																		><label
																			>Date</label
																		></div
																	><div
																		><div
																			class="hidden"
																			style="position:absolute;left:0px;top:0px;background-color:LightBlue;color:black"
																			name="calendar"
																			data-ref="<?php echo $row['id']?>"
																			><table
																				style="margin:0 auto"
																				><tr
																					><td
																						style="float:left"
																						><b
																							><span
																								style="text-decoration:underline"
																								onmouseenter="this.style.color='red'"
																								onmouseleave="this.style.color='black'"
																								onclick="earlyYear('cyear', <?php echo $row['id']?>)"
																								>&lt;</span
																							></b
																						></td
																					><td
																						style="text-align:center"
																						name="cyear"
																						data-ref="<?php echo $row['id']?>"
																						></td
																					><td
																						style="float:right"
																						><b
																							><span
																								style="text-decoration:underline"
																								onmouseenter="this.style.color='red'"
																								onmouseleave="this.style.color='black'"
																								onclick="laterYear('cyear', <?php echo $row['id']?>)"
																								>&gt;</span
																							></b
																						></td
																					></tr
																				><tr
																					><td
																						style="float:left"
																						><b
																							><span
																								style="text-decoration:underline"
																								onmouseenter="this.style.color='red'"
																								onmouseleave="this.style.color='black'"
																								onclick="earlyMonth('cmonth', <?php echo $row['id']?>)"
																								>&lt;</span
																							></b
																						></td
																					><td
																						style="text-align:center"
																						name="cmonth"
																						data-ref="<?php echo $row['id']?>"
																						></td
																					><td
																						style="float:right"
																						><b
																							><span
																								style="text-decoration:underline"
																								onmouseenter="this.style.color='red'"
																								onmouseleave="this.style.color='black'"
																								onclick="laterMonth('cmonth', <?php echo $row['id']?>)"
																								>&gt;</span
																							></b
																						></td
																					></tr
																				><tr
																					><td
																						colspan="3"
																						style="margin:0 auto"
																						name="days"
																						data-ref="<?php echo $row['id']?>"
																						></td
																					></tr
																				><tr
																					><td
																						colspan="3"
																						style="margin:0 auto"
																						><input type="button" value="Fermer" onclick="closeCalendar(<?php echo $row['id']?>)"/></td
																					></tr
																				></table
																			></div
																		><input type="text" style="width:140px" name="date" value="<?php echo htmlspecialchars($row['date']) ?>" data-ref="<?php echo $row['id']?>"/><img src="date.png" style="position:relative;left:10px;top:5px;max-height:15px;cursor:pointer" onclick="loadCalendar('date',<?php echo $row['id']?>);openCalendar(<?php echo $row['id']?>)"/></div
																	></div
																><div
																	><div
																		><label
																			>Evqust</label
																		></div
																	><div
																		><input type="text" name="id_evqust" value="<?php echo htmlspecialchars($row['id_evqust']) ?>"/></div
																	></div
																><div
																	><div
																		><label
																			>Utilisateur</label
																		></div
																	><div
																		><input type="text" name="id_user" value="<?php echo htmlspecialchars($row['id_user']) ?>"/></div
																	></div
																><div
																	><div
																		><label
																			>Type</label
																		></div
																	><div
																		><input type="text" name="type" value="<?php echo htmlspecialchars($row['type']) ?>"/></div
																	></div
																><div
																	><div
																		><label
																			>Description</label
																		></div
																	><div
																		><input type="text" name="description" value="<?php echo htmlspecialchars($row['description']) ?>"/></div
																	></div
																><div
																	><div
																		><label
																			>Infos</label
																		></div
																	><div
																		><input type="text" name="infos" value="<?php echo htmlspecialchars($row['infos']) ?>"/></div
																	></div
																><div
																	><div
																		><input type="submit" value="Modifier"/></div
																	></div
																></div
															></form
														></td
													></tr
												><?php
																		++$num_line;
																		}
																	?></tbody
											><tfoot
												></tfoot
											></table
										><?php
														}
													}
												}
											?><div
											style="display:inline-flex;height:100px;width:100%"
											><div
												style="width:11%;border:1px solid black"
												>&nbsp;</div
											><div
												style="width:5%;border:1px solid black"
												><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=first&lines_per_page=<?php echo $lines_per_page?>"
														>&lt;&lt;</a
													></div
												></div
											><div
												style="width:5%;border:1px solid black"
												>&nbsp;</div
											><div
												style="width:5%;border:1px solid black"
												><?php if ($num_page > 1) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>"
														>&lt;</a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												>&nbsp;</div
											><div
												style="width:5%;border:1px solid black"
												><?php if ($num_page >= 4) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page-3)?>&lines_per_page=<?php echo $lines_per_page?>"
														><?php echo ($num_page-3)?></a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												><?php if ($num_page >= 3) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page-2)?>&lines_per_page=<?php echo $lines_per_page?>"
														><?php echo ($num_page-2)?></a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												><?php if ($num_page >= 2) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>"
														><?php echo ($num_page-1)?></a
													></div
												><?php } ?></div
											><div
												style="width:8%;border:1px solid black"
												><div
													style="line-height:100px;height:100px;text-align:center"
													><?php echo $num_page ?></div
												></div
											><div
												style="width:5%;border:1px solid black"
												><?php if (($num_page+1) <= $nbr_pages) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>"
														><?php echo ($num_page+1)?></a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												><?php if (($num_page+2) <= $nbr_pages) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page+2)?>&lines_per_page=<?php echo $lines_per_page?>"
														><?php echo ($num_page+2)?></a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												><?php if (($num_page+3) <= $nbr_pages) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page+3)?>&lines_per_page=<?php echo $lines_per_page?>"
														><?php echo ($num_page+3)?></a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												>&nbsp;</div
											><div
												style="width:5%;border:1px solid black"
												><?php if ($num_page < $nbr_pages) { ?><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=&num_page=<?php echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>"
														>&gt;</a
													></div
												><?php } ?></div
											><div
												style="width:5%;border:1px solid black"
												>&nbsp;</div
											><div
												style="width:5%;border:1px solid black"
												><div
													style="line-height:100px;height:100px;text-align:center"
													><a
														href="?&option=last&lines_per_page=<?php echo $lines_per_page?>"
														>&gt;&gt;</a
													></div
												></div
											><div
												style="width:11%;border:1px solid black"
												>&nbsp;</div
											></div
										></div
									></div
								></div
							></div
						></div
					></div
				></div
			><script
				language="JavaScript"
				type="text/javascript"
				>
							function toggle(elementId, className) {
								let x = document.getElementById(elementId);
								x.classList.toggle(className)
							}
							function getFormRef(id, ref) {
								let d = document.getElementsByName(id);
								let arr = Array.prototype.slice.call(d).filter(a => a.dataset.ref == ref);
								return arr[0]
							}
					function openCalendar(ref) {
					getFormRef('calendar', ref).style.display = "block"
				}

				function closeCalendar(ref) {
					getFormRef('calendar', ref).style.display = "none"
				}

				function loadCalendar(id, ref) {
					let today = new Date()
					let cyear = getFormRef('cyear', ref)
					let cmonth = getFormRef('cmonth', ref)
					cyear.innerText = today.getFullYear()
					cmonth.innerText = today.getMonth() + 1
					drawDays(today.getFullYear(), today.getMonth() + 1, today.getDate(), id, ref)
				}

				function earlyYear(id, ref) {
					let today, m, y
					today = new Date()
					m = today.getMonth() + 1
					y = today.getFullYear()
					let cyear = getFormRef('cyear', ref)
					let cmonth = getFormRef('cmonth', ref)
					cyear.innerText = parseInt(cyear.innerText) - 1
					drawDays(parseInt(cyear.innerText), parseInt(cmonth.innerText), today.getDate(), id, ref)
				}

				function laterYear(id, ref) {
					let today, m, y
					today = new Date()
					m = today.getMonth() + 1
					y = today.getFullYear()
					let cyear = getFormRef('cyear', ref)
					let cmonth = getFormRef('cmonth', ref)
					cyear.innerText = parseInt(cyear.innerText) + 1
					drawDays(parseInt(cyear.innerText), parseInt(cmonth.innerText), today.getDate(), id, ref)
				}

				function earlyMonth(id, ref) {
					let today, dMonth, m, y
					today = new Date()
					y = today.getFullYear()
					m = today.getMonth() + 1
					let cyear = getFormRef('cyear', ref)
					let cmonth = getFormRef('cmonth', ref)
					dMonth = parseInt(cmonth.innerText)
					if (dMonth > 1) {
						dMonth = dMonth - 1
						if (m == dMonth && y == parseInt(cyear.innerText))
							drawDays(parseInt(cyear.innerText), dMonth, today.getDate(), id, ref)
						else
							drawDays(parseInt(cyear.innerText), dMonth, 0, id, ref)
					}
					cmonth.innerText = dMonth
				}

				function laterMonth(id, ref) {
					let today, dMonth, m, y
					today = new Date()
					y = today.getFullYear()
					m = today.getMonth() + 1
					let cyear = getFormRef('cyear', ref)
					let cmonth = getFormRef('cmonth', ref)
					dMonth = parseInt(cmonth.innerText)
					if (dMonth < 12) {
						dMonth = dMonth + 1
						if (m == dMonth && y == parseInt(cyear.innerText))
							drawDays(parseInt(cyear.innerText), dMonth, today.getDate(), id, ref)
						else
							drawDays(parseInt(cyear.innerText), dMonth, 0, id, ref)
					}
					cmonth.innerText = dMonth
				}

				function drawDays(y, m, d, id, ref) {
					let firstDayOfMonth, dDay, dYear, dMonth, dweekId
					dYear = y
					dMonth = m
					dDay = d
					firstDayOfMonth = new Date(dYear, dMonth - 1, 1)
					dweekId = (firstDayOfMonth.getDay() + 6) % 7
					let text = "<table cellpadding='1' cellspacing='1'>"
					text = text + "<tr><td><b>L</b></td><td><b>M</b></td><td><b>M</b></td><td><b>J</b></td><td><b>V</b></td><td><b>S</b></td><td><b>D</b></td></tr>"
					for(let i = 1; i <= 7; ++i) {
						text = text + "<tr>"
						for(let j = 1; j <= 7; ++j) {
							text = text + "<td>"
							if (((i-1) * 7 + j - dweekId) <= new Date(dYear, dMonth, 1-1).getDate()) {
								if (((i-1) * 7 + j) > dweekId) {
									text = text + "<span onmouseenter='this.style.color = \"red\"' onmouseleave='this.style.color = \"black\"' onclick='changeDay(this.innerText, \"" + id + "\", " + ref + ")'"
									if (dDay == (i-1) * 7 + j - dweekId && dMonth == (new Date().getMonth() + 1) && dYear == new Date().getFullYear())
										text = text + " style='text-decoration:underline;color:white;background-color:DarkBlue'"
									else
										text = text + " style='text-decoration:underline'"
									text = text + ">" + ((i-1) * 7 + j - dweekId) + "</span>"
								}
							}
							text = text + "</td>"
						}
						text = text + "</tr>"
					}
					text = text + "</table>"
					getFormRef('days', ref).innerHTML = text
				}

				function changeDay(value, id, ref) {
					let cyear, cmonth
					cyear = getFormRef('cyear', ref)
					cmonth = getFormRef('cmonth', ref)
					let obj = getFormRef(id, ref)
					obj.value = cyear.innerText + "-" + cmonth.innerText + "-" + value
					closeCalendar(ref)
				}
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