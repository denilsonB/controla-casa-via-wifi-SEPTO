<?php
$servername = "";#host
$username = "";#usuario
$password = "";#senha
$dbname = "";#nome do banco

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>