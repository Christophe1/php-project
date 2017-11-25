<?php
require('dbConnect.php');

				//received from app
				//first of all, we need to see if the updated category name already exists in the db.
				//This determines everything else
				$category = $_POST["category"];
				
				 //received from app
				//this is the review_id of the review the user is editing
				$Review_id = $_POST['review_id'];
				//$Number = "51";
				
				//received from app
				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
				
				//this is if the review is public or private
				$public_or_private = $_POST["public_or_private"];
				
				
				// check to see if the BEING-UPDATED-TO CATEGORY to exists in the category table
				$query = "SELECT * FROM category WHERE cat_name = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
				//get the result, if it doesn't exist, the result is 0
			    $result = $stmt->get_result();

			
				// get the current review_id being updated in the review table, then get the matching fields in the row
				$query2 = "SELECT * FROM review WHERE review_id = ?";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();
			
				while ($row = $result2->fetch_assoc()) {
				//get the corresponding user_id and cat_id in the review table row of the current cat_id
				$user_id = $row["user_id"];
				$cat_name= $row["cat_name"];
				$cat_id = $row["cat_id"];
				
				//echo "category being updated-to: " . $category . "\n";
				//echo "cat id" . $cat_id . "\n";
				//echo "review id" . $Review_id . "\n";
				}
			
					//SCENARIO 1 ************* 
					//If the BEING-UPDATED-TO CATEGORY name doesn't exist in the category table...
					If ($result->num_rows == 0) {
					
					//we might replace the CURRENT CAT_NAME with the being-updated-to cat_name, 
					//no point having an old unused one lingering about
					
					//but first...
										
					//check if other reviews with CURRENT CAT_NAME have been created.
					//we can get this info in the review_shared table
					
					//check to see if the CURRENT CAT_NAME is being used in more than just this review being updated
					//we are counting distinct review_ids matching cat_id 123 (or whatever) and where review_id is 
					//not this current review
					$query3 = "SELECT COUNT(DISTINCT review_id) AS review_count FROM review_shared WHERE cat_id = ? AND review_id <> ?";
					$stmt3 = $con->prepare($query3) or die(mysqli_error($con));
					$stmt3->bind_param('ii', $cat_id, $Review_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
					$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
					$result3 = $stmt3->get_result();
					$row = mysqli_fetch_array($result3) or die(mysqli_error());
					$review_count = $row['review_count'];
					//echo the number of users other than $user_id who are using the current category
					echo "review count:" . $review_count . "\n";
					
							//If $review_count, the number of reviews the CURRENT CAT_NAME is being used in,
							//is 0, then the cat_id is only being used in this current review_id 
							
					 		If ($review_count==0) {
								
							//SCENARIO 1.1 
							//The CURRENT CAT_NAME is only being used in this current review_id
							//so we can update it in the category table and review table
							//no point having it lingering about
								
							// update the current cat_name, no point having an old unused one lingering about
						 	$stmt5 = $con->prepare("UPDATE category SET cat_name=? WHERE cat_id=?") or die(mysqli_error($con));
							$stmt5->bind_param('si', $category, $cat_id ) or die ("MySQLi-stmt binding failed ".$stmt5->error);
							$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error); 
							
							//echo "category updated succesfully" . "\n";
							
							//also update the current review
							$stmt4 = $con->prepare("UPDATE review SET cat_name=?, name=?, phone=?, address=?, comment=?, public_or_private=? WHERE Review_id=?") or die(mysqli_error($con));
							$stmt4->bind_param('sssssii', $category, $name, $phone, $address, $comment, $public_or_private, $Review_id ) or die ("problem here ".$stmt4->error);
							$stmt4->execute() or die ("problem here ".$stmt4->error); 
			
							echo "review updated succesfully" . "\n";
							
							//also update the review_shared table
							
							//**************INSERT INTO review_shared******************
					
					//for each time a contact in the listview is checked we want to put it in the review_shared table :
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
					
					echo "checked contacts are " . $jsoncheckedContacts;
					
					
		/* 			//decode the JSON array
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
							 */
							
							
							
								
									}
									
							////SCENARIO 1.2 		
							//If $review_count is more than 0, that is, CURRENT CAT_NAME is being used in more than 
							//just this current review, then we need to create a new cat_name and 
							//cat_id for the BEING-UPDATED-TO CATEGORY
							else {
								
							//If the CURRENT CAT_NAME is being used in more reviews than this current review then create a new category for the BEING-UPDATED-TO CATEGORY
							//Remember: Cat_id is auto-increment so no need to put that in our query
							$stmt3 = $con->prepare("INSERT INTO category (cat_name, user_id) VALUES(?,?)") or die(mysqli_error($con));
							$stmt3->bind_param('si', $category, $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
							$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
							//$result2 = $stmt2->get_result();
							//this is the last auto increment value, cat_id, which we need to put into the review table
							$last_id = $con->insert_id;
								
							//also update the current review, including the cat_id of the BEING-UPDATED-TO CATEGORY, above  
							$stmt4 = $con->prepare("UPDATE review SET cat_id=?, cat_name=?, name=?, phone=?, address=?, comment=? WHERE Review_id=?") or die(mysqli_error($con));
							$stmt4->bind_param('isssssi', $last_id, $category, $name, $phone, $address, $comment, $Review_id ) or die ("MySQLi-stmt binding failed ".$stmt4->error);
							$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error); 
			
							//also update the review_shared table
							

							}		
									
									
									
									
							}
							
					//SCENARIO 2 ************* 
					//If the BEING-UPDATED-TO CATEGORY name does exist already in the category table...
							
							If ($result->num_rows > 0) {
								
								
							echo "howaya!!!!" . "\n";
							
							
							}
							
							
							
							
								//REDUNDANT BELOW I THINK
								//SCENARIO 1.2 The current cat_name is not being shared with anybody by anybody but 
								//maybe it is being used privately - it exists in the category table,
								//or maybe $user_id is using the current cat_name in other reviews
								//Check to see if current cat_name exists in the category table.
								
								/*
								
								$query = "SELECT * FROM category WHERE cat_name = ?";
								$stmt = $con->prepare($query) or die(mysqli_error($con));
								$stmt->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt->error);
								$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
								//get the result, if it doesn't exist, the result is 0
								$result = $stmt->get_result();
								echo $cat_id; */

								
