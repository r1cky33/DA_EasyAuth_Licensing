<?php
// Database connection
include('server.php');

// GET the token = ?token
if(!empty($_GET['token'])){
    $token = $_GET['token'];
} else {
    $token = "";
}

if($token != "") {
    $sqlQuery = mysqli_query($connection, "SELECT * FROM users WHERE token = '$token' ");
    $countRow = mysqli_num_rows($sqlQuery);

    if($countRow == 1){
        while($rowData = mysqli_fetch_array($sqlQuery)){
            $is_active = $rowData['is_active'];
            if($is_active == 0) {
                $update = mysqli_query($connection, "UPDATE users SET is_active = '1' WHERE token = '$token' ");
                if($update){
                    echo '<script type="text/javascript">alert("verification successful")</script>';
                    sleep(3);
                    header("location: login.php");
                }
            } else {
                echo '<script type="text/javascript">alert("e-mail already verified")</script>';
                sleep(3);
                header("location: login.php");
            }
        }
    } else {
        echo '<script type="text/javascript">alert("somehow ois beat")</script>';
        sleep(3);
        die();

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>EasyAuth licensing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style ="background-color: #EE494A">
</body>
</html>
