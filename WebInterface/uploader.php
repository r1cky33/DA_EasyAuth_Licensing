<?php
include('server.php');

$target_path = "product_binaries/";

$raw_file_name = substr_replace(basename( $_FILES['uploadedfile']['name']), "", -4, 4);

$hash_name = md5(basename( $raw_file_name . microtime()));

$target_path = $target_path . $hash_name;


if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "The file ". basename( $_FILES['uploadedfile']['name']).
        " has been uploaded";

    //creating hash from filename

    $sql = "UPDATE products SET name ='" . $hash_name . "' WHERE name = '"  . $raw_file_name . "'";

    $sqlQuery = mysqli_query($connection, $sql);

    if(!$sqlQuery){
        die("MySQL query failed!" . mysqli_error($connection));
    }

} else{
    echo "There was an error uploading the file, please try again!";
}
