<?php
include('server.php');

if(isset($_SESSION['id'])){
	unset($_SESSION['id']);
	header('location:login.php');
}
else{
	header('location:login.php');
}

