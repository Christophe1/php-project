<?php
require('dbConnect.php');
$Number = $_POST['phonenumber'];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// check to see if the username exists in the user table
				$query = "SELECT username FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   //if the username is not already in the user table, then put him in
			    If ($result->num_rows == 0) {
				$stmt2 = $con->prepare("INSERT INTO user (username) VALUES(?)") or die(mysqli_error($con));
				$stmt2->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
				}

print_r($stmt);

?>

