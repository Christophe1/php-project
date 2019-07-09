<?php

//this is the username, the logged-in user, in the user table
$user_id = 10303;
	
//post all phone numbers on my phone, taken from Android, as a JSON array
$json = $_POST['phonenumberofcontact'];
//$json = '[{"phone_number":"+353872934480"}, {"phone_number":"+353872934482"}]';


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
 
  //$results = '[{"usernameMatch":"+353872934480"}, {"usernameMatch":"+353872934482"}]';
  //$results = json_decode($results);
  
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
				
			//call this user_id contact_id
			$contact_id = $row['user_id'];

			 //here we get the contact_id in the user table of matching contacts
			 //will be of the form [{"contact_id":"1"},{"contact_id":"27"}, etc...]
			 //below we will delete these from the contacts table, if they don't exist in the most updated 
			 //matching contacts.
			 $contact_id_results[] = array('contact_id' => $contact_id);
			 //$results of the matching usernames will be of the form [{"usernameMatch":"+123456"}, etc...]
			 $results[] = array('usernameMatch' => $row['username']);
			 
			//} 
			 
			   //Check if the contact_id which we got from the user table, for logged-in user, is in the contacts table. 
			   //We use this for updating contacts who
			   //who may have been added or deleted into logged-in user's contacts phone book since the last time using the app
 				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				if ($stmt3 = $con->prepare($query3) or die(mysqli_error($con))) {
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			    $stmt3->close();
				} 
				
				//insert matching contact_ids into the contacts table, if not there already
			    If ($result3->num_rows == 0) {
				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
			
				}

				//encode the contacts in the contacts table of this user
				$json4 = json_encode($contact_id_results);
				//$json4  = '[{"contact_id": 10306}, {"contact_id": 10304}, {"contact_id": 10305}]';
				//decode $json4, because our implode wasn't working otherwise
                $json5 = json_decode($json4);				
				//get the contact_id values as individual strings and call these $id_list
			    $id_list = implode(",", array_map(function ($val) { return (string) $val->contact_id; }, $json5));
				//echo "The numbers are " . $id_list;
				//$id_list = "10306, 10304, 10305";
				//delete any extra unnecessary contacts in the contacts table of logged-in user
                $query5 = "DELETE FROM contacts WHERE user_id = ? AND contact_id NOT IN ($id_list)";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt5->error);
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
				$stmt5->close();
					
				
				//delete any extra unnecessary contacts from review_shared table of logged-in user, except logged-in user's own contact_id
                $query6 = "DELETE FROM review_shared WHERE user_id = ? AND contact_id NOT IN ($id_list) AND contact_id <> ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('ii', $user_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
				$stmt6->close();   
				
				
				
								}     //end of while 1. 
								
			}
			
			echo "The numbers are " . $id_list;	

$stmt2->close();
		?>