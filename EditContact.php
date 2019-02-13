<?php
require('dbConnect.php');

				//received from app
				//first of all, we need to see if the BEING-UPDATED-TO CATEGORY name already exists in the db.
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
				
				
				// check to see if the BEING-UPDATED-TO CATEGORY exists in the category table
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
				//get the corresponding user_id and cat_id in the review table row of the current review
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
										//so we can update the CURRENT CAT_NAME in the category table and review table
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
										$stmt4 = $con->prepare("UPDATE review SET cat_id=?, cat_name=?, name=?, phone=?, address=?, comment=?, public_or_private=? WHERE Review_id=?") or die(mysqli_error($con));
										$stmt4->bind_param('isssssii', $last_id, $category, $name, $phone, $address, $comment, $public_or_private, $Review_id ) or die ("MySQLi-stmt binding failed ".$stmt4->error);
										$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error); 
									

										}		
									
									

									}
							
							//SCENARIO 2 ************* 
							//If the BEING-UPDATED-TO CATEGORY name DOES exist already in the category table...		
							If ($result->num_rows > 0) {
								
							//we want to get the cat_id of the already existing BEING-UPDATED-TO CATEGORY.
							//Then we will update the CURRENT CATEGORY to that.
							
							
							//get associated fields of the BEING-UPDATED-TO CATEGORY that already exists
							while ($row = $result->fetch_assoc()) {
							//get the corresponding cat_id in the row
							//this is the matching cat_id in the category table of BEING-UPDATED-TO CATEGORY name
							$updated_cat_id= $row["cat_id"]; 
							echo "the category does indeed exist" . "\n";
							echo "updated cat that already exists:" . $updated_cat_id;

							}
							
								
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
						
								
										//SCENARIO 2.1 ************* 
										//The CURRENT CAT ID is being used only in this review
										//We know this because if $review_count, the number of reviews the 
										//CURRENT CAT_NAME is being used in,
										//is 0, then this means the cat_id is only being used in this current review_id 
								
										//AND, also, if the BEING-UPDATED-TO category is not equal to the CURRENT category
										//then we want to delete CURRENT CAT_NAME from the Category table
										
										If ($review_count==0 && $category <> $cat_name) {
										//Then delete the category from the Category table, no point having it 
										//lingering around	

										$query = "DELETE FROM category WHERE cat_id = ?";
										$stmt = $con->prepare($query) or die(mysqli_error($con));
										$stmt->bind_param('i', $cat_id) or die ("MySQLi-stmt binding failed  ".$stmt->error);
										$stmt->execute() or die ("MySQLi-stmt binding failed  ".$stmt->error);
														
										//We know the BEING-UPDATED-TO CATEGORY already exists in the category table, so just 
										//update the current review with its cat_name and cat_id in the review table. 
										//For the cat_id value, we want to update to $updated_cat_id, which is the cat_id of the //BEING-UPDATED-TO CATEGORY that already exists
						
										$stmt4 = $con->prepare("UPDATE review SET cat_id=?, cat_name=?, name=?, phone=?, address=?, comment=?, public_or_private=? WHERE Review_id=?") or die(mysqli_error($con));
										$stmt4->bind_param('isssssii', $updated_cat_id, $category, $name, $phone, $address, $comment, $public_or_private, $Review_id ) or die ("problem here ".$stmt4->error);
										$stmt4->execute() or die ("problem here ".$stmt4->error); 
						
										echo "current Category only exists in this review" . "\n";

										}
										
										//SCENARIO 2.2*********** 
										//The CURRENT CAT_NAME is being used in more than just this
										//current review. So don't delete it from the Category table.
										
										//A cat_id for the BEING-UPDATED-TO CATEGORY already exists.
										//Update this current review with the already existing
										//BEING-UPDATED-TO CATEGORY id and name

										else {
												
										$stmt4 = $con->prepare("UPDATE review SET cat_id=?, cat_name=?, name=?, phone=?, address=?, comment=?, public_or_private=? WHERE Review_id=?") or die(mysqli_error($con));
										$stmt4->bind_param('isssssii', $updated_cat_id, $category, $name, $phone, $address, $comment, $public_or_private, $Review_id ) or die ("problem here ".$stmt4->error);
										$stmt4->execute() or die ("problem here ".$stmt4->error); 
						
										echo "current Category exists in multiple reviews" . "\n";
										}
										
							}
							
							
							
									
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
					
							//post the json array of checked contacts, checkedContacts, from EditContact in my phone to this php page. 
							//Let's call the php-side json array $jsoncheckedContacts
							//$_POST['checkedContacts'] is Android side, of the form [{"checkedContact":"+3531234567"},{"checkedContact":"+353868132813"}]
							$jsoncheckedContacts = $_POST['checkedContacts'];
					
							echo "checked contacts are " . $jsoncheckedContacts;
					
							// delete all the review_ids in the review_shared table and all the matching fields in the row
							//we do this before adding the new checkedcontacts, which the user is editing
							$query = "DELETE FROM review_shared WHERE review_id = ?";
							$stmt = $con->prepare($query) or die(mysqli_error($con));
							$stmt->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed  ".$stmt->error);
							$stmt->execute() or die ("MySQLi-stmt binding failed  ".$stmt->error);
					
					
							//decode the JSON array
							$arraycheckedContacts = json_decode($jsoncheckedContacts);
					
										 //for each checkedcontact posted from Android...
										foreach ($arraycheckedContacts as $value)
								
											{
												
											//and the value we want to extract from the JSON Array is called checkedContact (it's the only key
											//in the JSON Array). JSONArray posted from Android is of the form, 
											//[{"checkedContact":"+353123456"},{"checkedContact":"+353567890"}....]
											$checkedContact = $value->checkedContact;
								
											//So $checkedContact will be a phone number, the username of the contact, like +353872934480. Now we want to get the corresponding 
											//user_id in the user table, and we want to insert this as contact_id into review_shared
											
											// select the username, the phonenumber of the contact, in the user table and get the matching user_id
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
									
								//Back to SCENARIO 1 ************* 
								//If the BEING-UPDATED-TO CATEGORY name doesn't exist in the category table...
								If ($result->num_rows == 0) {
										
																		
								//SCENARIO 1.1 - The CURRENT CAT_NAME is only being used in this current review_id
								//will insert the new checkedcontacts 
								
								If ($review_count==0) {
			
									$stmt3 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
									$stmt3->bind_param('iiiis', $cat_id, $Review_id, $user_id, $contact_id,$checkedContact) or die ("MySQLi-stmt binding failed ".$stmt3->error);
									$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
									
									}
					
									else {
									//Back to SCENARIO 1.2 - The CURRENT CAT_NAME is being used in more than just this
									//current review, we have created a new cat_name and cat_id for the BEING-UPDATED-TO CATEGORY
									//We have deleted all the review_ids in the review_shared table. now we 
									//will insert the new checkedcontacts. Using $last_id, which is the id for the 
									//BEING-UPDATED-TO CATEGORY 
									$stmt3 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
									$stmt3->bind_param('iiiis', $last_id, $Review_id, $user_id, $contact_id,$checkedContact) or die ("MySQLi-stmt binding failed ".$stmt3->error);
									$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
									
									echo "we are going to update the review_shared table" . "\n";
						
										}
										
										
									}
									//Back to SCENARIO 2,  the BEING-UPDATED-TO CATEGORY already exists*********** 
									If ($result->num_rows > 0) {
										
									//SCENARIO 2.1	
									//The CURRENT CAT ID is being used only in this review
									//If $review_count, the number of reviews the CURRENT CAT_NAME is being used in,
									//is 0, then we know the cat_id is only being used in this current review_id 
							
									//Update this current review with 
									//BEING-UPDATED-TO CATEGORY id	
									If ($review_count==0) {
									$stmt3 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
									$stmt3->bind_param('iiiis', $updated_cat_id, $Review_id, $user_id, $contact_id,$checkedContact) or die ("MySQLi-stmt binding failed ".$stmt3->error);
									$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
									
									}
										
									else {
									//Back to SCENARIO 2.2 - The CURRENT CAT_NAME is being used in more than just this
									//current review,
									//we will insert the new checkedcontacts using $updated_cat_id, which is the id for the 
									//BEING-UPDATED-TO CATEGORY which has already exisited
									$stmt3 = $con->prepare("INSERT INTO review_shared (cat_id, review_id, user_id, contact_id, username) VALUES(?,?,?,?,?)") or die(mysqli_error($con));
									$stmt3->bind_param('iiiis', $updated_cat_id, $Review_id, $user_id, $contact_id,$checkedContact) or die ("MySQLi-stmt binding failed ".$stmt3->error);
									$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
									
									echo "we are going to update the review_shared table" . "\n";
						
										}
										
										
										
										
									}
								

									}

			
			
		?>