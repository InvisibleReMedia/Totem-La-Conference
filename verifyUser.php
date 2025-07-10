<?php

$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$event = $_GET['id_event'];
if ($firstName && $lastName && $event) {

    $mac = get_cfg_var("MySQL_machine_name");
    $db = get_cfg_var("MySQL_database_name");
    $userName = get_cfg_var("MySQL_user_name_admin");
    $password = get_cfg_var("MySQL_password_admin");
    $conn = new mysqli($mac, $userName, $password, $db);
    if ($conn->connect_error) {
        die("Connection failed :" . $conn->connect_error);
    }
    
    if ($stmt = $conn->prepare("SELECT verifyUser(?, ?, ?)")) {

        $stmt->bind_param("ssi", $firstName, $lastName, $event);
        $stmt->bind_result($result);
        $stmt->execute();
        $stmt->fetch();
        echo $result;
        $stmt->close();

    }
    else
        echo $conn->error;
    
    $conn->close();

}
else
    echo 0;

?>
