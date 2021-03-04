<?php
include('server.php');
include('product_form.php');
include('license_form.php');

if (empty($_SESSION['id'])) {
    header('location: login.php');
}

$query = "SELECT * FROM products WHERE user_id = '{$_SESSION['id']}'";
$rows = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($rows);

$license_query = "SELECT * FROM licenses WHERE product_id = '{$row['product_id']}'";
$license_rows = mysqli_query($connection, $license_query);
$license_row = mysqli_fetch_assoc($license_rows);

?>

<!DOCTYPE html>
<html lang="en">
<title>EasyAuth Licensing</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>

    .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4); 
        -webkit-animation-name: fadeIn; 
        -webkit-animation-duration: 0.4s;
        animation-name: fadeIn;
        animation-duration: 0.4s
    }

    .modal-content {
        position: fixed;
        bottom: 0;
        background-color: #fefefe;
        width: 100%;
        -webkit-animation-name: slideIn;
        -webkit-animation-duration: 0.4s;
        animation-name: slideIn;
        animation-duration: 0.4s
    }

    .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-header {
        padding: 2px 16px;
        background-color: #EE494A;
        color: #222222;
    }

    .modal-body {
        padding: 2px 16px;
    }


    @-webkit-keyframes slideIn {
        from {bottom: -300px; opacity: 0}
        to {bottom: 0; opacity: 1}
    }

    @keyframes slideIn {
        from {bottom: -300px; opacity: 0}
        to {bottom: 0; opacity: 1}
    }

    @-webkit-keyframes fadeIn {
        from {opacity: 0}
        to {opacity: 1}
    }

    @keyframes fadeIn {
        from {opacity: 0}
        to {opacity: 1}
    }

    .fa-sort-down{
        cursor: pointer;
        color: #222222;
        list-style: none;
        position: relative;
        display: inline-block;
    }

    .links{
        color: whitesmoke;
        background-color: #222222;
        display: block;
        text-decoration: none;
    }

    .links:hover{
        color: #222222;
        background-color: #EE494A;
        text-decoration: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
        padding-top: 20px;
    }

    .dropdown-content{
        display: none;
        position: absolute;
    }

    .dropdown-content a{
        display:block;
        color: whitesmoke;
    }

    .show{
        display:block;
    }

    label{
        background-color: #EE494A;
        color: #222222;
        cursor: pointer;
    }

</style>

<body>

<!-- Top header-->
<div style="border-bottom: 1px solid black">
    <header class="w3-container w3-xlarge">
        <p class="w3-right">
                <i class="fa fa-user-circle" style ="font-size: 35px;"></i>
                <i><?php echo $_SESSION['username'] ?></i>
                <div class="dropdown w3-right">
                  <div class="fa fa-sort-down" onclick="openMyDropdown()">
                       <div class="bar1"></div>
                  </div>
                  <div id="myDropdown" class="dropdown-content">
                      <a href="logout.php" class="links">Logout</a>
                   </div>
            </div>
        </p>
    </header>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-top w3-large" style="width:15%;font-weight:bold; background-color: #222222; overflow:visible" id="mySidebar"><br>
    <div style="padding-bottom: 30px; text-align: center" class="w3-container">
        <img src="moga_logo.png" alt="logo" style="width: 210px;height: 110px;">
    </div>
    <div id= "myButtons" class="w3-bar-block" style="font-size: x-large;">
        <a id = "dashboard-page" href="dashboard.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Dashboard</a>
        <div class ="w3-dropdown-hover">
            <a id = "products-page" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn sideBarMarker">Products</a>
            <div id="myProductList" method="POST" class="w3-dropdown-content w3-bar-block w3-card-4" style="position: absolut; left:100%; top:0;">
                <?php 
                    foreach($rows as $unique_product){ ?>
                        <button style="background-color: #222222; color: whitesmoke; padding-bottom: 10px; text-align: center; width: 400px;" 
                        class ="w3-bar-item w3-button w3-hover-red table_updater" onClick="bitteGehJz()" id= "list_product" name="list_product" type="submit" 
                        value = "<?php echo $unique_product['description_title'] ?>"><?php echo $unique_product['description_title'] 
                        ?></button>
                <?php   }
                ?>
                <button class="w3-bar-item w3-button w3-hover-red" style="color: whitesmoke; text-align: center; background-color: #222222;" id= "new_product" name="new_product" >-- Add New Product --</button>
            </div>
        </div>
        <a id = "news-page" href="news.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">News</a>
        <a id = "support-page" href="support.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Support</a>
    </div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-bottom" style="100%;font-size:22px;">Close Menu</a>
