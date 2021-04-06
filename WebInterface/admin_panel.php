<?php
include('server.php');

$admin_query = "SELECT username, email, hwid, is_active from users";
$admin_rows = mysqli_query($connection, $admin_query);
$admin_row = mysqli_fetch_assoc($admin_rows);
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

<!-- Main Content -->
<div style="margin-left:18%; margin-top:7%" class="w3-responsive w3-container w3-center">
    <h1>Users</h1>
    <div style="margin-right: 4%">
    <table id="license_table" class="w3-table w3-bordered w3-border w3-centered">
            <tr class = "w3-large">
                   <th>Username</th>
		   <th></th>
                   <th>E-Mail</th>
		   <th></th>
                   <th>Activated</th>
            </tr>
            <tr>
            <?php foreach($admin_rows as $unique_user) {?>
                <td><?php echo $unique_user['username']?></td>
                <td></td>
                <td><?php echo $unique_user['email']?></td>
                <td></td>
                <td><?php if($unique_user['is_active'] == 1){echo "yes";} else {echo "no";}?></td>
            </tr>
            <?php }?>
        </table>
        </div>
            <div style="text-align: center; margin-top: 1%" class="w3-large">
            <button id = "delete_user_btn" name="delete_user_btn" class="w3-button w3-hover-red" style="margin-left: 1%; background-color: #EE494A; border: 1px solid black; width:auto; text-align: center;">Delete User</button>
            </div>
</div>
<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-top w3-large" style="width:15%;font-weight:bold; background-color: #222222; overflow:visible" id="mySidebar"><br>
    <div style="padding-bottom: 30px; text-align: center" class="w3-container">
        <img src="moga_logo.png" alt="logo" style="width: 210px;height: 110px;">
    </div>
    <div id= "myButtons" class="w3-bar-block" style="font-size: x-large;">
        <a id = "dashboard-page" href="dashboard.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Dashboard</a>
        <div class ="w3-dropdown-hover">
            <a id = "admin_panel" href="admin_panel.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn sideBarMarker">Admin Panel</a>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-bottom" style="100%;font-size:22px;">Close Menu</a>
</nav>

<!-- Delete-User-Modal -->
<div id="deleteUserModal" class="modal">
    <div class="modal-content" style="margin-left: 15%">
        <div class="modal-header">
            <span class="delete_User_modal_close w3-xxlarge" style="cursor: pointer">&times;</span>
            <h1>Select User To Delete</h1>
        </div>
        <form class="modal-body"  method="post" action="delete_user_form_action.php">
            <select style="padding: 3px; margin: 5px;" id="selected_user" name="selected_user">
                <?php foreach($admin_rows as $deletable_user){ ?>
                    <option id="user_to_delete" value="<?php echo $deletable_user['username'] ?>"><?php echo $deletable_user['username'] ?></option>
                <?php }?>
            </select><br>
            <button style="padding: 3px; margin: 5px;" id="delete_user_submit" name="delete_user_submit" type="submit">Delete User</button>
        </form>
    </div>
</div>
</body>
</html>

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

<!-- Delete User -->
<script>
    var deleteUserModal = document.getElementById("deleteUserModal");

    var deleteUserModalFinished = document.getElementById("delete_user_submit")

    var deleteBtn = document.getElementById("delete_user_btn");

    var deleteSpan = document.getElementsByClassName("delete_User_modal_close")[0];

    deleteBtn.onclick = function() {
        deleteUserModal.style.display = "block";
    }

    deleteSpan.onclick = function() {
        deleteUserModal.style.display = "none";
    }

    deleteUserModalFinished.onclick = function() {
        deleteUserModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == deleteUserModal) {
            deleteUserModal.style.display = "none";
        }
    }
</script>
