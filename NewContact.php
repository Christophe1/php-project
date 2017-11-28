

<?php

	require('dbConnect.php');
	   
	//we are making a new contact, so to do this we can post the user_id of the user making the contact
	//to do this, post the phone number of the user, which in the user table is username, and get associated details

	//received from app, the phonenumber, which in the DB is username
	$Number = $_POST['phonenumberofuser'];

				//now we need to get the matching user_id

				// check the username in the user table and get the matching user_id
				$query2 = "SELECT * FROM user WHERE username = ?";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();
				
				while ($row = $result2->fetch_assoc()) {
				//get the corresponding user_id in the row
				//this is the matching user_id in the user table of the user
				$user_id = $row["user_id"];
				//echo $user_id;
				}
			
			
				//let's see if the category being created exists already in the category table.
				//If yes, take that category id and put it in the review table.
				//If no, create the category in the category table and then put it into the review table.

				//received from app
				$category = $_POST["category"];

				// The ? below are parameter markers used for variable binding
				// AUTO_INCREMENT does not need prepared statements

				// select to see if the category exists in the category table
				
				//$query = "SELECT COUNT(*) AS rowcount FROM category WHERE cat_name = ?";
				$query = "SELECT * FROM category WHERE cat_name = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   //if the category is not already in the category table, then put it in there.
			   //we also need to put in the user_id in the category table - so we can know who made the category.
			   //The cat_id is AUTO_INCREMENT, so it is put in the category table automatically
			   
			   
				//so, check to see if the category already exists in the category table. If it doesn't exist, then 
				//put the values in the category table...
			    If ($result->num_rows == 0) {
							$stmt3 = $con->prepare("INSERT INTO category (cat_name, user_id) VALUES(?,?)") or die(mysqli_error($con));
							$stmt3->bind_param('si', $category, $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
							$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
							//last_id is the last AUTO_INCREMENT id inserted into the category table; that is, cat_id
							$last_id = $con->insert_id;
							echo "the category doesn't exist" . "\n";
							}
				
					else {
					
							//if it exists don't insert it into category table but we need the cat_id that
							//is already there
							
							// check the cat_name in the category table and get the matching cat_id
							$query4 = "SELECT * FROM category WHERE cat_name = ?";
							$stmt4 = $con->prepare($query4) or die(mysqli_error($con));
							$stmt4->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt4->error);
							$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
							$result4 = $stmt4->get_result();
				
							while ($row = $result4->fetch_assoc()) {
							//get the corresponding cat_id in the row
							//this is the matching cat_id in the category table of cat_name
							$last_id = $row["cat_id"]; 
							echo "the category does indeed exist" . "\n";
							//echo $user_id;
								}
								//echo $category;
					
							}

					 //received from app

					$name = $_POST["name"];
					$phone = $_POST["phone"];
					$address = $_POST["address"];
					$comment = $_POST["comment"];
					
					//this is if the review is public or private
					$public_or_private = $_POST["public_or_private"];
					
					//If it already exists in the category table, then just put it only in the review table. 
					//review_id is AUTO_INCREMENT, so it is put into the review table automatically.
					//this is the last inserted auto increment, which is cat_id
					
					$stmt = $con->prepare("INSERT INTO review (cat_id, cat_name, user_id, name, phone, address, comment, public_or_private ) VALUES(?,?,?,?,?,?,?,?)") or die(mysqli_error($con));
					$stmt->bind_param('isissssi', $last_id, $category, $user_id, $name, $phone, $address, $comment,$public_or_private) or die ("MySQLi-stmt binding failed ".$stmt->error);
					$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error); 
					//last_id2 is the last AUTO_INCREMENT id inserted into the review table; that is, review_id
					$last_id2 = $con->insert_id;
		
					//**************INSERT INTO review_shared******************
					
					//for each time a contact in the listview is checked we want to put it in the review_shared table 
					//the review_shared_id (which is AUTO_INCREMENT), the cat_id, the review_id, the corresponding user_id, the
					//contact_id, and the username, which is the checkedcontact in Android				
									
					//review_shared_id is AUTO_INCREMENT
					//we need the cat_id, which is $last_id
					//we need the review_id, which is $last_id2
					//we need the user_id, which is $user_id
					//we need the contact_id, which is $user_id
					
					//post the json array of checked contacts, checkedContacts, from NewContact in my phone to this php page. 
					//Let's call the php-side json array $jsoncheckedContacts
					//$_POST['checkedContacts'] is Android side, of the form [{"checkedContact":"+3531234567"},{"checkedContact":"+353868132813"}]
					$jsoncheckedContacts = $_POST['checkedContacts'];
					//decode the JSON array
					$arraycheckedContacts = json_decode($jsoncheckedContacts);
					
							 //for each checkedcontact posted from Android call it $checkedContact
							foreach ($arraycheckedContacts as $value)
					
								{
									
								//and the value we want to extract from the JSON Array is called checkedContact (it's the only key
								//in the JSON Array). JSONArray posted from Android is of the form, 
								//[{"checkedContact":"+353123456"},{"checkedContact":"+353567890"}....]
								$checkedContact = $value->checkedContact;
					
								//So $checkedContact will be a phone number, like +353872934480. Now we want to get the corresponding 
								//user_id in the user table for the phone number, and we want to insert this as contact_id into review_shared
								
								// select the username in the user table and get the matching user_id
								$query5 = "SELECT * FROM user WHERE username = ?";
								$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
								$stmt5->bind_param('s', $checkedContact) or die ("Binding failed on $checkedContact ".$stmt5->error);
								$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
								$result5 = $stmt5->get_result();
				
								while ($row = $result5->fetch_assoc()) {
								//get the corresponding user_id in the row
								//this is the matching user_id in the user table of username
								$contact_id = $row["user_id"];
								$checkedContact = $row["username"];
								//echo $user_id;
								
								}
			
					$stmt3 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
					$stmt3->bind_param('iiiis', $last_id, $last_id2, $user_id, $contact_id,$checkedContact) or die ("MySQLi-stmt binding failed ".$stmt3->error);
					$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
					}

			
					echo $jsoncheckedContacts; 
					
		?>