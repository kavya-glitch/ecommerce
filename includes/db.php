<?php
$servername = "localhost"; 
$username   = "root"; 
$password   = ""; 
$dbname     = "ecommerce"; 

// First connect
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Then check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

