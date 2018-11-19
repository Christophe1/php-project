<?php

//we need to deal with these situations:

//2. A user uninstalls Populisto. What do I do here? Delete all records etc? Back it up somewhere?

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//***************************************************
require('dbConnect.php');

//this is the username, the logged-in user, in the user table
//$Number = $_POST['phonenumberofuser'];
$Number = "+353873525613";
//$Number2 = "10227";
//$Number3 = "666";

			//$review_id = "";
			//$cat_id  = "";

//$Number = "+353872934480";
// get the username of the user in the user table, then get the matching user_id in the user table
				// so we can check contacts against it 
				$query = "SELECT user_id FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			//get the matching user_id
			while ($row = $result->fetch_assoc()) {
			//this is the user_id in the user table of the user
			$user_id = $row["user_id"];
			}
			echo "user_id is: " . $user_id . "<br>";
			
			
//now post all contacts in my phone as a JSON array
//It will look something like [{"phone_number":"+3538745465381","name":"Tom"},{"phone_number":"+35385...etc...

$json = '[{"phone_number":"+3538745465381","name":"+353 87 454 65381"},{"phone_number":"+353851841344","name":"0851841344"},{"phone_number":"+34631898397","name":"Alex Kariginsky"},{"phone_number":"+33647370605","name":"Alex Nike"},{"phone_number":"+3538520987","name":"Ann Curry Darndale"},{"phone_number":"+35318558586","name":"Ann White"},{"phone_number":"+353864024923","name":"anto football"},{"phone_number":"+353906486300","name":"Axa Motor Rescue"},{"phone_number":"+353872497348","name":"Babette Harris"},{"phone_number":"+4915904455627","name":"Basak Germany"},{"phone_number":"+353857661772","name":"Bridget Courtney"},{"phone_number":"+353872930317","name":"Caitriona OKelly"},{"phone_number":"+353873606058","name":"Cheury Childminder"},{"phone_number":"+353872934480","name":"Christophe Per"},{"phone_number":"+353877649919","name":"Claire Harris Mobile"},{"phone_number":"+353874546538","name":"Claire Harris Mobile"},{"phone_number":"+61450692667","name":"Claire Mobile Oz"},{"phone_number":"+353877638356","name":"Daddy"},{"phone_number":"+353868782191","name":"Damian Football"},{"phone_number":"+353868118447","name":"Derek Peppard"},{"phone_number":"+353899407444","name":"Dmitry"},{"phone_number":"+353851111102","name":"Domo Amie"},{"phone_number":"+353874113618","name":"Donal Murray"},{"phone_number":"+353872139485","name":"Duty Manager Convergys"},{"phone_number":"+3536661234456","name":"Efpeters"},{"phone_number":"+353870617063","name":"Elise Nike"},{"phone_number":"+353862479142","name":"Eoin Quinn"},{"phone_number":"+353852239501","name":"Esther"},{"phone_number":"+61478914157","name":"Ewan"},{"phone_number":"+491739475188","name":"Florian Iconik"},{"phone_number":"+353879759716","name":"Fr Hannon"},{"phone_number":"+353863762628","name":"France Carr"},{"phone_number":"+353874176481","name":"Ger (And Der)"},{"phone_number":"+3535454","name":"Guff"},{"phone_number":"+353831371462","name":"Helen Mcdonald"},{"phone_number":"+35314970234","name":"Hilary Moloney"},{"phone_number":"+353866021869","name":"Ian Elliott"},{"phone_number":"+353872988755","name":"Inigo Viti"},{"phone_number":"+353868647715","name":"Irene"},{"phone_number":"+353864677745","name":"Jen Mob"},{"phone_number":"+35318394089","name":"Jen Mob"},{"phone_number":"+353872737","name":"Jen Visa Debit Card Pin"},{"phone_number":"+353872345465","name":"Jim Beam"},{"phone_number":"+353852155149","name":"Joan Mccann"},{"phone_number":"+353858716422","name":"John Collins"},{"phone_number":"+353867749714","name":"John Flashman"},{"phone_number":"+353851478849","name":"John kershaw"},{"phone_number":"+61418105717","name":"Justin"},{"phone_number":"+353874372987","name":"Justin"},{"phone_number":"+353857813277","name":"Karen Bunratty"},{"phone_number":"+353858377914","name":"Karen Cullen"},{"phone_number":"+353877529563","name":"Karl football"},{"phone_number":"+353852136355","name":"Keith Mcenaspy"},{"phone_number":"+353863366715","name":"Lala Nathalie"},{"phone_number":"+33466809489","name":"Le Grau Du Roi"},{"phone_number":"+353509758351","name":"Le Grau Test"},{"phone_number":"+353857435122","name":"Lennart Sobieka"},{"phone_number":"+35318295806","name":"lisa bassett cpl"},{"phone_number":"+353871390535","name":"Lisa Quinn"},{"phone_number":"+353872753024","name":"Lorcan"},{"phone_number":"+353872393473","name":"Louis Mobile"},{"phone_number":"+353861265483","name":"Louise Durkin"},{"phone_number":"+48697557153","name":"Lucaz Iconik"},{"phone_number":"+353851842697","name":"Mandy Brennan"},{"phone_number":"+353871272698","name":"Marcin Dettlaf"},{"phone_number":"+353858668490","name":"Mark Kelly"},{"phone_number":"+353872867692","name":"Mark O Donoghue"},{"phone_number":"+353858618711","name":"Martin O Connor"},{"phone_number":"+35318425315","name":"Mary And Patricia Nolan"},{"phone_number":"+353872434871","name":"Mary Reville"},{"phone_number":"+353872304207","name":"Michael Byrne"},{"phone_number":"+353872306559","name":"Michael Caulfield"}]';


