<?php
	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$cnx = connect_to_db('admin');
	if (!getSession($cnx, $user, $event)) {
		header('Location: ./login.php');
	}
	getAuthentName($cnx, $user, $greetings, $firstName, $lastName);
	getEventTitle($cnx, $event, $eventTitle);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="language" content="french">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css"/>
		<link rel="stylesheet" href="quill/quill.snow.css"/>
		<title>
			Les questions
		</title>
		<style>
			body { min-width:300px; } /* remember to set the width in size or % */ .center-vertically { margin:auto; padding:10px; } .padding-center-vertically { padding : 20px 0; } .line-height-center-vertically { line-height : 30px; height: 30px; } .multiple-lines-center-vertically { display:inline-block; vertical-align : middle; } .absolute-right { position: absolute; top:-50%; left:100%; transform : translate(-100%,50%); } .center-horizontally { display: block; margin: auto 0; } .text-center { text-align : center; } .left-horizontally { float : left; } .right-horizontally { float : right; } .clearfix { overflow : auto; } .flex-row : { display:flex; flex-direction:row;  width:100% } #standalone-container {   box-sizing: content-box; width: 100%; height: 100%;  }  #editor-container {   height: 100px;  }
			.hidden { display: none; }
			.ql-print>p {
				margin-top:-30px;
			}
			img:focus {
				outline-width: 0;
			}
		</style>

	</head>
	<script language="JavaScript">
		function testSession() {
			fetch('./testSession.php')
				.then(res => res.text())
				.then(text => {
					if (text == 0) {
						document.location = "./logout.html"
					}
				})
		}

		function load() {
			setInterval(getReview, 1000)
			setInterval(sendLikes, 3000)
			setInterval(testSession, 1500)
		}

		function sendQuestion(name) {
			let d = document.querySelector(name)
			let data = new FormData()
			// si il y a du texte dans la question...
			if (d.innerText.length > 1) {
				data.append("question", d.innerHTML )
				fetch('./addQuestion.php', {
					method : 'POST',
					body : data
				}).then(res => res.text())
				.then(text => {
					let x = document.getElementById("editQuestion")
					x.classList.toggle("hidden")
					quill.setText('')
				}).catch(error => console.log(error))
			}
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

    	function moveUp(obj) {
    		this.current = obj
    		this.previous = current.previousSibling
    		if (this.previous) {
    			currentSize = this.current.offsetHeight
    			this.current.style.zIndex = "2"
        		this.current.style.transform = "translate(0, "- + currentSize + "px)"
        		previousSize = this.previous.offsetHeight
        		this.previous.style.transform = "translate(0, " + previousSize + "px)"
        		setTimeout(function(e) {
        			this.current.style.zIndex = ""
	    			this.current.style.transform = "none"
	    			let parent = current.parentNode
	    			this.previous.style.transform = "none"
	    			parent.removeChild(this.current)
	    			parent.insertBefore(this.current, this.previous)
	    			setTimeout(function(e) {
	    				sorting(this.current)
	    			}, 500);
        		}, 1000)
    		}
    	}

    	function sorting(obj) {
    		let evqust = obj.dataset.evqust;
    		let previous = obj.previousSibling;
    		if (previous) {
	    		let previousEvqust = previous.dataset.evqust;
	    		let l = document.getElementsByName('likes');
	    		let list = Array.prototype.slice.call(l);
	    		prev = list.filter(a => a.dataset.evqust == previousEvqust);
	    		let cur = list.filter(a => a.dataset.evqust == evqust);
	    		if (parseInt(prev[0].innerText) < parseInt(cur[0].innerText)) {
	    			moveUp(obj)
	    		}

    		}
    	}

		async function processReview(json) {
			for(const u of json) {
				if (u.type == "QUESTION") {
					let q = await fetch('./getquestion.php?id=' + u.id)
						.then(res => res.text())
						.then(text => {
							let d = document.getElementById("questions")
							d.insertAdjacentHTML('beforeEnd', text)
						})
				} else if (u.type == "LIKES") {
					let x = document.getElementsByName('likes');
					evqust = u.id;
					x = Array.prototype.slice.call(x).filter(a => a.dataset.evqust == evqust);
					x[0].innerText = u.infos.totalLikes;
					this.y = document.getElementsByName('aQuestion');
					this.y = Array.prototype.slice.call(y).filter(a => a.dataset.evqust == evqust);
					setTimeout(function(e) {
						sorting(this.y[0])
					}, 500)
				}
				last_date = u.last;
			}
		}

		function disconnect() {
			fetch('./disconnect.php')
				.then(res => res.text())
				.then(text => {
					if (text == 1) {
						document.cookie = ""
					}
				})
		}

		function openQuestion() {
			let x = document.getElementById("editQuestion")
			x.classList.toggle("hidden")
		}

	</script>
	<body onload="load()">
		<nav style="display:inline-flex;width:100%">
			<div style="width:10%;">
				&nbsp;
			</div>
			<div style="width:50%;">
<?php echo $greetings ." " . $firstName ." " . $lastName ?>
			</div>
			<div style="width:40%;float:right">
				<button onclick="disconnect()">Déconnexion</button>
			</div>

		</nav>
		<section style="display:inline-flex;width:100%">
			<div style="width:100%;">
				<header class="flex-row" style="width:100%;height:100%">
					<div style="height:100%;">
						<h1 style="text-align:center">
							<?php echo $eventTitle ?>
						</h1>

					</div>

				</header>

			</div>

		</section>
		<section class="flex-row" style="width:100%;height:100%">
			<div style="height:30%;">
				<header style="display:inline-flex;height:50px;width:100%">
					<div style="width:70%;line-height:50px;height:50px;padding-left:10px">
						<button onclick="openQuestion()">
							Poser une question
						</button>

					</div>
					<div style="width:30%;">
						&nbsp;
					</div>

				</header>

			</div>
			<div id="editQuestion" class="hidden" style="height:70%;">
				<section style="display:inline-flex;height:220px;width:100%">
					<div style="width:100%;">
						<div id="standalone-container">
							 							<div id="toolbar-container">
								  								<span class="ql-formats">
									<select class="ql-font">
									</select>
									  									<select class="ql-size">
									</select>
									 
								</span>
  								<span class="ql-formats">
									  									<button class="ql-bold">
									</button>
									  									<button class="ql-italic">
									</button>
									  									<button class="ql-underline">
									</button>
									  									<button class="ql-strike">
									</button>
									 
								</span>
  								<span class="ql-formats">
									  									<select class="ql-color">
									</select>
									  									<select class="ql-background">
									</select>
									 
								</span>
  								<span class="ql-formats">
									  									<button class="ql-script" value="sub">
									</button>
									  									<button class="ql-script" value="super">
									</button>
									 
								</span>
  								<span class="ql-formats">
									  									<button class="ql-header" value="1">
									</button>
									  									<button class="ql-header" value="2">
									</button>									 
								</span>
  								<span class="ql-formats">
									  									<button class="ql-list" value="ordered">
									</button>
									  									<button class="ql-list" value="bullet">
									</button>
									  									<button class="ql-indent" value="-1">
									</button>
									  									<button class="ql-indent" value="+1">
									</button>
									 
								</span>
  								<span class="ql-formats">
									  									<button class="ql-direction" value="rtl">
									</button>
									  									<select class="ql-align">
									</select>
									<button class="ql-clean"></button>
								</span>
  
							</div>
  							<div id="editor-container">
							</div>
						</div>
					</div>
				</section>
				<footer style="display:inline-flex;height:50px;width:100%">
					<div style="width:80%;">
						&nbsp;
					</div>
					<div style="width:20%;">
						<button onclick="sendQuestion('.ql-editor')">
							Envoyer
						</button>

					</div>

				</footer>
			</div>
		</section>
		<section id="questions" class="flex-row" style="width:100%;height:100%"><?php
			$sql = "SELECT e.id AS id, q.content AS content, e.likes AS likes, NOW() AS date FROM questions AS q, evqust AS e WHERE e.id_event = " . $event . " AND e.id_question = q.id ORDER BY e.likes DESC";
			if ($result = $cnx->query($sql)) {
				if (($count_rows = $result->num_rows) > 0) {
					$num_line = 1;
					while($row = $result->fetch_assoc()) {

						$content = $row['content'];
						$id = $row['id'];
						$line = $num_line;
						$likes = $row['likes'];
						$date = $row['date'];
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
			}?></section>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.js">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js">
	</script>
	<script src="quill/quill.min.js">
	</script>
	<script>
		 var quill= new Quill('#editor-container', {   modules: {    formula: true,    syntax: true,    toolbar: '#toolbar-container'   },   placeholder: 'Ecrivez votre question...',   theme: 'snow'  });
	</script>
	<script language="JavaScript">
		var last_date = "<?php echo $last_date ?>"
		async function getReview() {
			fetch('review.php?last_date=' + last_date)
				.then(res => res.json())
				.then(json => processReview(json))
				.catch(error => console.error(error))
		}
	</script>

</body>
</html>
<?php close_db($cnx);
	?>