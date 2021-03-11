<?php 
include('server.php');
ini_set('display_errors',0);

$software_added = "No";

$sessionCurrentProduct = $_POST['sessionCurrentProduct'];
$trimmedSessionCurrentProduct = substr($sessionCurrentProduct, 1,-1);

$join_query = "SELECT l.*, p.product_id, p.user_id, p.name, p.software_name_hash FROM licenses l LEFT JOIN products p ON l.product_id = p.product_id WHERE p.user_id = $_SESSION[id] AND p.name  = '$trimmedSessionCurrentProduct'";
$join_rows = mysqli_query($connection, $join_query);
$join_row = mysqli_fetch_assoc($join_rows);

$session_ProductID_query = "SELECT product_id FROM products WHERE name  = '$trimmedSessionCurrentProduct' ";
$session_ProductID_rows = mysqli_query($connection, $session_ProductID_query);
$session_ProductID_row = mysqli_fetch_assoc($session_ProductID_rows);

if($join_row['software_name_hash']){$software_added = "Yes";}
    

$_SESSION['sessionProductID'] = $session_ProductID_row['product_id'];
if(mysqli_num_rows($join_rows) < 1){
    print nl2br("<div><h1>No License(s) found for this Product!");
    }
else {
        print nl2br("<h1 style = 'margin-top: 3%;'> $join_row[name] </h1> <p> Software Added: $software_added </p> <div style='margin-right:4%'> <table id='license_table' class='w3-table w3-bordered w3-border w3-centered w3-large' <tr class = 'w3-large'> <th>License <th>HWID <th>Duration <tr>");
            while($unique_product = mysqli_fetch_assoc($join_rows)){    
                echo "<tr><td>" . $unique_product['license'] . "</td><td>" . $unique_product['hwid'] . "</td><td>" . $unique_product['duration'] . "</td></tr>";} 
        //foreach($join_rows as $unique_product) {
                //print nl2br("<td> {$unique_product['license']} <td> {$unique_product['hwid']} <td> {$unique_product['duration']} </div> </table>");}
        //print nl2br("<div style='text-align: center; margin-top: 1%' class='w3-large'> <button id ='newLicenseButton' class='w3-button w3-hover-red' onclick='licenseModalFunc()' style='margin-right: 4%; background-color: #EE494A; border: 1px solid black; width:90px; text-align: center;'>Add</button><button id = 'deleteLicenseButton' class='w3-button w3-hover-red' onclick='deleteLicenseModalFunc()' style='background-color: #EE494A; border: 1px solid black; width:90px; text-align: center;'>Delete</button></div>");
        //print nl2br("<div style='text-align: center; margin-top: 1%' class='w3-large'> <button id = 'deleteLicenseButton' class='w3-button w3-hover-red' onclick='deleteLicenseModalFunc()' style='margin-left: 16%; background-color: #EE494A; border: 1px solid black; width:90px; text-align: center;'>Delete</button></div>");
        };