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
				//echo $user_id;
				}


			//here is the user_id, which is the corresponding user_id for username +5555555

			//$user_id = "21";
			//Select all related info in the review_shared table 
			//where the contact_id column is equal to $user_id.
				
			//a value in the contact_id column means a review is shared with a person, $user_name,
			//who owns that number, $user_id
			//we are joining the review_shared table with the category table, so we can instantly get the category name
			$sql = "SELECT * FROM review_shared INNER JOIN category ON review_shared.cat_id = category.cat_id WHERE review_shared.user_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			
			$userPersonalReviews = $stmt2->get_result();
			
			//$privateReviews = mysqli_query($con,$sql);
			
				// Iterate through user personal review results and append to combined reviews
				while (($row = $userPersonalReviews->fetch_assoc())) {
				//$category_id = $row['cat_name'];
				
				//$review_id = $row['review_id'];
				$category_id = $row['cat_name'];
				//$contact_id = $row['contact_id'];
				
				//each JSON object will be of form
				//{"cat_name":VARCHAR,"private_review_ids":[INT,],"public_review_ids":[INT,],"private_count":INT,"public_count":INT}
				/* $reviews[$category_id]['cat_name'] = $category_id;
				$reviews[$category_id]['private_review_ids'][] = $review_id;
				$reviews[$category_id]['public_review_ids'] = [];
				$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
				$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']); */
				echo $category_id;

				}
				
				//echo $contact_id;
			
			?>