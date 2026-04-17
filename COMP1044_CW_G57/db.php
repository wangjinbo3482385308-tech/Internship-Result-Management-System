<?php
$servername = "localhost";
$username = "root";       
$password = "root";      
$dbname = "internship_db";

//reate connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection 
if ($conn->connect_error) {
    die("Invalid connection: " . $conn->connect_error);
} 

echo " ";
?>
