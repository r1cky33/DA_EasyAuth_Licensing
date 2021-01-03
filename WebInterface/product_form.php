<?php
include('server.php');

$_product_name = $_descr_title = $_descr_text = $_architecture = "";

if(isset($_POST["submit_form"])) {
    $product_name     = $_POST['product_name'];
    $descr_title         = $_POST['description_title'];
    $descr_text     = $_POST['description_text'];
    $architecture = $_POST['architecture'];

    // PHP validation
    // Verify if form values are not empty
    if(!empty($product_name) && !empty($descr_title) && !empty($descr_text) && !empty($architecture)){

            // clean the form data before sending to database
            $_product_name = mysqli_real_escape_string($connection, $product_name);
            $_descr_title = mysqli_real_escape_string($connection, $descr_title);
            $_descr_text = mysqli_real_escape_string($connection, $descr_text);
            $_architecture = mysqli_real_escape_string($connection, $architecture);

            if($_POST['architecture'] == 'nativex64'){
                $_architecture = '1';
            }
            else{
                $_architecture = '2';
            }

            $sql = "INSERT INTO products (user_id, name, description_title, description_text, architecture, state) VALUES ('{$_SESSION['id']}','{$_product_name}','{$_descr_title}','{$_descr_text}', '{$_architecture}', '1');";

            $sqlQuery = mysqli_query($connection, $sql);

            if(!$sqlQuery){
                die("MySQL query failed!" . mysqli_error($connection));
            }

            header("location: products.php");

            }
    else {
        if(empty($_product_name)){
            echo '<script type="text/javascript">alert("product name can not be blank")</script>';
            die();
        }
        if(empty($_descr_title)){
            echo '<script type="text/javascript">alert("description title can not be blank")</script>';
            die();
        }
        if(empty($_descr_text)){
            echo '<script type="text/javascript">alert("description can not be blank")</script>';
            die();
        }
        if(empty($_architecture)){
            echo '<script type="text/javascript">alert("architecture can not be blank")</script>';
            die();
        }
    }
}
?>