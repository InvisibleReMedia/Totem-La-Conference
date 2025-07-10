<?php
    include('connectodb.php');// connection method to db is not browsable
    include('getsession.php');
    include('dbfunctions.php');
    $cnx = connect_to_db('admin');
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Login</title>
        <style>
            * {
                box-sizing: content-box;
            }
            em {
                color:red;
            }
            .label {
                background-color: blueviolet;
                color:white;
            }
            form {
                width:fit-content;
                height:fit-content;
                margin:auto auto;
                display:flex;
                flex-direction:column;
            }
        </style>
        <script language="JavaScript">
            function query() {
                var fName = document.getElementById("firstName")
                var lName = document.getElementById("lastName")
                var event = document.getElementById("event")
                var qs = {
                        "firstName" : fName.value,
                        "lastName" : lName.value,
                        "id_event" : event.value
                }
                var url = "verifyUser.php?" +
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
                        else
                            document.getElementById("msg").innerText = "Error login"
                    })
                    .catch(error => console.error(error))
            }
        </script>
    </head>
    <body>
        <form>
            <em id="msg">&nbsp;</em>
            <dl>
                <dt class="label" style="width:100%">Prénom :</dt>
                <dt style="width:100%">
                    <input type="text" id="firstName" autocomplete="firstName" style="width:98%" placeholder="Votre prénom"/>
                </dt>
            </dl>
            <dl>
                <dt class="label" style="width:100%">Nom :</dt>
                <dt style="width:100%">
                    <input type="text" id="lastName" autocomplete="lastName" style="width:98%" placeholder="Votre nom"/>
                </dt>
            </dl>
            <dl>
                <dt class="label" style="width:100%">Evenement :</dt>
                <dt>
                    <select id="event">
                        <?php
            $sql = "SELECT id, title, date_start, date_end, address FROM events";
            if ($result = $cnx->query($sql)) {
                if (($count_rows = $result->num_rows) > 0) {
                    $num_line = 1;
                    while($row = $result->fetch_assoc()) {?>
                        <option value="<?php echo $row['id']?>"><?php echo $row['title']?> - du <?php echo $row['date_start']?> au <?php echo $row['date_end']?> à <?php echo $row['address']?></option>
<?php
                        ++$num_line;
                        if ($num_line == $count_rows) {
                            $last_date = $row['date'];
                        }
                    }
                }
            }
                        ?>
                    </select>
                </dt>
            </dl>
            <input type="button" value="Submit" onclick="query()"/>
        </form>
    </body>
</html>