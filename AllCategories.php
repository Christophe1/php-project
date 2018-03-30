<?php

require('dbConnect.php');

//this is me, my username in the user table
//$Number = $_POST['phonenumberofuser'];
$Number = "+353872934480";

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
				
			while ($row = $result->fetch_assoc()) {
				//get the corresponding user_id in the row
			$user_id = $row["user_id"];
			
			//here is the user_id, which is the corresponding user_id for username Joe Blogs
			//echo $user_id;
			}

$sql = "SELECT cat_name FROM category WHERE user_id = ?";

$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
$result2 = $stmt2->get_result();

while ($row[] = $result2->fetch_assoc()) {
 $data = $row;
 
 $json = json_encode($data);

} 
	
	echo $json;
	
?>