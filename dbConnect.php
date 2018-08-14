<?php

define ('HOST', 'localhost');
define ('USER', 'root');
define ('PASS', 'monkeynuts');
define ('DB', 'populisto');

$con = NEW MySQLi("localhost", "root", "monkeynuts", "populisto");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 

/* $con = mysqli_connect("localhost", "root", "", "populisto") or die('Unable to Connect'); */

?>