</nav>

<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
    <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()">☰</a>
    <span>EasyAuth</span>
</header>

<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:340px;margin-right:40px"></div>

<!-- Product-Modal -->
<div id="myProductModal" class="modal">
    <div class="modal-content" style="margin-left: 15%">
        <div class="modal-header">
            <span class="product_modal_close w3-xxlarge" style="cursor: pointer">&times;</span>
            <h1>Add new Product</h1>
        </div>
        <form class="modal-body"  method="post" action="product_form.php">
            <input style="padding: 3px; margin: 5px;" id="product_name" name="product_name" placeholder="Product Name"><br>
            <input style="padding: 3px; margin: 5px;" id="description_title" name="description_title" placeholder="Description Title"><br>
            <textarea style="padding: 3px; margin: 5px;" id="description_text" name="description_text" placeholder="Description Text" cols="24"></textarea><br>
            <select style="padding: 3px; margin: 5px;" id="architecture" name="architecture">
                <option value="nativex64">Native x64 (C/C++)</option>
                <option value="clr">CLR (C#)</option>
            </select><br>
            <button style="padding: 3px; margin: 5px;" id="product_modal_submit" name="product_modal_submit" type="submit">Add Product</button>
        </form>
    </div>
</div>

<!-- License-Modal -->
<div id="myLicenseModal" class="modal">
    <div class="modal-content" style="margin-left: 15%">
        <div class="modal-header">
            <span class="license_modal_close w3-xxlarge" style="cursor: pointer">&times;</span>
            <h1>Add new License</h1>
        </div>
        <form class="modal-body"  method="post" action="license_form.php">
            <p class="w3-xlarge" style="padding-left: 3px; margin: 5px;" id="activation_date" name="activation_date"> Activation Date: <script> var my_date = new Date(); my_date.toISOString().split('T')[0]; document.write(my_date); </script></p><br>
            <p style="padding: 3px; margin: 5px;">End of license:</p><br>
            <input style="padding: 3px; margin: 5px;" id="license_duration" name="license_duration" placeholder="YYYY-MM-DD"><br>
            <button style="padding: 3px; margin: 5px;" id="license_modal_submit" name="license_modal_submit" type="submit">Add License</button>
        </form>
    </div>
</div>
<!-- Main content -->
    <!-- File Upload -->
<form enctype="multipart/form-data" action="uploader.php" method="POST" 
    style ="margin-left: 80%; margin-top: 3%;"
    class ="w3-large"><input id="my_uploadButton" name="uploadedfile" type="file" hidden/>
    <label class="w3-button w3-hover-red" style = "background-color: #222222; color:whitesmoke;" for="my_uploadButton">Select your Software here</label><br>
    <input class ="w3-large w3-button w3-hover-red" style="background-color: #EE494A; margin-left:18%;" type="submit" value="Upload File"/>
</form>

    <!-- Table -->
<div style="margin-left:18%; margin-top:7%" class="w3-responsive w3-container w3-center">
<?php 
        if(mysqli_num_rows($license_rows) < 1){ ?>
                <div>
                        <?php echo $row['description_title'] ?>
                        <h1>No License(s) found for this Product!</h1>
                    </div>
                   
<?php  } 
        
        else{ ?>
        <table id="license_table" class="w3-table w3-bordered w3-border w3-centered">
            <thead class = "w3-xlarge">
                <tr>
                     <th> </th>
                     <th><?php echo $row['description_title']?></th>
                     <th> </th>
                 </tr>
            </thead>
            <tr class = "w3-large">
                 <th>License</th>
                 <th>HWID</th>
                 <th>Duration</th>
            </tr>
            <tr>
            <?php foreach($license_rows as $unique_product) {?>
                <td><?php echo $unique_product['license']?></td>
                <td><?php echo $unique_product['hwid']?></td>
                <td><?php echo $unique_product['duration']?></td>
            </tr>
            <?php }?>
         </table>
         <div style="text-align: center; margin-top: 1%" class="w3-large">
             <button id ="newLicenseButton" class="w3-button w3-hover-red" onclick="licenseModalFunc()" style="margin-right: 5%; background-color: #EE494A; border: 1px solid black; width:90px; text-align: center;">Add</button>
             <button class="w3-button w3-hover-red" style="margin-right: 5%; background-color: #EE494A; border: 1px solid black; width:90px; text-align: center;">Modify</button>
             <button class="w3-button w3-hover-red" style="margin-right: 5%; background-color: #EE494A; border: 1px solid black; width:90px; text-align: center;">Delete</button>
         </div>
 <?php   }

?>
</div>
<script>
        function w3_open() {
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("myOverlay").style.display = "block";
        }

        function w3_close() {
            document.getElementById("mySidebar").style.display = "none";
            document.getElementById("myOverlay").style.display = "none";
        }

        function onClick(element) {
            document.getElementById("img01").src = element.src;
            document.getElementById("modal01").style.display = "block";
            var captionText = document.getElementById("caption");
            captionText.innerHTML = element.alt;
        }

</script>

<!-- Table-Update script -->
<!--
<script>
        $('#list_product').on('click', function bitteGehJz() {
                var currentProduct = $(this).val();
                $.ajax({
                url: 'update_license_table.php',               
                method: "GET",
                dataType: 'json',
                {sessionCurrentProduct:currentProduct},
                success: function(response) {
                    $('#license_table').html(response);
                }
            });       
        });
</script>
-->
<script>
        $(".table_updater").click(function bitteGehJz(){
            var currentProduct = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "update_license_table.php",
                    dataType: 'text',
                    data:{
                        sessionCurrentProduct: JSON.stringify(currentProduct)
                    },      
                    success: function(data){
                        $("#license_table").html(data);
                    }
                }); 
        });  
        /*var currentProduct = $(this).val();
                $.post("update_license_table.php",{sessionCurrentProduct: currentProduct},function(result){            
                    $('#license_table').text(result);
                }); */
                
                /*$('#list_product').on('click', function bitteGehJz(){
                        var currentProduct = $(this).val();
                        var url = "update_license_table.php";
                        $.post(url,{sessionCurrentProduct:currentProduct},function(data){            
                                $('#license_table').html(data);
                        });*/    
