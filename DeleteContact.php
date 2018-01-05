

<?php

require('dbConnect.php');

//this is the review_id of the current review, we want to delete everything associated with it, all rows,
//in the review table
$Review_id = $_POST['review_id'];

//also, if the category for this review is used for this review only, then delete the category

				// get the current review_id being deleted in the review table, then get the matching fields in the row
				$query2 = "SELECT * FROM review WHERE review_id = ?";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();
			
				while ($row = $result2->fetch_assoc()) {
				//get the corresponding cat_name and cat_id in the review table row of the current review
				//$user_id = $row["user_id"];
				$cat_name= $row["cat_name"];
				$cat_id = $row["cat_id"];
				
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
														//Then delete the category from the Category table, no point having it 
										//lingering around	

										$query = "DELETE FROM category WHERE cat_id = ?";
										$stmt = $con->prepare($query) or die(mysqli_error($con));
										$stmt->bind_param('i', $cat_id) or die ("MySQLi-stmt binding failed  ".$stmt->error);
										$stmt->execute() or die ("MySQLi-stmt binding failed  ".$stmt->error);
										}
				

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// delete the review_id in the review table and all the matching fields in the row 
				$query = "DELETE FROM review WHERE review_id = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed  ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt binding failed  ".$stmt->error);
			    //$result = $stmt->get_result();
				
				
											// delete all the review_ids in the review_shared table and all the matching fields in the row
							//we do this before adding the new checkedcontacts, which the user is editing
							$query = "DELETE FROM review_shared WHERE review_id = ?";
							$stmt = $con->prepare($query) or die(mysqli_error($con));
							$stmt->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed  ".$stmt->error);
							$stmt->execute() or die ("MySQLi-stmt binding failed  ".$stmt->error);
					
			
echo "deleted succesfully";






		?>