<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>
			Evénements
		</title>
		<style>
			table { box-sizing: content-box; width: 100%; border: 1px solid black; } th, thead { background-color: #604000; color: white; } td { border: 1px solid black; } .group { display:block; box-sizing:content-box; } .group> div { display:inline-block; padding-bottom:4px; } .group> div> div { width:200px; height:30px; text-align:center; padding-top:15px; padding-bottom:0px; display:inline-block; border:1px solid black; background-color:#802040; color:white; } input, label { line-height:1.4em; } .hidden { display:none; }
		</style>
		<script language="JavaScript" type="text/javascript">
			function toggle(elementId, className) { let x= document.getElementById(elementId); x.classList.toggle(className) } function getFormRef(id, ref) { let d= document.getElementsByName(id); let arr= Array.prototype.slice.call(d).filter(a=> a.dataset.ref== ref); return arr[0] }function openCalendar(ref) {
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
					text = text + "<span onmouseenter='this.style.color = \\"red\\"' onmouseleave='this.style.color = \\"black\\"' onclick='changeDay(this.innerText, \\"" + id + "\\", " + ref + ")'"
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
	</head>
	<body>
				<button onclick="toggle('newRecord', 'hidden')">
			Nouvel événement
		</button>
<?php
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
	

	?><a href="events.php?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>">Actualiser</a><form method="POST" action="events.php?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"><div id="newRecord" class="group hidden">
	<div>
	<div><label>Titre</label></div>
	<div><input type="text" name="title"/></div>
	</div>
	<div>
	<div><label>Date début</label></div>
	<div><input type="text" name="date_start"/></div>
	</div>
	<div>
	<div><label>Date fin</label></div>
	<div><input type="text" name="date_end"/></div>
	</div>
	<div>
	<div><label>Adresse</label></div>
	<div><input type="text" name="address"/></div>
	</div>
	<div>
	<div><label>Infos</label></div>
	<div><input type="text" name="infos"/></div>
	</div>
	<div>
	<div><label>Supprimé</label></div>
	<div><input type="text" name="suppressed"/></div>
	</div>
	<div><div><input type="submit" value="Créer"/></div></div></div>
	</form><?php
	if (!empty($_POST)) {
		$postEnabled = true;

		if (array_key_exists('id', $_POST)) {
			$insert = false;
			$id = $_POST['id'];
		} else {
			$insert = true;
		}

		if (array_key_exists('title', $_POST)) {
			$title = $_POST['title'];
		} else {
			die('title');
		}

		if (array_key_exists('date_start', $_POST)) {
			$date_start = $_POST['date_start'];
		} else {
			die('date_start');
		}

		if (array_key_exists('date_end', $_POST)) {
			$date_end = $_POST['date_end'];
		} else {
			die('date_end');
		}

		if (array_key_exists('address', $_POST)) {
			$address = $_POST['address'];
		} else {
			die('address');
		}

		if (array_key_exists('infos', $_POST)) {
			$infos = $_POST['infos'];
		} else {
			die('infos');
		}

		if (array_key_exists('suppressed', $_POST)) {
			$suppressed = $_POST['suppressed'];
		} else {
			die('suppressed');
		}

	} else {
		$postEnabled = false;
	}


	//open
	$mac = get_cfg_var("MySQL_machine_name");
	$db = get_cfg_var("MySQL_database_name");
	$user = get_cfg_var("MySQL_user_name_admin");
	$pass = get_cfg_var("MySQL_password_admin");
	$cnx = new mysqli($mac, $user, $pass, $db);
	if ($cnx->connect_error) {
		//error
		die("error sql");
	}
	//success


	//init
	if ($postEnabled) {
		if ($insert) {
			$sql = "INSERT INTO events(title, date_start, date_end, address, infos, suppressed) VALUES (?, ?, ?, ?, ?, ?)";
		} else {
			$sql = "UPDATE events SET title = ?, date_start = ?, date_end = ?, address = ?, infos = ?, suppressed = ? WHERE id = ?";
		}
		if ($stmt = $cnx->prepare($sql)) {
			if ($insert) {
				$stmt->bind_param("sssssi", $title, $date_start, $date_end, $address, $infos, $suppressed);
			} else {
				$stmt->bind_param("sssssii", $title, $date_start, $date_end, $address, $infos, $suppressed, $id);
			}
			if (!$stmt->execute()) {
				echo $stmt->error;
			}
			$stmt->close();
		} else
			echo $cnx->error;
		}
		$sql = "SELECT COUNT(*) FROM events";
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

			$sql = "SELECT id, title, date_start, date_end, address, infos, suppressed FROM events" . " LIMIT " . strval($lines_per_page) . " OFFSET " . strval($skip_lines);
			if ($result = $cnx->query($sql)) {
				if ($result->num_rows > 0) {?>		<table>
								<caption>
									Evénements
								</caption>
								<thead>
									<tr>
					<!--make a static loop of fields-->					
					<td>&nbsp;</td>
					<td style="width:5%">id</td>
					<td style="width:10%">Titre</td>
					<td style="width:15%">Date début</td>
					<td style="width:15%">Date fin</td>
					<td style="width:25%">Adresse</td>
					<td style="width:20%">Infos</td>
					<td style="width:10%">Supprimé</td>

									</tr>

								</thead>
								<tbody>
					<!--make a dynamic loop of values--><?php
					$num_line = 1;
					while($row = $result->fetch_assoc()) {
						?><tr>
						<td><a href="" onclick="(function(e) { toggle('record<?php echo $num_line?>', 'hidden'); e.preventDefault()})(event)">&gt;</a></td>
						<td><?php echo htmlspecialchars($row['id']) ?></td>
						<td><?php echo htmlspecialchars($row['title']) ?></td>
						<td><?php echo htmlspecialchars($row['date_start']) ?></td>
						<td><?php echo htmlspecialchars($row['date_end']) ?></td>
						<td><?php echo htmlspecialchars($row['address']) ?></td>
						<td><?php echo htmlspecialchars($row['infos']) ?></td>
						<td><?php echo htmlspecialchars($row['suppressed']) ?></td>
						</tr><tr id="record<?php echo $num_line?>" class="hidden"><td colspan="8"><a href="?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>">Actualiser</a><form method="POST" action="?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"><div class="group">
						<input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']) ?>"/><div>
						<div><label>Titre</label></div>
						<div><input type="text" name="title" value="<?php echo htmlspecialchars($row['title']) ?>"/></div>
						</div>
						<div>
						<div><label>Date début</label></div>
						<div><input type="text" name="date_start" value="<?php echo htmlspecialchars($row['date_start']) ?>"/></div>
						</div>
						<div>
						<div><label>Date fin</label></div>
						<div><input type="text" name="date_end" value="<?php echo htmlspecialchars($row['date_end']) ?>"/></div>
						</div>
						<div>
						<div><label>Adresse</label></div>
						<div><input type="text" name="address" value="<?php echo htmlspecialchars($row['address']) ?>"/></div>
						</div>
						<div>
						<div><label>Infos</label></div>
						<div><input type="text" name="infos" value="<?php echo htmlspecialchars($row['infos']) ?>"/></div>
						</div>
						<div>
						<div><label>Supprimé</label></div>
						<div><input type="text" name="suppressed" value="<?php echo htmlspecialchars($row['suppressed']) ?>"/></div>
						</div>
						<div><div><input type="submit" value="Modifier"/></div></div></div>
						</form></td></tr><?php
						++$num_line;
						}
					?>			</tbody>
								<tfoot>
								</tfoot>
							</table>

				<?php
				}
			}
		}

	$cnx->close();
	//close


	?>
	<div style="display:inline-flex;height:100px;width:100%"><div style="width:11%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=first&lines_per_page=<?php echo $lines_per_page?>">&lt;&lt;</a></div></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><?php if ($num_page > 1) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>">&lt;</a></div><?php } ?></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><?php if ($num_page >= 4) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page-3)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page-3)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if ($num_page >= 3) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page-2)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page-2)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if ($num_page >= 2) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page-1)?></a></div><?php } ?></div><div style="width:8%;border:1px solid black"><div style="line-height:100px;height:100px;text-align:center"><?php echo $num_page ?></div></div><div style="width:5%;border:1px solid black"><?php if (($num_page+1) <= $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page+1)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if (($num_page+2) <= $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page+2)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page+2)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if (($num_page+3) <= $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page+3)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page+3)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><?php if ($num_page < $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=&num_page=<?php echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>">&gt;</a></div><?php } ?></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><div style="line-height:100px;height:100px;text-align:center"><a href="events.php?option=last&lines_per_page=<?php echo $lines_per_page?>">&gt;&gt;</a></div></div><div style="width:11%;border:1px solid black">&nbsp;</div></div>
	
		</body>

	</html>

