		
		<?php

//we need to deal with these situations:

//2. A user uninstalls Populisto. What do I do here? Delete all records etc? Back it up somewhere?

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//***************************************************
require('dbConnect.php');

//this is the username, the logged-in user, in the user table
$Number = $_POST['phonenumberofuser'];
//$Number = "+353872934480";
// get the username of the user in the user table, then get the matching user_id in the user table
				// so we can check contacts against it 
				$query = "SELECT user_id FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed 1 ".$stmt->error);
			    $result = $stmt->get_result();
			
			//get the matching user_id
			while ($row = $result->fetch_assoc()) {
			//this is the user_id in the user table of the user
			$user_id = $row["user_id"];
			//echo "hello hello hello";
			}
			
			
//now post all contacts in my phone as a JSON array
//It will look something like [{"phone_number":"+3538745465381","name":"Tom"},{"phone_number":"+35385...etc...
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
 
 //for each value of phone_number in our json_decode - that is, a person in the mobile phone contacts of user_id, call it $phonenumberofcontact
	foreach ($array as $value)
	{
		//for every value of phone_number from Android, call it $phonenumberofcontact
		$phonenumberofcontact = $value->phone_number;

		//execute the query, see which contacts on my phone are users of the App
		$stmt2->execute() or die ("MySQLi-stmt execute failed 2 ".$stmt2->error);

		//store the result of app users from the user's phonebook (that is, the result of the above query, $stmt2) that are using the app
		$result2 = $stmt2->get_result(); 
		
		//So, above, now we have matching contacts: contacts in the phone who are also users of the app (user_names in user table). 

		//*************************************
		
		//This while loop is to make sure the contacts table is constantly updated.
		//check the $phonenumberofcontact in the user's phonebook/ simultaneously users of the app, against
		//the user's contacts table. Put the matching contacts in the contacts table for that user, if they are not
		//there already. We need to regularly check and keep the contacts table updated.
	 
			//we want to get the corresponding user_ids in user table of matching contacts - those in phone book 
			//and also using app. While we have the phone numbers of matching contacts get their corresponding user_ids...
	        while ($row = $result2->fetch_assoc()) {
				
			$contact_id = $row['user_id'];
			//echo $contact_id;
						
			
						
			//for every phone number/username in the user table, like +353872943370....
			if(!empty($row['username'])) {
			 
			 //make an array of contacts who use the app and are phone contacts of the user
			 //get the corresponding $contact_id ($row['user_id']) in user table
			 //the array will be of the form [{"contact_id":"1"},{"contact_id":"27"}, etc...]
			 $contact_id_results[] = array('contact_id' => $contact_id);
			 //get the matching usernames/ phone numbers. will be of the form [{"phone_number":"+123456"}, etc...]
			 $results[] = array('phone_number' => $row['username']);
			 
			 
			 
	}
			

			
 				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				if ($stmt3 = $con->prepare($query3) or die(mysqli_error($con))) {
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed 3 ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			    $stmt3->close();
				} 
						 					

											
			   //if the contact is not already in the contacts table...
			   //if the $contact_id is not present with the $user_id value, then put him in the contacts table
			    If ($result3->num_rows == 0) {
					
				echo json_encode($contact_id_results);

				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
				}
			 

 			} 
			
	}

	//echo json_encode($results);	
 
			
	
$stmt->close();
$stmt2->close();


		?>

		

	
		


		
	