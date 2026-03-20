<?php

$servername = "localhost";
$username = "root";       
$password = "root";      
$dbname = "internship_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Invalid connection: " . $conn->connect_error);
} 


echo " ";
?>
