<?php
//user_id_contacts_2_fk
//contacts_ibfk_1
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//***************************************************
require('dbConnect.php');

//this is me, my user_id in the user table
$Number = $_POST['phonenumberofuser'];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// get the username of the user in the user table, then get the matching user_id in the user table
				// so we can check contacts against it 
				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
				
			while ($row = $result->fetch_assoc()) {
			//this is the user_id in the user table of the user
           // echo $row['user_id']."<br />";
			$user_id = $row["user_id"];
			}
				
//echo $user_id . "blahblah";
//post all contacts in my phone as a JSON array
$json = $_POST['phonenumberofcontact'];
//decode the JSON
$array = json_decode($json);


//bind. We want to check if contacts in my phone are also users of the app. 
//if they are, then we want to put those phone contacts into the contacts table, as friends of user_id , the user of the app
 $query = "SELECT * FROM user WHERE username = ?";
 $stmt = $con->prepare($query) or die(mysqli_error($con));
 $stmt->bind_param('s', $phonenumberofcontact) or die ("MySQLi-stmt binding failed ".$stmt->error);
 //for each value of phone_number posted from Android, call it $phonenumberofcontact
	foreach ($array as $value)
	{
		$phonenumberofcontact = $value->phone_number;
		
$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
//store the result of contacts from the user's phonebook (that is, the result of the above query, $stmt) that are using the app
	 $result = $stmt->get_result(); 
	 


	 //In this while loop, check the $phonenumberofcontact in the user's phonebook and who are users of the app against
	 //the user's contacts table. Put the shared contacts in the contacts table for that user.
	        while ($row = $result->fetch_assoc()) {
			//this is the user_id in the user table of a contact in the user's phone
			//call this user_id contact_id
			$contact_id = $row['user_id'];
			
			//**********************************************************
			

			//make an array called $results
			//this is the username in the user table of a contact in the user's phone
			//call this username contact_phonenumber
	$results = array();
		
					$results[] = array(
		 'contact_phonenumber' => $row['username'], 
		 );
			
		 
	 			 	$json2 = json_encode($results);	
           echo $json2;


			//**********************************************************

				//make a select statement for contacts table where user_id = $user_id and contact_id = $contact_id.If
				//this value doesn't exist then put it in the contacts table
				
				$query3 = "SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?";
				$stmt3 = $con->prepare($query3) or die(mysqli_error($con));
				$stmt3->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			    $result3 = $stmt3->get_result();
			
			   //if the contact is not already in the contacts table, then put him in
			   //If there is nothing in the above result
			    If ($result3->num_rows == 0) {
				$stmt2 = $con->prepare("INSERT INTO contacts (user_id, contact_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt2->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
				$stmt2->close();
				}

	}

	}


//var_dump($_POST["phonenumberofcontact"]);
$stmt->close();

$stmt3->close();
		
		?>