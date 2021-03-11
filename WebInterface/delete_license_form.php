<?php
include('server.php');
ini_set('display_errors',0);
$selected_license = "";

if(isset($_POST["delete_license_modal_submit"])) {
    $selected_license = $_POST["selected_license_to_delete"];

    // PHP validation
    // Verify if form values are not empty
    if(!empty($selected_license)){

            // clean the form data before sending to database
            $_selected_license = mysqli_real_escape_string($connection, $selected_license);

            $sql = "DELETE FROM licenses WHERE license = '{$_selected_license}'";
            $sqlQuery = mysqli_query($connection, $sql);

            if(!$sqlQuery){
                die("MySQL query failed!" . mysqli_error($connection));
            }

            header("location: products.php");

            }
    else {
        echo '<script type="text/javascript">alert("Something went wrong with the selected License");
        window.location.href = "products.php";
        </script>';
    }
}
?>