<?php

require('dbConnect.php');

//this is me, my username in the user table
//$Number = $_POST['phonenumberofuser'];
//$Number = "+353872934480";
/* $Number = "+353864677745";

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
				} */

			$user_id = "21";

			//Select all related info in the review_shared table 
			//where the contact_id column is equal to $user_id.
				
			//a value in the contact_id column means a review is shared with a person, $user_name,
			//who owns that number, $user_id
			$sql = "SELECT * FROM review_shared WHERE contact_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			$result2 = $stmt2->get_result();
			
			//$results = array();

				//fetch all rows associated with the respective contact_id value
				//in review_shared table
			    while ($row = $result2->fetch_assoc()) {
					
				//get the corresponding cat_id in the row
			    $cat_id = $row["cat_id"];
				
				//get the corresponding review_id in the row
				$review_id = $row["review_id"];

				//make an array called $results
				$results[$row['cat_id']][] = $review_id; 
				
				//echo count($results[$row['cat_id']]) . ",";
				
				}
					
			$jsonData = array_map(function($catId) use ($results) {
			return [
					'category' => $catId,
					'count' => count($results[$catId]),
					'review_ids' => $results[$catId]
					
					];
			}, array_keys($results));

			echo json_encode($jsonData);
			
?>