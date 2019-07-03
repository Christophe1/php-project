<?php
require('dbConnect.php');
//this is the username, the logged-in user, in the user table
/* $Number = $_POST['phonenumberofuser'];

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
			} */

$user_id = 10303;			
	
//post all phone numbers on my phone, taken from Android, as a JSON array
$json = $_POST['phonenumberofcontact'];
//decode the JSON into PHP language, will look something like ["username"] => "0871234567" etc
$array = json_decode($json);
//We want to get the corresponding user_id of the posted phone number
 $query = "SELECT * FROM user WHERE username = ?";
 //sanitize the query
 $stmt2 = $con->prepare($query) or die(mysqli_error($con));
 //post the phone numbers of contacts from Android 
 $stmt2->bind_param('s', $phonenumberofcontact) or die ("MySQLi-stmt binding failed ".$stmt2->error);
 
 //make the result of the query, $results, into an array
 $results = array();
 
 //for each phone number of contact posted from Android, and decoded, call it $username
	foreach ($array as $value)
	{
		$phonenumberofcontact = $value->phone_number;
		//get the usernames (phone numbers) that match phone numbers in our posted JSON, jsonArrayAllPhonesandNamesofContacts
		$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
		$result2 = $stmt2->get_result(); 
		
		//So, above, we will have our $results array - where there 
		//is a match between the posted JSON and usernames in the user table. 
		
		//**********************
		
			//we want to get the corresponding user_ids in user table of usernames
	        while ($row = $result2->fetch_assoc()) {    //while 1.
				
			//call this user_id $contact_id
			$contact_id = $row['user_id'];
			
			//foreach ($array as $value)... for each number in phone contacs (like 0872345678), 
			//if they are also present in the username column of user table
			if(!empty($row['username'])) {
				
			 //here we get the user_id in the user table, it
			 //will be of the form [{"contact_id":"1"},{"contact_id":"27"}, etc...]

			 $contact_id_results[] = array('contact_id' => $contact_id);
			 //$results of the matching usernames will be of the form [{"usernameMatch":"+123456"}, etc...]
			 $results[] = array('usernameMatch' => $row['username']);
			 
			}

				
				//If a contact has been deleted from my phonebook and this contact is a user of the app:
				// If the contacts table contains a superfluous phone number that is not in the results[] JSON array posted
				// from my phone (results[] is matching contacts, those on my phone and users of the app) then delete 
				//those contact_ids from the contacts table.
				
				//encode the contacts in the contacts table of this user
				$json4 = json_encode($contact_id_results);
				//decode $json4, because our implode wasn't working otherwise
                $json5 = json_decode($json4);				
				//get the contact_id values as individual strings and call these $id_list
			    //$id_list = implode(",", array_map(function ($val) { return (int) $val->contact_id; }, $json5));
				
				$id_list = "10306, 10304, 10305";
				//delete any extra unnecessary contacts in the contacts table of logged-in user
/*                  $query5 = "DELETE FROM contacts WHERE user_id = ? AND contact_id NOT IN ($id_list)";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt5->error);
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
				
				$stmt5->close();  */
				
									
				//Objective:
				//When logged-in user removes a contact from their phonebook, check the reveiw_shared table if that contact
				//exists in the contact_id column for logged-in user and delete the relevant row 
				
				//delete any extra unnecessary contacts from review_shared table of logged-in user, except logged-in user's own contact_id
/*               $query6 = "DELETE FROM review_shared WHERE user_id = ? AND contact_id <> ? AND contact_id <> ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('iii', $user_id, $contact_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
				//$rc = mysql_affected_rows();
				$stmt6->close(); */   
				
				//delete any extra unnecessary contacts from review_shared table of logged-in user, except logged-in user's own contact_id
                   $query6 = "DELETE FROM review_shared WHERE user_id = ? AND contact_id NOT IN ($id_list)";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
				$stmt6->close();   
					
	
			
				  
 				
				//delete any extra unnecessary contacts from review_shared table of logged-in user, except logged-in user's own contact_id
/*                 $query6 = "DELETE FROM review_shared WHERE user_id = ? AND contact_id NOT IN ($id_list) AND contact_id <> ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('ii', $user_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
				$stmt6->close();   
				  */
				
								}     //end of while 1. 
								
			}
			
			
			              echo $json4;

              //echo $user_id;
			//echo $id_list;
			//echo json_encode($results);	
			//echo "Records deleted: " //. $rc;
			
			
		   
$stmt->close();
$stmt2->close();
		?>