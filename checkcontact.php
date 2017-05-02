<?php
//user_id_contacts_2_fk
//contacts_ibfk_1
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//***************************************************
require('dbConnect.php');


//this is me, +567890123, my user_id in the user table
$user_id = 20;

//post all contacts in my phone as a JSON array
$json = $_POST['phonenumber'];
//decode the JSON
$array = json_decode($json);
//bind 
 $query = "SELECT * FROM user WHERE username = ?";
 $stmt = $con->prepare($query) or die(mysqli_error($con));
 $stmt->bind_param('s', $phonenumber) or die ("MySQLi-stmt binding failed ".$stmt->error);


 //for each value of phone_number in the array, call it $phonenumber
	foreach ($array as $value)
	{
		$phonenumber = $value->phone_number;
		
			//	$sql = "SELECT * FROM user WHERE username = '$phonenumber'";
		//$result = mysqli_query($con, $sql);
	
		//*****SECURE
$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
//$result = $con->($query);
//$result = $stmt->get_result(); 
//$stmt->store_result();
//$num_rows = $stmt->num_rows();
 //$result = mysqli_query($con, $query);
//echo $result;
 //if ($stmt->num_rows > 0) { // Check if there are any rows matching this value
	 $result = $stmt->get_result(); // Convert from MySQLi_stmt to MySQLi_result (to use fetch_assoc())
	//  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	// while($row = mysqli_fetch_assoc($result)){ 
	     echo "Number of rows matching username '".$value->phone_number."' from user-table is " . $result->num_rows  . " rows.<br>"; 
	// $contact_id = $row['user_id'];
	// echo $contact_id;
	//	echo "phonenumber is " . $phonenumber  . "<br>";
	//echo "number of phone numbers in the user table is " . $result->num_rows  . "<br>"; 
	        while ($row = $result->fetch_assoc()) {
			//this the the user_id in the user table of the matching phone number	
            echo $row['user_id']."<br />";
			//call this user_id contact_id
			$contact_id = $row['user_id'];
			
			//if ($stmt->num_rows == 0)
			
				$stmt2 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt2->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
	//$insert_into_contacts_table = mysqli_query($con,$insert_into_contacts_command);
			
	} //}
/* 	      else {
        echo "No rows matching username ".$value->phone_number.".<br />";
    } */
 }
// while ($row = $stmt->fetch_array(MYSQLI_ASSOC)) {
    // 

//}
 
 /* Bind results */
   // $stmt -> bind_result($testfield1);

    /* Fetch the value */
  //  $stmt -> fetch();

// $num_rows = $stmt->num_rows;
//*****
//echo "phonenumber is " . $phonenumber  . "<br>";

	
//	if($num_rows > 0) {

	//echo "phonenumber is " . $phonenumber  . "<br>";
 
//echo $phonenumber . "<br>";
//var_dump($result);
//var_dump($stmt);
//$num_rows = $stmt->num_rows();
//*****
/* 	}
	else {
		echo "no match";
	} */
//	}
//	echo $num_rows . "<br>";
var_dump($_POST["phonenumber"]);
$stmt->close();
//mysqli_close($con);	
/* 	
	else {
	var_dump($stmt);
	
	
	} */
		
		?>