<?php
$servername = "localhost:3306";
$username = "root";
$password = "Lucky@123";
$dbname = "rock_paper_scissors";

// Create connection
$Conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($Conn->connect_error) {
  die("Connection failed: " . $Conn->connect_error);
}
// echo "Connected successfully";
?>