/* 								//search the category table for the current cat_name. 
								$stmt5 = $con->prepare("UPDATE category SET cat_name=?,cat_id=? WHERE cat_id=?") or die(mysqli_error($con));
								$stmt5->bind_param('si', $category, $cat_id ) or die ("MySQLi-stmt binding failed ".$stmt5->error);
								$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error); 
								
									
							//}
							
							
							
					
				//	}
					


				
				//echo "nothing with the updated-to category";
				//echo "the review_shared result is " . $user_id2;

				//}
			
				//SCENARIO 2 *************
				//if the updated-to category name already exists in the category table...
/* 				else {
					
				//don't insert anything into the category table, but get the already existing cat_name's matching cat_id
				while ($row = $result->fetch_assoc()) {
				//get the corresponding cat_id in the row
				//this is the matching cat_id in the category table of cat_name
			    $cat_id2 = $row["cat_id"];

				}
				//update the review with updated-to category name and the cat_id, and also update the other review details
				$stmt = $con->prepare("UPDATE review SET cat_id=?, cat_name=?, name=?, phone=?, address=?, comment=? WHERE Review_id=?") or die(mysqli_error($con));
				$stmt->bind_param('isssssi', $cat_id2, $category, $name, $phone, $address, $comment, $Review_id ) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
				//$result2 = $stmt2->get_result();
				
				echo "it already exists " . $cat_id2;
				} */
				
			

	



				
			   //if the category is not already in the category table, then put it in there.
			   // Get the cat_id and user_id from the review table
			   
			  
	
	//$json = json_encode($review);
	//echo $category . " h " . $user_id .  " e " .$cat_id;
//echo $Review_id . " " . $category . " " .  $name . " " .  $phone . " " .  $address . " " .  $comment;
//echo $json;
//echo $Number;	
			
/* 				//If the updated category name doesn't exist in the category table....
			    If ($result->num_rows == 0) {
				//If the old category has nobody using it, simply update the category name, keeping the same cat_id
				
				//to do this : select from review_shared, if the cat_id is used only by current_user
				//then keep the cat_id and update the category name
				
				//select from review_shared, if the cat_id is used by more than just current_user
				//then add a new cat_id and category name
				
				

				

					
				//then put it in */
			
			   
			   
			   
			   
			   //we also need to put in the user_id  - so we can know who made the category.
			   //The cat_id is auto increment, so it is put in automatically
			   
			   
			   
			   
			   
			   //**************************************
			   
			   //we are making a new contact, so we need to put in the user_id of the user making the contact
//post the phone number of the user, which in the table is username
//received from app
/* $Number = $_POST['phonenumberofuser'];
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
			} */
			
			//*********************************************8
			
			
			//easier to get the $user_id this way, instead of above :
			
			   
			   //the other value in the category table, cat_id, is auto incremented, so it is inserted automatically
			   
			   	
				

				//}
				
				//else {
					
					//if it exists don't insert it into category table but we need the cat_id that
					//is already there
					
					// check the cat_name in the category table and get the matching cat_id
				//$query4 = "SELECT * FROM category WHERE cat_name = ?";
				//$stmt4 = $con->prepare($query4) or die(mysqli_error($con));
				//$stmt4->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				//$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
			    //$result4 = $stmt4->get_result();
				
				//while ($row = $result4->fetch_assoc()) {
				//get the corresponding cat_id in the row
			//this is the matching cat_id in the category table of cat_name
			//$last_id = $row["cat_id"];
			//echo $user_id;
			
			//If the old category has nobody using it, delete it.
			
		//	}
					
		//		}
				
	
				
				
				
				
				//If it already exists in the category table, then just put it only in the review table. 
				//this is the last inserted auto increment, which is cat_id
				
				
	/* 	 		$stmt = $con->prepare("INSERT INTO review (cat_id, cat_name, user_id, name, phone, address, comment ) VALUES(?,?,?,?,?,?,?)") or die(mysqli_error($con));
				$stmt->bind_param('isissss', $last_id, $category, $user_id, $name, $phone, $address, $comment) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);  */
//********************************************
				
				//received from app
			/* 	$category = $_POST["category"];
				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
 */
			
		?>