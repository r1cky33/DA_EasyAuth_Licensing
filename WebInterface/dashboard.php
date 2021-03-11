<?php
include('server.php');
include('product_form.php');

if (empty($_SESSION['id'])) {
    header('location: login.php');
}
$query = "SELECT p.*, u.id, u.username FROM products p LEFT JOIN users u on p.user_id = u.id WHERE p.user_id = '{$_SESSION['id']}'";
$rows = mysqli_query($connection, $query);

$admin_query = "SELECT * FROM users WHERE id = '{$_SESSION['id']}'";
$admin_rows = mysqli_query($connection, $admin_query);
$user_info = mysqli_fetch_array($admin_rows, MYSQLI_ASSOC);
$admin_true = $user_info['is_admin'];
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
                <a href="logout.php" class="links">Logout</a>
            </div>
        </div>
        </p>
    </header>
</div>

<!-- Main content -->
<h1 style ="text-align: center; margin-left: 18%">Welcome back, <?php echo $_SESSION['username'] ?>!</h1>
<div id ="table_updater" class="w3-row-padding w3-center w3-margin-top" style="margin-left:21%">
    <div class="w3-third">
        <div class="w3-card w3-container" style="min-height:160px; width: 350px; text-align: left">
            <h3>Latest news:</h3>
            <p>-) Soon we will launch this project!</p>
        </div>
    </div>

    <div class="w3-third">
        <div class="w3-card w3-container" style="min-height:160px; width: 350px; text-align: left;">
            <h3>Recent activities:</h3>
            <p>-) Advancing the WebInterface to make it ready for the launch!</p>
        </div>
    </div>

    <div class="w3-third">
        <div class="w3-card w3-container" style="min-height:160px; width: 350px; text-align: left">
            <h3>Version history:</h3>
            <p>0.1 : Prototype finished</p>
        </div>
    </div>
</div>
<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-top w3-large" style="width:15%;font-weight:bold; background-color: #222222; overflow:visible" id="mySidebar"><br>
    <div style="padding-bottom: 30px; text-align: center" class="w3-container">
        <img src="moga_logo.png" alt="logo" style="width: 210px;height: 110px;">
    </div>
    <div id= "myButtons" class="w3-bar-block" style="font-size: x-large;">
        <a id = "dashboard-page" href="dashboard.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn sideBarMarker">Dashboard</a>
        <div class ="w3-dropdown-hover">
        <?php if($admin_true == '1'){?>
                    <a id = "admin_panel" href="admin_panel.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Admin Panel</a>
                <?php }
            else{   ?>
            <a id = "products-page" href="products.php" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Products</a>
            <div id="myProductList" method="POST" class="w3-dropdown-content w3-bar-block w3-card-4" style="position: absolut; left:100%; top:0;">
                <?php 
                    foreach($rows as $unique_product){ ?>
                        <button style="background-color: #222222; color: whitesmoke; padding-bottom: 10px; text-align: center; width: 400px;" 
                        class ="w3-bar-item w3-button w3-hover-red table_updater" onClick="location.href='products.php';" id= "list_product" name="list_product" type="submit" 
                        value = "<?php echo $unique_product['name'] ?>"><?php echo $unique_product['name'] 
                        ?></button>
                <?php   }
                ?>
             </div>
        </div>
        <a id = "news-page" href="news.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">News</a>
        <a id = "support-page" href="support.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Support</a>
    </div>
    <?php }?>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-bottom" style="100%;font-size:22px;">Close Menu</a>
</nav>

<!-- Top menu on small screens 
<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
    <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()">☰</a>
    <span>EasyAuth</span>
</header>

Overlay effect when opening sidebar on small screens
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:340px;margin-right:40px">
-->

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
/* 
    function showActivePage(evt, button) {
        var i, x, btn;
        x = document.getElementsByClassName("btn");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        btn = document.getElementsByClassName("btn");
        for (i = 0; i < x.length; i++) {
            btn[i].className = btn[i].className.replace("sideBarMarker", "");
        }
        document.getElementById(button).style.display = "block";
        evt.currentTarget.className += "sideBarMarker";
    } */
</script>
<!-- Logout -->
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
</body>
</html>