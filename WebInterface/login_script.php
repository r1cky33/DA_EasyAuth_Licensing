<?php

include('server.php');

if(isset($_POST["loginbtn"])) {

    $email_signin = $_POST['email_signin'];
    $password_signin = $_POST['pw_signin'];

    // clean data
    $user_email = filter_var($email_signin, FILTER_SANITIZE_EMAIL);
    $pswd = mysqli_real_escape_string($connection, $password_signin);

    // Query if email exists in db
    $sql = "SELECT * From users WHERE email = '{$email_signin}' ";
    $query = mysqli_query($connection, $sql);
    $rowCount = mysqli_num_rows($query);

    // If query fails, show the reason
    if(!$query){
        die("SQL query failed: " . mysqli_error($connection));
    }

    if(!empty($email_signin) && !empty($password_signin)){
        if(!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $pswd)) {
            echo '<script type="text/javascript">alert("Password between 8 to 24 characters, at least one special character, lowercase, uppercase and a digit.")</script>';
            die();
        }
        // Check if email exist
        if($rowCount <= 0) {
            echo '<script type="text/javascript">alert("account does not exist");
            window.location.href = "login.php";
            </script>';
        } else {
            // Fetch user data and store in php session
            while($row = mysqli_fetch_array($query)) {
                $id            = $row['id'];
                $username      = $row['username'];
                $email         = $row['email'];
                $pass_word     = $row['password'];
                $token         = $row['token'];
                $is_active     = $row['is_active'];
            }

            // Verify password
            $password = password_verify($password_signin, $pass_word);

            // Allow only verified user
            if($is_active == '1') {
                if($email_signin == $email && $password_signin == $password) {
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['token'] = $token;

                    header("location: dashboard.php");

                } else {
                    echo '<script type="text/javascript">alert("something is wrong with email or password");
                    window.location.href = "login.php";
                    </script>';
                }
            } else {
                echo '<script type="text/javascript">alert("verification not completed");
                window.location.href = "login.php";
                </script>';
            }

        }

    } else {
        if(empty($email_signin)){
            echo '<script type="text/javascript">alert("email left empty");
            window.location.href = "login.php";
            </script>';
        }

        if(empty($password_signin)){
            echo '<script type="text/javascript">alert("password left empty");
            window.location.href = "login.php";
            </script>';
        }
    }

}

?>
