<?php
include('server.php');
ini_set('display_errors',0);
$_duration = "";

if(isset($_POST["license_modal_submit"])) {
    $duration = $_POST["license_duration"];
    $activation_date = date('Y-m-d H:i:s');
    $generated_license = generate_uuid();

    //var_dump($activation_date);die();

    // PHP validation
    // Verify if form values are not empty
    if(!empty($duration)){

            // clean the form data before sending to database
            $_duration = mysqli_real_escape_string($connection, $duration);
            $sql = "INSERT INTO licenses (license, activation_date, duration, product_id, user_id, state) VALUES ('{$generated_license}','{$activation_date}','{$_duration}', '{$_SESSION['sessionProductID']}','{$_SESSION[id]}', '1');";
            $sqlQuery = mysqli_query($connection, $sql);

            if(!$sqlQuery){
                die("MySQL query failed!" . mysqli_error($connection));
            }

            header("location: products.php");

            }
    else {
        echo '<script type="text/javascript">alert("Duration can not be blank");
        window.location.href = "products.php";
        </script>';
    }
}
function generate_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0C2f ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
    );

}
?>