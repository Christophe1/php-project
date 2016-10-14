<?php

define ('HOST', 'localhost');
define ('USER', 'root');
define ('PASS', '');
define ('DB', 'populisto');

$con = NEW MySQLi("localhost", "root", "", "populisto");

if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//$con = mysqli_connect("localhost", "root", "", "populisto") or die('Unable to Connect');
// echo 'dbConnect is working';
?>