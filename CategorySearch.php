<?php

require('dbConnect.php');

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
				
				}
					
			$jsonData = array_map(function($catId) use ($results) {
			return [
					'category' => $catId,
					'review_ids' => $results[$catId]
					];
			}, array_keys($results));

			echo json_encode($jsonData);
?>