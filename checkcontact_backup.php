<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//***************************************************
require('dbConnect.php');

//this is the username in the user table
$Number = $_POST['phonenumberofuser'];
//$Number = "+353872934480";
// get the username of the user in the user table, then get the matching user_id in the user table
				// so we can check contacts against it 
				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			//get the matching user_id
			while ($row = $result->fetch_assoc()) {
			//this is the user_id in the user table of the user
			$user_id = $row["user_id"];
			}
			
//post all contacts in my phone as a JSON array
$json = $_POST['phonenumberofcontact'];
//decode the JSON
$array = json_decode($json);

//*********************************************************

//We want to check if contacts of user_id are also users of the app.
 $query = "SELECT * FROM user WHERE username = ?";
 $stmt2 = $con->prepare($query) or die(mysqli_error($con));
 $stmt2->bind_param('s', $phonenumberofcontact) or die ("MySQLi-stmt binding failed ".$stmt2->error);
 
 //this will be an array of matching numbers - users of the app and also those users who are phone contacts of user_id
 $results = array();
 
 //for each value of phone_number posted from Android - a person in the phone contacts of user_id, call it $phonenumberofcontact
	foreach ($array as $value)
	{
		$phonenumberofcontact = $value->phone_number;

$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);

//store the result of contacts from the user's phonebook (that is, the result of the above query, $stmt2) that are using the app
	 $result2 = $stmt2->get_result(); 

	 //In this while loop, check the $phonenumberofcontact in the user's phonebook and who are users of the app against
	 //the user's contacts table. Put the matching contacts in the contacts table for that user, if they are not
	 //there already
	 
	 //get the matching contacts
	        while ($row = $result2->fetch_assoc()) {
				
			//this is the user_id in the user table of a contact in the user's phone contacts
			//call this user_id contact_id
			$contact_id = $row['user_id'];
			
			//if there's a match, put the phone number in our array
			if(!empty($row['username'])) {
			$results[] = array('contact_phonenumber' => $row['username']);
					}
					
			//make a select statement for contacts table where user_id = $user_id and contact_id = $contact_id. Check
			//if the number in the user's phone contacts is in the contacts table
				
				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				$stmt3 = $con->prepare($query3) or die(mysqli_error($con));
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			
			   //if the contact is not already in the contacts table, then put him in
			   //if there is nothing in the above result
			    If ($result3->num_rows == 0) {
				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
				}
					
					
				}
			}
		 
		 //output the matching numbers as a JSON array
	 			 	$json2 = json_encode($results);	
           echo $json2;   
		   
$stmt->close();
$stmt2->close();
$stmt3->close();


		?>



		
	