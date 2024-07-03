<?php
session_start();
include 'db_connect.php';  // Include the database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // MD5 hashing for simplicity, use better hashing in production

    // Simple query without prepared statements
    $query = "SELECT id FROM users WHERE username='$username' AND password='$password'";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        echo "Invalid username or password";
    }

    $connection->close();
}
?>