//$json = '[{"phone_number":"12345","name":"Bob"},{"phone_number":"67890","name":"Sally"},{"phone_number":"11223344","name":"Jim"},{"phone_number":"987654","name":"Marge"}]';

//$json = $_POST['phonenumberofcontact'];
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

				echo "contact_id is: " . $contact_id . "," . "<br>";
				}
				
				//Make sure public reviews of contacts are visible to the logged-in user.
				//CHECK REVIEW TABLE FOR reviews made by contacts of the logged-in user, get the
				//public (public_or_private = 2) ones
				$query6 = "SELECT * FROM review WHERE public_or_private = 2 AND user_id = ?";
				$stmt6 = $con->prepare($query6) or die(mysqli_error($con));
				$stmt6->bind_param('i', $contact_id) or die ("MySQLi-stmt binding failedd ".$stmt6->error);
				$stmt6->execute() or die ("MySQLi-stmt execute failed ".$stmt6->error);
			    $result6 = $stmt6->get_result();
			    $stmt6->close();
				
				while ($row = $result6->fetch_assoc()) {
					
					//get the associated review_id column value
					$review_id = $row['review_id'];
					
					//get the associated cat_id column value
					$cat_id = $row['cat_id'];
					echo "review id is " . $review_id . "and cat_id is " . $cat_id . "<br>";
				
				//For reviews of contacts of logged-in user that are public in review table, check if 
				//logged-in user is a contact, in the review_shared table
				$query8 = "SELECT * FROM review_shared WHERE review_id = ? AND user_id = ? AND contact_id = ?";
				$stmt8 = $con->prepare($query8) or die(mysqli_error($con));
				$stmt8->bind_param('iii', $review_id, $contact_id, $user_id) or die ("MySQLi-stmt binding failed ".$stmt8->error);
				$stmt8->execute() or die ("MySQLi-stmt execute failed ".$stmt8->error);
			    $result8 = $stmt8->get_result();
			    $stmt8->close();
				
				//while ($row = $result8->fetch_assoc()) {
					
					//get the associated cat_id column value
					//$cat_id = $row['cat_id'];
					//echo $cat_id . ",";
				//}
				
								If ($result8->num_rows == 0) {
					
				echo "True dude" . "<br>";
				echo "Problem cat_id" . $cat_id . "<br>";
				echo "Problem review_id" . $review_id . "<br>";
				echo "Problem contact_id" . $contact_id . "<br>";
				echo "Problem user_id" . $user_id . "<br>";
				echo "Problem Number" . $Number . "<br>";
				
				//echo "review id is " . $review_id . "and cat_id is " . $cat_id . "<br>";
				
				//If the logged-in user is not already in the contact_id column of the review_shared table, for that particluar review_id, then put him in...
   	 			$stmt9 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
				$stmt9->bind_param('iiiis', $cat_id, $review_id, $contact_id, $user_id, $Number) or die ("MySQLi-stmt binding failed ".$stmt9->error);
				$stmt9->execute() or die ("MySQLi-stmt execute failed ".$stmt9->error);
				$stmt9->close();    
				}
				
				
				}
				
				

				
				
				

			//}
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
				
				//encode the contact ids in the contacts table of this user
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

				//delete any unnecessary shared reviews in the review_shared table,
				//if a contact uninstalls the app
/*                 $query10 = "DELETE FROM review_shared WHERE contact_id = ? AND user_id NOT IN ($id_list)";
				$stmt10 = $con->prepare($query10) or die(mysqli_error($con));
				$stmt10->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt10->error);
				$stmt10->execute() or die ("MySQLi-stmt execute failed ".$stmt10->error);
				$stmt10->close();  */				
					
				}
			}
			

		 
		 //output the matching numbers as a JSON array
	 			 	$json2 = json_encode($results);	
					
					//$json3 = json_encode($contact_id_results);	
					
					
					//print_r($contact_id_results);
					//echo $id_list . " " . $user_id;
           echo $json2;  

			  //  If ($result6->num_rows > 0) {
					
					 //echo $contact_id2;
					 //echo $Hash;

					 //$stmt->close();

				//}
				//else {
				//If the hash doesn't exist...
					//echo "False";
					//echo $Hash;
					//$stmt->close();
					//return false;
        //}

		   
		 //  echo $result3; 
		   
$stmt->close();
$stmt2->close();


		?>

		


		
	