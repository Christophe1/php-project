<?php

//we need to deal with these situations:

//1. A contact has been deleted from my phonebook. This contact is a user of the app. 
// If the contacts table contains a superfluous phone number that is not in the results[] JSON array posted
// from my phone (results[] is matching contacts, those on my phone and users of the app) then delete then delete
//those phone numbers/ records from the contacts table.

//2. A user uninstalls Populisto. What do I do here? Delete all records etc? Back it up somewhere?

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
			
			
//now post all contacts in my phone as a JSON array
$json = $_POST['phonenumberofcontact'];
//decode the JSON into PHP language, will look something like ["phone_number"] => "+353871234567"
$array = json_decode($json);

//*********************************************************

//We want to check if contacts of user_id are also users of the app.

//We will check if the phone contacts exist in the username column of the user table
 $query = "SELECT * FROM user WHERE username = ?";
 //sanitize the query
 $stmt2 = $con->prepare($query) or die(mysqli_error($con));
 //In the place of username in the user table, we will post the phonenumberofcontact values from Android 
 $stmt2->bind_param('s', $phonenumberofcontact) or die ("MySQLi-stmt binding failed ".$stmt2->error);
 
 //make the result of this query, $results, into an array
 $results = array();
 
 //for each value of phone_number in our json_decode - that is, a person in the phone contacts of user_id, call it $phonenumberofcontact
	foreach ($array as $value)
	{
		//for every value of phone_number from Android, call it $phonenumberofcontact
		$phonenumberofcontact = $value->phone_number;

		//execute the query, see which contacts on my phone are users of the App
		$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);

		//store the result of contacts from the user's phonebook (that is, the result of the above query, $stmt2) that are using the app
		$result2 = $stmt2->get_result(); 
		
		//So, above, now we have which contacts in the phone are also users of the app.

		//In this while loop, check the $phonenumberofcontact in the user's phonebook/ simultaneously users of the app, against
		//the user's contacts table. Put the matching contacts in the contacts table for that user, if they are not
		//there already. We need to regularly check and keep the contacts table updated.
	 
			//get the matching contacts - those in phone book and also using app. Let's see if contacts table is updated.
	        while ($row = $result2->fetch_assoc()) {
				
			//First, get corresponding user_id, in the user table, of a contact.
			//call this user_id contact_id
			//we will be using his for the contacts table, below
			$contact_id = $row['user_id'];
			
			//if there's a match, put the phone number in our array, $results
			//foreach ($array as $value)... for each person in the phone contacts, if they are also present in the username column
			if(!empty($row['username'])) {
			//$results of the matching contacts will be of the form [{"phone_number":"+123456"}, etc...]
			$results[] = array('phone_number' => $row['username']);
					}
					
			//make a select statement for contacts table where user_id = $user_id and contact_id = $contact_id. Check
			//if the number in the user's phone contacts is in the contacts table. We use this for updating contacts who
			//who may have been added or deleted into user's contacts phone book since the last time using the app
				
				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				$stmt3 = $con->prepare($query3) or die(mysqli_error($con));
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			
			   //if the contact is not already in the contacts table...
			   //if the $contact_id is not present with the $user_id value, then put him in the contacts table
			    If ($result3->num_rows == 0) {
				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
				}
					
					
				$id_list = implode(",", array_map(function ($val) { return (string) $val->phone_number; },
				$array));
				
				mysqli_query("DELETE FROM contacts WHERE phone_number NOT IN ($id_list)");

				
/* 				$query5 = "DELETE FROM contacts WHERE phone_number NOT IN ($id_list)";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt5->close(); */
				
				}
			}
		 
		 //output the matching numbers as a JSON array
	 			 	$json2 = json_encode($results);	
					
					
					
					
					
					
           echo $json2;   
		   
$stmt->close();
$stmt2->close();
$stmt3->close();


		?>

		


		
	