<?php
require('dbConnect.php');
$Number = $_POST['phonenumber'];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements
$query = $con->prepare("INSERT INTO user (username) VALUES (?)");
$query->bind_param('s', $Number);
$query->execute();

print_r($query);

?>