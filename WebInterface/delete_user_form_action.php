<?php
include('server.php');

$_user_to_delete = "";

if(isset($_POST["delete_user_submit"])) {
    $user_to_delete = $_POST['selected_user'];

    $_user_to_delete = mysqli_real_escape_string($connection, $user_to_delete);

    $admin_query = "DELETE FROM users WHERE username = '{$_user_to_delete}'";
    $admin_rows = mysqli_query($connection, $admin_query);

    if(!$admin_rows){
        die("MySQL query failed!" . mysqli_error($connection));
    }
    
    header("location: admin_panel.php");
}
else{
    echo '<script type="text/javascript">alert("Something went wrong!");
    window.location.href = "admin_panel.php";
    </script>';
}
?>