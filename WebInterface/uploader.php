<?php
include('server.php');

$target_path = "product_binaries/";

$raw_file_name = substr_replace(basename( $_FILES['uploadedfile']['name']), "", -4, 4);

$hash_name = md5(basename( $raw_file_name . microtime()));

$target_path = $target_path . $hash_name;


if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

    $sql = "UPDATE products SET software_name_hash ='" . $hash_name . "' WHERE product_id = '"  . $_SESSION['sessionProductID'] . "'";
    $sqlQuery = mysqli_query($connection, $sql);

    if($sqlQuery){
        echo '<script type="text/javascript">alert("File has been uploaded!")
        window.location.href = "products.php";
        </script>';
    }

    if(!$sqlQuery){
        die("MySQL query failed!" . mysqli_error($connection));
    }

} else{
    echo '<script type="text/javascript">alert("There was an error uploading the file, please try again!")</script>';
}
