<?php 
include('server.php');
ini_set('display_errors',0);

$sessionCurrentProduct = $_POST['sessionCurrentProduct'];
$trimmedSessionCurrentProduct = substr($sessionCurrentProduct, 1,-1);

$join_query = "SELECT l.*, p.product_id, p.user_id, p.description_title FROM licenses l LEFT JOIN products p ON l.product_id = p.product_id WHERE p.user_id = $_SESSION[id] AND description_title  = '$trimmedSessionCurrentProduct' ";
$join_rows = mysqli_query($connection, $join_query);
$join_row = mysqli_fetch_assoc($join_rows);

$session_ProductID_query = "SELECT product_id FROM products WHERE description_title  = '$trimmedSessionCurrentProduct' ";
$session_ProductID_rows = mysqli_query($connection, $session_ProductID_query);
$session_ProductID_row = mysqli_fetch_assoc($session_ProductID_rows);

$_SESSION['sessionProductID'] = $session_ProductID_row['product_id'];

if(mysqli_num_rows($join_rows) < 1){
    print nl2br("<div><h1>No License(s) found for this Product!");
    }
else { 
        print nl2br("<table id='license_table' class='w3-table w3-bordered w3-border w3-centered' <thead><tr> <th> <th class = 'w3-xlarge'>$join_row[description_title] <th> <tr class = 'w3-large'> <th>License <th>HWID <th>Duration <tr>");
            foreach($join_rows as $unique_product) {
            print nl2br("<td> $unique_product[license] <td> $unique_product[hwid] <td> $unique_product[duration]");}
            
        };