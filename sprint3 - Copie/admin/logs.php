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
			><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="Logs - Administration TOTEM La conférence"><meta name="generator" content="aloha - a programming language"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="../fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" type="image/png" href="../favicon.png"/><title
				>Logs - Administration - TOTEM La conférence</title
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
											>Pas d'entrée
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
											href="logs.php?&option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"
											>Actualiser</a
										><?php
											if (!empty($_POST)) {
												$postEnabled = true;

											} else {
												$postEnabled = false;
											}

											//init

											$sql = "SELECT COUNT(*) FROM logs";
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

												$sql = "SELECT date, severity, `desc`, trace, origin FROM logs" . " LIMIT " . strval($lines_per_page) . " OFFSET " . strval($skip_lines);
												if ($result = $cnx->query($sql)) {
													if ($result->num_rows > 0) {?><table
											><caption
												>Logs
																		</caption
											><thead
												><tr
													><!--make a static loop of fields--><td
														style="width:10%"
														>date</td
													><td
														style="width:10%"
														>Severity</td
													><td
														style="width:20%"
														>Description</td
													><td
														style="width:20%"
														>Trace</td
													><td
														style="width:30%"
														>Origine</td
													></tr
												></thead
											><tbody
												><!--make a dynamic loop of values--><?php
																$num_line = 1;
																while($row = $result->fetch_assoc()) {
																	?><tr
													><td
														><?php echo htmlspecialchars($row['date']) ?></td
													><td
														><?php echo htmlspecialchars($row['severity']) ?></td
													><td
														><?php echo htmlspecialchars($row['desc']) ?></td
													><td
														><?php echo htmlspecialchars($row['trace']) ?></td
													><td
														><?php echo htmlspecialchars($row['origin']) ?></td
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