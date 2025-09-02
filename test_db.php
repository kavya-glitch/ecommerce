<?php

require_once __DIR__ . '/includes/db.php';
$servername = "sql100.infinityfree.com";   // Found in cPanel > MySQL Databases
$username   = "if0_39819719";              // Found in cPanel > MySQL Databases
$password   = "Kavya6652";      // The password you used when creating the DB
$dbname     = "if0_39819719_ecommerce"; 
$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Failed to connect to the database.";
}
?>