</script>

<!-- Product-Modal script -->
<script>
    var productModal = document.getElementById("myProductModal");

    var productFinished = document.getElementById("product_modal_submit")

    var productBtn = document.getElementById("new_product");

    var productSpan = document.getElementsByClassName("product_modal_close")[0];

    productBtn.onclick = function() {
        productModal.style.display = "block";
    }

    productSpan.onclick = function() {
        productModal.style.display = "none";
    }

    productFinished.onclick = function() {
        productModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == productModal) {
            productModal.style.display = "none";
        }
    }
</script>

 <!-- License-Modal script -->
 <script>
    function licenseModalFunc(){
        var licenseModal = document.getElementById("myLicenseModal");
        var licenseFinished = document.getElementById("license_modal_submit")
        var licenseBtn = document.getElementById("newLicenseButton");
        var licenseSpan = document.getElementsByClassName("license_modal_close")[0];
        licenseBtn.onclick = function() {
            licenseModal.style.display = "block";
        }
        licenseSpan.onclick = function() {
            licenseModal.style.display = "none";
        }
        licenseFinished.onclick = function() {
            licenseModal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == licenseModal) {
                licenseModal.style.display = "none";
                }
            }
    };
</script>

<script>

    function openMyDropdown(){
        const dropdown = document.getElementById('myDropdown');
        dropdown.classList.toggle('show');
    }

    window.onclick = function(event) {
        if (!event.target.matches('.fa-sort-down')) {
            var dropdowns = document.getElementsByClassName('dropdown-content');
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

</script>

</body>
</html>
