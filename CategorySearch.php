<?php

require('dbConnect.php');

//this is me, my username in the user table
//$Number = $_POST['phonenumberofuser'];
//$Number = "+353872934480";
$Number = "+353864677745";

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

			//Select all related info in the user table for the user +353864677745, or whatever
			$query = "SELECT * FROM user WHERE username = ?";
			$stmt = $con->prepare($query) or die(mysqli_error($con));
			$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
			$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			$result = $stmt->get_result();
				
				//fetch all rows associated with the value +353864677745
				while ($row = $result->fetch_assoc()) {
				//for the user_id row associated with user_name +353864677745, call it $user_id
				$user_id = $row["user_id"];
			
				//here is the user_id, which is the corresponding user_id for username +353864677745
				echo $user_id;
				}


			//Select all related info in the review_shared table 
			//where the contact_id column is equal to $user_id.
				
			//a value in the contact_id column means a review is shared with a person, $user_name,
			//who owns that number, $user_id
			$sql = "SELECT * FROM review_shared WHERE contact_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			$result2 = $stmt2->get_result();
			
			
			
			$results = array();
			
			
				
				//fetch all rows associated with the respective contact_id value
			    while ($row = $result2->fetch_assoc()) {
					
				//get the corresponding cat_id in the row
			    $cat_id = $row["cat_id"];
				
				//get the corresponding review_id in the row
				$review_id = $row["review_id"];
				

				
				//get the corresponding user_id in the row, the id of the person who created the review
				//if it the same value as $user_id, above, then we will put U in the recyclerView
				$review_maker_id = $row["user_id"];
				
/* 				 if ($review_maker_id = $user_id){
					
					echo "ffff";
				}  */
			
			
			    //While cat_id = 123, or whatever, select all related info in the category table 
				$sql2 = "SELECT * FROM category WHERE cat_id = ?";
				$stmt3 = $con->prepare($sql2) or die(mysqli_error($con));
				$stmt3->bind_param('i', $cat_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
				$result3 = $stmt3->get_result();
				
				//$results = array();
				
					//fetch all rows associated with the respective cat_id value	
					while($row2  = $result3->fetch_assoc()) {
					//make an array called $results
					$results[] = array(
					
					//get the corresponding cat_name in the row
					'category' => $row2['cat_name'], 
					'review_id' => $review_id,
					'U' => $review_maker_id,
					/*'private' => $row['phone'],
					'public' => $row['comment'],
					'reviewid' => $row['review_id'], */
					);
		 

					}

				//echo $json;	
				}		
				 echo json_encode($results);


//echo $user_id;	
		
				
				
				/*    U: if the contact_id in the review_shared table = user_id, the current user, 

				//fetch all rows associated with the respective cat_id value				
					while ($row[] = $result3->fetch_assoc()) {
					//get the corresponding cat_name in the row
					$cat_name = $row["cat_name"];
					
					$json = json_encode($cat_name);
					
					echo $json;
					//here is the user_id, which is the corresponding user_id for username Joe Blogs
					//echo $cat_id . ":" . $cat_name . "\n";
					}
				} */
			
			        

	
?>