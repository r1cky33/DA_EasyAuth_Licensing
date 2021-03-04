<?php
include 'server.php';
include 'login_script.php';
?>

<!DOCTYPE html>
<html lang="en">
<title>EasyAuth licensing</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<body style ="background-color: #EE494A">
<div class="w3-display-container w3-display-middle w3-white w3-round-xxlarge" style ="height:350px; width: 600px; border: 2px solid black;">
    <img class ="w3-display-topmiddle" src="moga_logo.png" alt="logo" style="width: 210px;height: 130px; padding-top: 3%">
    <div class ="w3-display-bottommiddle w3-medium">
        <form class="w3-container" action="login_script.php">
            <input name = "email_signin" id="email_signin" class="w3-input w3-border w3-section" type="email" placeholder="e-mail">
            <input name = "pw_signin" id="pw_signin" class="w3-input w3-border w3-section" type="password" placeholder="password">
            <input name = "loginbtn" id="login" type="submit" class="w3-btn w3-blue w3-round-large" value ="Login" style ="margin-left: 33%">
            <p style ="font-size: 15px; color: dimgray">Not registered yet? - <a href="register.php">click here!</a></p>
        </form>
    </div>
</div>


</body>
</html>

