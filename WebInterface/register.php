<?php
include 'server.php';
include 'signup_script.php';
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
<div class="w3-display-container w3-display-middle w3-white w3-round-xxlarge" style ="height:550px; width: 600px; border: 2px solid black;">
    <img class ="w3-display-topmiddle" src="moga_logo.png" alt="logo" style="width: 210px;height: 130px; padding-top: 3%">
    <div class ="w3-display-middle w3-medium" style="padding-top:22%">
        <form method = "post" action="register.php" class="w3-container">
            <input name= "username" id="name" class="w3-input w3-border w3-section" type="text" placeholder="name">
            <input name ="email" id="email" class="w3-input w3-border w3-section" type="email" placeholder="e-mail">
            <input name ="password" id="pw" class="w3-input w3-border w3-section" type="password" placeholder="password">
            <input name ="pw_confirm" id="pw_confirm" class="w3-input w3-border w3-section" type="password" placeholder="confirm password">
            <input id="submit" name="submit" value="register" type="submit" class="w3-btn w3-blue w3-round-large" style ="margin-left: 33%">
            <p style ="font-size: 14px; color: dimgray">Already got an account? - <a href="login.php">click here!</a></p>
            <p style ="font-size: 14px; color: dimgray; padding-top:6%;">Password: 8 to 24 characters, at least one special character, lowercase & uppercase char and a digit</p>
        </form>
    </div>
</div>


</body>
</html>
