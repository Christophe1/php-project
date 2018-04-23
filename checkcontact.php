<?php

//we need to deal with these situations:

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

//We want to check if contacts of user_id, the posted phonenumberofcontacts, are also users of the app.

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
		
		//So, above, now we have matching contacts: contacts in the phone who are also users of the app (user_names in user table). 

		//This while loop is to make sure the contacts table is constantly updated.
		//check the $phonenumberofcontact in the user's phonebook/ simultaneously users of the app, against
		//the user's contacts table. Put the matching contacts in the contacts table for that user, if they are not
		//there already. We need to regularly check and keep the contacts table updated.
	 
			//we want to get the corresponding user_ids in user table of matching contacts - those in phone book 
			//and also using app. 
	        while ($row = $result2->fetch_assoc()) {
				
			//First, get corresponding user_id, in the user table, of a contact.
			//call this user_id contact_id
			//we will be using this for the contacts table, below
			$contact_id = $row['user_id'];
			
			//foreach ($array as $value)... for each person in the phone contacts, if they are also present in the username column
			if(!empty($row['username'])) {
			
			 //here we get the contact_id in the contacts table of matching contacts
			 //will be of the form [{"contact_id":"1"},{"contact_id":"27"}, etc...]
			 //below we will delete these from the contacts table, if they don't exist in the most updated 
			 //matching contacts.
			 $contact_id_results[] = array('contact_id' => $contact_id);

			 //$results of the matching contacts will be of the form [{"phone_number":"+123456"}, etc...]
			 $results[] = array('phone_number' => $row['username']);
					}
					
			//make a select statement for contacts table where user_id = $user_id and contact_id = $contact_id. Check
			//if the number in the user's phone contacts is in the contacts table. We use this for updating contacts who
			//who may have been added or deleted into user's contacts phone book since the last time using the app
				
				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				if ($stmt3 = $con->prepare($query3) or die(mysqli_error($con))) {
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			    $stmt3->close();

				}
				
				
			   //if the contact is not already in the contacts table...
			   //if the $contact_id is not present with the $user_id value, then put him in the contacts table
			    If ($result3->num_rows == 0) {
				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
				}
					
                
				//If a contact has been deleted from my phonebook and this contact is a user of the app:
				// If the contacts table contains a superfluous phone number that is not in the results[] JSON array posted
				// from my phone (results[] is matching contacts, those on my phone and users of the app) then delete 
				//those phone numbers/ records from the contacts table.
				
				//encode the contacts in the contacts table of this user
				$json4 = json_encode($contact_id_results);
				//decode $json4, because our implode wasn't working otherwise
                $json5 = json_decode($json4);				

				//get the contact_id values as individual strings and call these $id_list
			    $id_list = implode(",", array_map(function ($val) { return (int) $val->contact_id; }, $json5));

				//delete any extra unnecessary contacts in the contacts table for this user
                $query5 = "DELETE FROM contacts WHERE user_id = ? AND contact_id NOT IN ($id_list)";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt5->error);
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
				$stmt5->close();  
					
				}
			}
		 
		 //output the matching numbers as a JSON array
	 			 	$json2 = json_encode($results);	
					
					//$json3 = json_encode($contact_id_results);	
					
					
					//print_r($contact_id_results);
					//echo $id_list . " " . $user_id;
           echo $json2;   
		   
$stmt->close();
$stmt2->close();


		?>

		


		
	