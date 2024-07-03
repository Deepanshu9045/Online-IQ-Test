<?php
// Database credentials
$hostname = "localhost";  // Change this if your database is hosted elsewhere
$username = "root";  // Replace with your MySQL username
$password = "";  // Replace with your MySQL password
$database = "iqtest";  // Replace with your MySQL database name

// Create connection
$connection = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
