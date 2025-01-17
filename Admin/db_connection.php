<?php
$servername = "localhost";  // Change this if necessary
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "sms";            // The name of your database

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
