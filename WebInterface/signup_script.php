<?php
include('server.php');

// Swiftmailer lib
require_once 'vendor/autoload.php';

// Set empty form vars for validation mapping
$_username = $_email = $_password = $_password_conf ="";

if(isset($_POST["submit"])) {
    $username      = $_POST['username'];
    $email         = $_POST['email'];
    $password      = $_POST['password'];
    $password_confirm = $_POST['pw_confirm'];

    // check if email already exist
    $email_check_query = mysqli_query($connection, "SELECT * FROM users WHERE email = '{$email}' ");
    $rowCount = mysqli_num_rows($email_check_query);

    // PHP validation
    // Verify if form values are not empty
    if(!empty($username) && !empty($email) && !empty($password)){

        // check if user email already exist
        if($rowCount > 0) {
            echo '<script type="text/javascript">alert("e-mail already exists");
            window.location.href = "register.php";
            </script>';
        } 
        else {

            // clean the form data before sending to database
            $_username = mysqli_real_escape_string($connection, $username);
            $_email = mysqli_real_escape_string($connection, $email);
            $_password = mysqli_real_escape_string($connection, $password);
            $_password_conf = mysqli_real_escape_string($connection, $password_confirm);

            // perform validation
            if(!preg_match("/^[a-zA-Z0-9]*$/", $_username)) {
                echo '<script type="text/javascript">alert("in username: numbers and letters only");
                window.location.href = "register.php";
                </script>';
            }
            if(!filter_var($_email, FILTER_VALIDATE_EMAIL)) {
                echo '<script type="text/javascript">alert("error in e-mail");
                window.location.href = "register.php";
                </script>';
            }
            if(!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,24}$/", $_password)) {
                echo '<script type="text/javascript">alert("Password between 8 to 24 characters, at least one special character, lowercase, uppercase and a digit.");
                window.location.href = "register.php";
                </script>';
            }
            if($_password != $_password_conf){
                echo '<script type="text/javascript">alert("passwords do not match");
                window.location.href = "register.php";
                </script>';
            }
            // Store the data in db, if all the preg_match condition met
            else{

                // Generate random activation token
                $token = md5(rand().time());

                // Password hash
                $password_hash = password_hash($_password, PASSWORD_BCRYPT);

                // Query

                $sql = "INSERT INTO users (username, email, password, token, is_active, is_admin) VALUES ('{$_username}','{$_email}','{$password_hash}', '{$token}', '0', '0');";

                // Create mysql query
                $sqlQuery = mysqli_query($connection, $sql);

                if(!$sqlQuery){
                    die("MySQL query failed!" . mysqli_error($connection));
                }

                // Send verification email
                else {
                    $msg = 'Click on the activation link to verify your email. <br><br>
                          <a href="http://192.168.1.110:8000/user_activation.php?token='.$token.'"> Click here to verify email</a>
                        ';

                    // Create the Transport
                    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                        ->setUsername('easyauth.dontresponde@gmail.com')
                        ->setPassword('kjcjrecuqwmuzhqd');

                    // Create the Mailer using your created Transport
                    $mailer = new Swift_Mailer($transport);

                    // Create a message
                    $message = (new Swift_Message('Please Verify Your Email Address!'))
                        ->setFrom([$_email => $_username])
                        ->setTo($_email)
                        ->addPart($msg, "text/html")
                        ->setBody('Hello! User');

                    // Send the message
                    $result = $mailer->send($message);

                    if($result) {
                        echo '<script type="text/javascript">alert("verification e-mail has been sent");
                        window.location.href = "login.php";
                        </script>';
                    }
                    else{
                        echo '<script type="text/javascript">alert("could not send verification e-mail");
                        window.location.href = "register.php";
                        </script>';
                    }

                }
            }
        }
    }
    else {
        if(empty($_username)){
            echo '<script type="text/javascript">alert("username can not be blank");
            window.location.href = "register.php";
            </script>';
        }
        if(empty($_email)){
            echo '<script type="text/javascript">alert("e-mail can not be blank");
            window.location.href = "register.php";
            </script>';
        }
        if(empty($_password)){
            echo '<script type="text/javascript">alert("password can not be blank");
            window.location.href = "register.php";
            </script>';
        }
    }
}
?>
