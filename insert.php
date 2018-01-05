<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('dbConnect.php');

$test = $_POST['jsonarray'];


$data = json_decode($test);
//$array = json_decode($test);

//$data = $array->myarray;
//$data = $array->myarray;

$id_list = implode(",", array_map(function ($val) { return (int) $val->id; }, $data));

//mysqli_query($con, "DELETE FROM test_table WHERE name NOT IN ($id_list)");

$query5 = "DELETE FROM test_table WHERE name NOT IN ($id_list)";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt5->close(); 

echo $test;

echo $id_list; 

//var_dump $obj;
//print_r($array);
print_r($data);

/* [ { "id": 1, "name": "Harry" },     { "id": 2, "name": "Ron" },     { "id": 3, "name": "Hermione" },     { "id": 4, "name": "Neville" } ]

[ { "id": 1 },     { "id": 2},     { "id": 3 },     { "id": 4 } ] */

//**************THIS IS FOR INSERTING A PHONE NUMBER INTO USER TABLE *************************

//post the phone number of the user, which in the table is username
//$Number = $_POST['phonenumberofuser'];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// check to see if the username exists in the user table
/* 				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   //if the username is not already in the user table, then put him in
			   //the other value in the table, user_id, is auto incremented, so it is inserted automatically
			    If ($result->num_rows == 0) {
				$stmt2 = $con->prepare("INSERT INTO user (username) VALUES(?)") or die(mysqli_error($con));
				$stmt2->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error); */
				//$result2 = $stmt2->get_result(); 
				//}
				
//print_r($stmt);

?>

