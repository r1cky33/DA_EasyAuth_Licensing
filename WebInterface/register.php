<?php include('server.php') ?>

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
<div class="w3-display-container w3-display-middle w3-white w3-round-xxlarge" style ="height:450px; width: 600px; border: 2px solid black;">
    <div class ="w3-display-topmiddle w3-xxlarge" style ="padding-top: 3%;">EasyAuth</div>
    <div class ="w3-display-middle w3-medium">
        <form method = "post" action="register.php" class="w3-container">
            <?php include('errors.php');?>
            <input id="name" class="w3-input w3-border w3-section" type="text" placeholder="name" value="<?php echo $username; ?>">
            <input id="email" class="w3-input w3-border w3-section" type="email" placeholder="e-mail" value="<?php echo $email; ?>">
            <input id="pw" class="w3-input w3-border w3-section" type="password" placeholder="password" value="<?php echo $password; ?>">
            <input id="pw_confirm" class="w3-input w3-border w3-section" type="text" placeholder="confirm password" value="<?php echo $password_confirm; ?>">
            <input id="register" type="button" class="w3-btn w3-blue w3-round-large" value ="Register" style ="margin-left: 33%">
            <p style ="font-size: 14px; color: dimgray">Already got an account? - <a href="login.php">click here!</a></p>
        </form>
    </div>
</div>


</body>
</html>