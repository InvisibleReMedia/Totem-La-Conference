<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>
			Users
		</title>
		<style>
			table { box-sizing: content-box; width: 100%; border: 1px solid black; } th, thead { background-color: #604000; color: white; } td { border: 1px solid black; } .group { display:block; box-sizing:content-box; } .group> div { display:inline-block; padding-bottom:4px; } .group> div> div { width:200px; height:30px; text-align:center; padding-top:15px; padding-bottom:0px; display:inline-block; border:1px solid black; background-color:#802040; color:white; } input, label { line-height:1.4em; } .hidden { display:none; }
		</style>
		<script language="JavaScript" type="text/javascript">
			function toggle(elementId, className) { let x= document.getElementById(elementId); x.classList.toggle(className) }
		</script>

	</head>
	<body>
		<button onclick="toggle('newRecord', 'hidden')">
			Nouvel utilisateur
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
	?><a href="users.php?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>">Actualiser</a><form method="POST" action="users.php?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"><div id="newRecord" class="group hidden">
	<div>
	<div><label>Civilité</label></div>
	<div><input type="text" name="greetings"/></div>
	</div>
	<div>
	<div><label>First name</label></div>
	<div><input type="text" name="firstName"/></div>
	</div>
	<div>
	<div><label>Last name</label></div>
	<div><input type="text" name="lastName"/></div>
	</div>
	<div>
	<div><label>Password</label></div>
	<div><input type="text" name="password"/></div>
	</div>
	<div>
	<div><label>Rôle</label></div>
	<div><input type="text" name="role"/></div>
	</div>
	<div>
	<div><label>Permissions</label></div>
	<div><input type="text" name="permissions"/></div>
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

		if (array_key_exists('greetings', $_POST)) {
			$greetings = $_POST['greetings'];
		} else {
			die('greetings');
		}

		if (array_key_exists('firstName', $_POST)) {
			$firstName = $_POST['firstName'];
		} else {
			die('firstName');
		}

		if (array_key_exists('lastName', $_POST)) {
			$lastName = $_POST['lastName'];
		} else {
			die('lastName');
		}

		if (array_key_exists('password', $_POST)) {
			$password = $_POST['password'];
		} else {
			die('password');
		}

		if (array_key_exists('role', $_POST)) {
			$role = $_POST['role'];
		} else {
			die('role');
		}

		if (array_key_exists('permissions', $_POST)) {
			$permissions = $_POST['permissions'];
		} else {
			die('permissions');
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
			$sql = "INSERT INTO users(greetings, firstName, lastName, password, role, permissions, infos, suppressed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		} else {
			$sql = "UPDATE users SET greetings = ?, firstName = ?, lastName = ?, password = ?, role = ?, permissions = ?, infos = ?, suppressed = ? WHERE id = ?";
		}
		if ($stmt = $cnx->prepare($sql)) {
			if ($insert) {
				$stmt->bind_param("sssssisi", $greetings, $firstName, $lastName, $password, $role, $permissions, $infos, $suppressed);
			} else {
				$stmt->bind_param("sssssisii", $greetings, $firstName, $lastName, $password, $role, $permissions, $infos, $suppressed, $id);
			}
			if (!$stmt->execute()) {
				echo $stmt->error;
			}
			$stmt->close();
		} else
			echo $cnx->error;
		}
		$sql = "SELECT COUNT(*) FROM users";
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

			$sql = "SELECT id, greetings, firstName, lastName, password, role, permissions, infos, suppressed FROM users" . " LIMIT " . strval($lines_per_page) . " OFFSET " . strval($skip_lines);
			if ($result = $cnx->query($sql)) {
				if ($result->num_rows > 0) {?>		<table>
								<caption>
									users
								</caption>
								<thead>
									<tr>
					<!--make a static loop of fields-->					
					<td>&nbsp;</td>
					<td style="width:10%">id</td>
					<td style="width:5%">Civilité</td>
					<td style="width:20%">First name</td>
					<td style="width:20%">Last name</td>
					<td style="width:10%">Password</td>
					<td style="width:10%">Rôle</td>
					<td style="width:10%">Permissions</td>
					<td style="width:10%">Infos</td>
					<td style="width:10%">Supprimé</td>

									</tr>

								</thead>
								<tbody>
					<!--make a dynamic loop of values--><?php
					$num_line = 1;
					while($row = $result->fetch_assoc()) {
						?><tr>
						<td><a href="#" onclick="toggle('record<?php echo $num_line?>', 'hidden')">&gt;</a></td>
						<td><?php echo htmlspecialchars($row['id']) ?></td>
						<td><?php echo htmlspecialchars($row['greetings']) ?></td>
						<td><?php echo htmlspecialchars($row['firstName']) ?></td>
						<td><?php echo htmlspecialchars($row['lastName']) ?></td>
						<td><?php echo htmlspecialchars($row['password']) ?></td>
						<td><?php echo htmlspecialchars($row['role']) ?></td>
						<td><?php echo htmlspecialchars($row['permissions']) ?></td>
						<td><?php echo htmlspecialchars($row['infos']) ?></td>
						<td><?php echo htmlspecialchars($row['suppressed']) ?></td>
						</tr><tr id="record<?php echo $num_line?>" class="hidden"><td colspan="10"><a href="?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>">Actualiser</a><form method="POST" action="?option=<?php echo $option?>&num_page=<?php echo $num_page?>&lines_per_page=<?php echo $lines_per_page?>"><div class="group">
						<input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']) ?>"/><div>
						<div><label>Civilité</label></div>
						<div><input type="text" name="greetings" value="<?php echo htmlspecialchars($row['greetings']) ?>"/></div>
						</div>
						<div>
						<div><label>First name</label></div>
						<div><input type="text" name="firstName" value="<?php echo htmlspecialchars($row['firstName']) ?>"/></div>
						</div>
						<div>
						<div><label>Last name</label></div>
						<div><input type="text" name="lastName" value="<?php echo htmlspecialchars($row['lastName']) ?>"/></div>
						</div>
						<div>
						<div><label>Password</label></div>
						<div><input type="text" name="password" value="<?php echo htmlspecialchars($row['password']) ?>"/></div>
						</div>
						<div>
						<div><label>Rôle</label></div>
						<div><input type="text" name="role" value="<?php echo htmlspecialchars($row['role']) ?>"/></div>
						</div>
						<div>
						<div><label>Permissions</label></div>
						<div><input type="text" name="permissions" value="<?php echo htmlspecialchars($row['permissions']) ?>"/></div>
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
		<div style="display:inline-flex;height:100px;width:100%"><div style="width:11%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=first&lines_per_page=<?php echo $lines_per_page?>">&lt;&lt;</a></div></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><?php if ($num_page > 1) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>">&lt;</a></div><?php } ?></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><?php if ($num_page >= 4) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page-3)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page-3)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if ($num_page >= 3) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page-2)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page-2)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if ($num_page >= 2) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page-1)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page-1)?></a></div><?php } ?></div><div style="width:8%;border:1px solid black"><?php echo $num_page ?></div><div style="width:5%;border:1px solid black"><?php if (($num_page+1) <= $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page+1)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if (($num_page+2) <= $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page+2)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page+2)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black"><?php if (($num_page+3) <= $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page+3)?>&lines_per_page=<?php echo $lines_per_page?>"><?php echo ($num_page+3)?></a></div><?php } ?></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><?php if ($num_page < $nbr_pages) { ?><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=&num_page=<?php echo ($num_page+1)?>&lines_per_page=<?php echo $lines_per_page?>">&gt;</a></div><?php } ?></div><div style="width:5%;border:1px solid black">&nbsp;</div><div style="width:5%;border:1px solid black"><div style="line-height:100px;height:100px;text-align:center"><a href="users.php?option=last&lines_per_page=<?php echo $lines_per_page?>">&gt;&gt;</a></div></div><div style="width:11%;border:1px solid black">&nbsp;</div></div>
			</body>
		</html>