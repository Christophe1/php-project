
<?php

require('dbConnect.php');

//this is the username, the logged-in user, in the user table
//$Number = $_POST['phonenumberofuser'];

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
			
	
//post all usernames as a JSON array
$json = $_POST['phonenumberofcontact'];
//decode the JSON into PHP language, will look something like ["username"] => "111" etc
$array = json_decode($json);

//We want to get the corresponding user_id
 $query = "SELECT * FROM user WHERE username = ?";
 //sanitize the query
 $stmt2 = $con->prepare($query) or die(mysqli_error($con));
 //post the username values from Android 
 $stmt2->bind_param('s', $phonenumberofcontact) or die ("MySQLi-stmt binding failed ".$stmt2->error);
 
 //make the result of the query, $results, into an array
 $results = array();
 
 //for each value of username in our json_decode, call it $username
	foreach ($array as $value)
	{
		$phonenumberofcontact = $value->phone_number;

		$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);

		$result2 = $stmt2->get_result(); 
		
		//So, above, we will have our $results array - where there 
		//is a match between the posted JSON and usernames in the user table. 
		
			//we want to get the corresponding user_ids in user table of usernames
	        while ($row = $result2->fetch_assoc()) {
				
			//call this user_id contact_id
			$contact_id = $row['user_id'];

			 //$results of the matching usernames will be of the form [{"usernameMatch":"+123456"}, etc...]
			 $results[] = array('usernameMatch' => $row['username']);
			 
				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				if ($stmt3 = $con->prepare($query3) or die(mysqli_error($con))) {
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			    $stmt3->close();
				}
				
				//insert matching usernames into the contacts table
			    If ($result3->num_rows == 0) {
				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
			
				}
				
								}
								

			}
				
		//if $results is empty then delete all rows in contacts table where user_id = 10269		
		if (!($results)) {
					
					echo "it's empty";
 				$query4a = "DELETE FROM contacts WHERE user_id = ?";
				$stmt4a = $con->prepare($query4a) or die(mysqli_error($con));
				$stmt4a->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt4a->error);
				$stmt4a->execute() or die ("MySQLi-stmt execute failed 8".$stmt4a->error);
				$stmt4a->close();  
					
 			} 
			
			//else {
				
			//	echo json_encode($results);
				
/* 				//insert matching usernames into the contacts table
			    If ($result3->num_rows == 0) {
				$stmt4 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt4->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
				$stmt4->close();
			
				} */
				
						
		   
//$stmt->close();
$stmt2->close();
		?>

		