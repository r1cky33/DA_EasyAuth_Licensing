<?php

// Enable us to use Headers
ob_start();

// Set sessions
if(!isset($_SESSION)) {
    session_start();
}

$hostname = "db";
$username = "root";
$password = "";
$dbname = "ezauth";

$connection = mysqli_connect($hostname, $username, $password, $dbname) or die("Database connection not established.");
?>
