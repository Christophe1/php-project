<?php
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
			}
			
	
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
				
			 //here we get the contact_id in the user table of matching contacts
			 //will be of the form [{"contact_id":"1"},{"contact_id":"27"}, etc...]
			 //below we will delete these from the contacts table, if they don't exist in the most updated 
			 //matching contacts.
			 $contact_id_results[] = array('contact_id' => $contact_id);
			 //$results of the matching usernames will be of the form [{"usernameMatch":"+123456"}, etc...]
			 $results[] = array('usernameMatch' => $row['username']);
			 
			}
			 
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
				
				//If a contact has been deleted from my phonebook and this contact is a user of the app:
				// If the contacts table contains a superfluous phone number that is not in the results[] JSON array posted
				// from my phone (results[] is matching contacts, those on my phone and users of the app) then delete 
				//those contact_ids from the contacts table.
				
				//encode the contacts in the contacts table of this user
				$json4 = json_encode($contact_id_results);
				//decode $json4, because our implode wasn't working otherwise
                $json5 = json_decode($json4);				
				//get the contact_id values as individual strings and call these $id_list
			    $id_list = implode(",", array_map(function ($val) { return (int) $val->contact_id; }, $json5));
				//delete any extra unnecessary contacts in the contacts table of logged-in user
                 $query5 = "DELETE FROM contacts WHERE user_id = ? AND contact_id NOT IN ($id_list)";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt5->error);
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
				
				$stmt5->close(); 
				
									
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
                   $query6 = "DELETE FROM review_shared WHERE user_id = ? AND contact_id NOT IN ($id_list) AND contact_id <> ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('ii', $user_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
				$stmt6->close();   
					
				//For reviews of contacts of logged-in user, check also if 
				//logged-in user is a contact of that contact in the contacts table.
			
				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				if ($stmt3 = $con->prepare($query3) or die(mysqli_error($con))) {
				$stmt3->bind_param('ii', $contact_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			    $stmt3->close();
				}
				
				//if yes...
				If ($result3->num_rows > 0) {
				
				//Make sure public reviews of contacts are visible to the logged-in user.
				//(We need to do this because, if logged-in user is in mobile phone as a contact, and logged-in user
				//has downloaded the app after contact has made the review 'public', they will not be checked for that review)
				//CHECK REVIEW TABLE FOR reviews made by contacts of the logged-in user, get the
				//public (public_or_private = 2) ones
				$query6 = "SELECT * FROM review WHERE public_or_private = 2 AND user_id = ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('i', $contact_id) or die ("MySQLi-stmt binding failedd ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
			    $result6 = $stmt6->get_result();
			    $stmt6->close();
				
				//while we have all public reviews by contacts of the logged-in user...
				while ($row = $result6->fetch_assoc()) {     //while 2.
					
					//get the associated review_id column value
					$review_id = $row['review_id'];
					
					//get the associated cat_id column value
					$cat_id = $row['cat_id'];
							
			//For reviews by contacts of logged-in user that are public in review table, check if 
			//logged-in user is a contact, in the review_shared table
				$query8 = "SELECT * FROM review_shared WHERE review_id = ? AND user_id = ? AND contact_id = ?";
				$stmt8 = $con->prepare($query8) or die(mysqli_error($con));
				$stmt8->bind_param('iii', $review_id, $contact_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt8->error);
				$stmt8->execute() or die ("MySQLi-stmt execute failed ".$stmt8->error);
			    $result8 = $stmt8->get_result();
			    $stmt8->close();
				
				//if not...
				If ($result8->num_rows == 0) {
									
				//If the logged-in user is not already in the contact_id column of the review_shared table, 
				//for that particluar review_id, then put him in the review_shared table
   	 			$stmt9 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
				$stmt9->bind_param('iiiis', $cat_id, $review_id, $contact_id, $user_id, $Number) or die ("MySQLi-stmt binding failed ".$stmt9->error);
				$stmt9->execute() or die ("MySQLi-stmt execute failed ".$stmt9->error);
				$stmt9->close();    
				}
				
				} 
				
				}
				
/* 				//if logged-in user has no contacts in the contacts table.
				If ($result3->num_rows == 0) {
				
				//delete rows in review_shared, as long as it is not 'Just U'
				$query5a = "DELETE FROM review_shared WHERE user_id = ? AND contact_id <> ?";
				$stmt5a = $con->prepare($query5a) or die(mysqli_error($con));
				$stmt5a->bind_param('ii', $user_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt5a->error);
				$stmt5a->execute() or die ("MySQLi-stmt execute failed 8".$stmt5a->error);
				$stmt5a->close();
				
				
				}      */
				
				
				
			//SITUATION: In logged-in user's phone, for his public reviews, we want his contacts 
			//to be checked, not empty. This can happen if a review is made before a contact 
			//downloads Populisto, or logged-in user makes a review public before putting new contact in their phone book.
			//So we need to add this contact to the review_shared table.
			//on startup of the app, when checkcontact.php is called, look at the public reviews of username, 
			//the logged-in user, in the review table. get the review_id where
			//pulic_or_private column is 2.
			//In the review_shared table for each of the above review_ids:
			//if $contact_id does not exist with this matching review_id then put him in, also put in the
			//other respective cells in the table
				$query10 = "SELECT * FROM review WHERE public_or_private = 2 AND user_id = ?";
				$stmt10 = $con->prepare($query10) or die(mysqli_error($con));
				$stmt10->bind_param('i', $user_id) or die ("MySQLi-stmt binding failedd ".$stmt10->error);
				$stmt10->execute() or die ("MySQLi-stmt execute failed ".$stmt10->error);
			    $result10 = $stmt10->get_result();
			    $stmt10->close();
				
				//while we have all public reviews of the logged-in user...
				while ($row = $result10->fetch_assoc()) {  //while 3
					
					//get the associated review_id column value
					$review_id = $row['review_id'];
					
					//get the associated cat_id column value
					$cat_id = $row['cat_id'];
					
					//In the review_shared table for each of the above review_ids, 
					//we want to see if all contacts are included for that particular review_id
				$query11 = "SELECT * FROM review_shared WHERE review_id = ? AND user_id = ? AND contact_id = ?";
				$stmt11 = $con->prepare($query11) or die(mysqli_error($con));
				$stmt11->bind_param('iii', $review_id, $user_id, $contact_id) or die ("MySQLi-stmt binding failedy ".$stmt11->error);
				$stmt11->execute() or die ("MySQLi-stmt execute failedy2 ".$stmt11->error);
			    $result11 = $stmt11->get_result();
			    $stmt11->close();
				
				If ($result11->num_rows == 0) {
													
				//((If the contact is not already in the contact_id column of the review_shared table, for that particluar review_id, then put him in the review_shared table))
   	 			$stmt12 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
				$stmt12->bind_param('iiiis', $cat_id, $review_id, $user_id, $contact_id, $phonenumberofcontact) or die ("MySQLi-stmt binding failed3 ".$stmt12->error);
				$stmt12->execute() or die ("MySQLi-stmt execute failed4 ".$stmt12->error);
				$stmt12->close();    
				}
				
				}  //end of while 3
				  
 				
				//delete any extra unnecessary contacts from review_shared table of logged-in user, except logged-in user's own contact_id
/*                 $query6 = "DELETE FROM review_shared WHERE user_id = ? AND contact_id NOT IN ($id_list) AND contact_id <> ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('ii', $user_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
				$stmt6->close();   
				  */
				
								}     //end of while 1. 
								
			}
			
			
			
			
			
				
		//if $results is empty, if logged-in user has no contacts at all, then delete all rows in contacts table of logged-in user	
		if (!($results)) {
					
					//no response;
 				$query4a = "DELETE FROM contacts WHERE user_id = ?";
				$stmt4a = $con->prepare($query4a) or die(mysqli_error($con));
				$stmt4a->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt4a->error);
				$stmt4a->execute() or die ("MySQLi-stmt execute failed 8".$stmt4a->error);
				$stmt4a->close();
				
			
				//also delete contacts in review_shared, aart from JUST U
				$query5a = "DELETE FROM review_shared WHERE user_id = ? AND contact_id <> ?";
				$stmt5a = $con->prepare($query5a) or die(mysqli_error($con));
				$stmt5a->bind_param('ii', $user_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt5a->error);
				$stmt5a->execute() or die ("MySQLi-stmt execute failed 8".$stmt5a->error);
				$stmt5a->close();
					
 			} 
			echo  $id_list;
			//echo json_encode($results);	
			//echo "Records deleted: " //. $rc;
			
			
		   
$stmt->close();
$stmt2->close();
		?>