<?php
include('server.php');
include('product_form.php');

$query = "SELECT * FROM products WHERE user_id = '{$_SESSION['id']}'";
$rows = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($rows);
?>

<!DOCTYPE html>
<html lang="en">
<title>EasyAuth licensing</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>

    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        -webkit-animation-name: fadeIn; /* Fade in the background */
        -webkit-animation-duration: 0.4s;
        animation-name: fadeIn;
        animation-duration: 0.4s
    }

    /* Modal Content */
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

    /* The Close Button */
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


    /* Add Animation */
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
        z-index: 1;
    }

    .dropdown-content a{
        display:block;
        color: whitesmoke;
    }

    .show{
        display:block;
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
                       <div class="bar2"></div>
                  </div>
                  <div id="myDropdown" class="dropdown-content">
                      <a class="links">Profile</a>
                      <a href="logout.php" class="links">Logout</a>
                   </div>
            </div>
        </p>
    </header>
</div>

<!-- Main content -->
<form enctype="multipart/form-data" style ="background-color: #EE494A; margin-left: 80%; margin-top: 3%" class ="w3-large">Upload your Software here</form>
<div style="margin-left: 80%; margin-top: 6%">
<form enctype="multipart/form-data" action="uploader.php" method="POST">
    Choose a file to upload: <input name="uploadedfile" type="file" /><br />
    <input type="submit" value="Upload File" />
</form>
</div>
<div style="margin-left:18%; margin-top:7%" class="w3-responsive w3-container">
    <table class="w3-table w3-bordered w3-border w3-centered">
       <thead class ="w3-xlarge">
           <tr>
                <th> </th>
                <th><?php echo $row['description_title'] ?></th>
                <th> </th>
            </tr>
     </thead>
     <tr>
            <th>License</th>
            <th>HWID</th>
            <th>Duration</th>
        </tr>
        <tr>
            <td>test</td>
            <td>test</td>
            <td>69 days remaining</td>
        </tr>
        <tr>
            <td>another test</td>
            <td>another test</td>
            <td>123 days remaining</td>
        </tr>
    </table>
    <div style="text-align: center; margin-top: 1%" class="w3-large">
        <button style="margin-right: 5%; background-color: #EE494A">Add</button>
        <button style="margin-right: 5%; background-color: #EE494A"">Modify</button>
        <button style="margin-right: 5%; background-color: #EE494A"">Delete</button>
    </div>
</div>
<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-top w3-large" style="z-index:3;width:18%;font-weight:bold; background-color: #222222;" id="mySidebar"><br>
    <div style="padding-left: 8px;padding-bottom: 30px; position: center" class="w3-container">
        <img src="moga_logo.png" alt="logo" style="width: 210px;height: 110px;">
    </div>
    <div id= "myButtons" class="w3-bar-block" style="font-size: x-large;">
        <a id = "dashboard-page" href="dashboard.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Dashboard</a>
        <div class ="w3-dropdown-hover ">
            <a id = "products-page" href="products.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn sideBarMarker">Products</a>
            <div class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="xyz" style="background-color: #222222; color: whitesmoke; padding-bottom: 10px; text-align: center;" class ="w3-bar-item w3-button w3-hover-red">product xyz</a>
                <a href="123" style="background-color: #222222; color: whitesmoke; padding-bottom: 10px; text-align: center;" class ="w3-bar-item w3-button w3-hover-red">product 123</a>
                <button id= "newproduct" name="new_product" >+ new product</button>
            </div>
        </div>
        <a id = "news-page" href="news.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">News</a>
        <a id = "support-page" href="support.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Support</a>
    </div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-bottom" style="100%;font-size:22px;">Close Menu</a>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
    <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()">☰</a>
    <span>EasyAuth</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:340px;margin-right:40px"></div>

<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="margin-left: 18%">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h1>add new product</h1>
        </div>
        <form class="modal-body"  method="post" action="product_form.php">
            <input style="padding: 3px; margin: 5px;" id="product_name" name="product_name" placeholder="product name"><br>
            <input style="padding: 3px; margin: 5px;" id="description_title" name="description_title" placeholder="description title"><br>
            <textarea style="padding: 3px; margin: 5px;" id="description_text" name="description_text" placeholder="description text" cols="24"></textarea><br>
            <select style="padding: 3px; margin: 5px;" id="architecture" name="architecture">
                <option value="nativex64">Native x64 (C/C++)</option>
                <option value="clr">CLR (C#)</option>
            </select><br>
            <button style="padding: 3px; margin: 5px;" id="submit_form" name="submit_form" type="submit">Add Product</button>
        </form>
    </div>

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

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get submit button
    var finished = document.getElementById("submit_form")

    // Get the button that opens the modal
    var btn = document.getElementById("newproduct");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal when finished
    finished.onclick = function() {
        modal.style.display = "none;"
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<script>

    function openMyDropdown(){
        const dropdown = document.getElementById('myDropdown');
        dropdown.classList.toggle('show');
    }

    window.onclick = function(event) {
        // Make sure ".hamburger" or any other class is included so when it is clicked it won't hide the dropdown
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
