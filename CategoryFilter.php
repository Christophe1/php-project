<?php
require('dbConnect.php');

			//this is me, my username in the user table
			$Number = $_POST['phonenumberofuser'];
			//$Number = "+353872934480";
			//$Number = "+353864677745";

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
			
			//FOR $userPersonalReviews REVIEWS
			//a value in the contact_id column means a review is shared with a person, $user_name,
			//who owns that number, $user_id
			//we are joining the review_shared table with the category table, so we can instantly get the category name
			$sql = "SELECT * FROM review_shared INNER JOIN category ON review_shared.cat_id = category.cat_id WHERE review_shared.user_id = ? AND review_shared.contact_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('ii', $user_id,$user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			
			//this is for reviews that current user has made
			$userPersonalReviews = $stmt2->get_result();
				
			//FOR $privateReviews REVIEWS	
			//a value in the contact_id column means a review is shared with a person, $user_name,
			//who owns that number, $user_id
			//we are joining the review_shared table with the category table, so we can instantly get the category name
			$sql = "SELECT * FROM review_shared INNER JOIN category ON review_shared.cat_id = category.cat_id WHERE review_shared.contact_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			
			//this is for reviews by people who know current user, and shared with current user
			$privateReviews = $stmt2->get_result();
			
			//FOR $publicReviews REVIEWS	
			//select all rows where public_or_private column = 2 in review table
			//we are joining with the category table, so we can instantly get the category name
			$sql2 = "SELECT * FROM review INNER JOIN category ON review.cat_id = category.cat_id WHERE review.public_or_private = 2";
			
			//these are pubic reviews
			$publicReviews =  mysqli_query($con,$sql2);
			
			// Prepare combined reviews array
			$reviews = [];
			
				//Iterate through user personal review results and append to combined reviews
				while (($row = $userPersonalReviews->fetch_assoc())) {
				
				$review_id = $row['review_id'];
				$category_id = $row['cat_name'];
				
				//whatever review ids satisfy the $userPersonalReviews->fetch_assoc
				//condition  then put then review_id in user_personal_review_ids array
				$reviews[$category_id]['cat_name'] = $category_id;
				$reviews[$category_id]['user_review_ids'][] = $review_id;
				$reviews[$category_id]['private_review_ids'] = [];
				$reviews[$category_id]['public_review_ids'] = [];
				$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
				$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
				$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);


				}
			
			    //Iterate through private review results and append to combined reviews
				while (($row = $privateReviews->fetch_assoc())) {
				$category_id = $row['cat_name'];
			
				$review_id = $row['review_id'];
				
				//each JSON object will be of form
				//{"cat_name":VARCHAR,"user_personal_review_ids":[INT,],"private_review_ids":[INT,],
				//"public_review_ids":[INT,],"user_personal_count":INT,"private_count":INT,"public_count":INT}
				$reviews[$category_id]['cat_name'] = $category_id;

					//if nothing has been set for user_personal_review_ids
					//then set it to be an empty array
					if (! isset($reviews[$category_id]['user_review_ids'])) {
					$reviews[$category_id]['user_review_ids'] = [];
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);					
					}
					
					//if the review has not already been placed in the user_personal_review_ids array
					//then put it in private_review_ids array
					if (! in_array($review_id, $reviews[$category_id]['user_review_ids'])) {
					$reviews[$category_id]['user_review_ids'] = [];
					$reviews[$category_id]['private_review_ids'][]= $review_id;
					$reviews[$category_id]['public_review_ids'] = [];
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					}
				}

				// Iterate through public review results and append to combined reviews
				while (($row = $publicReviews->fetch_assoc())) {
				$category_id = $row['cat_name'];
				$review_id = $row['review_id'];

				$reviews[$category_id]['cat_name'] = $category_id;

					//if nothing has been set for user_personal_review_ids
					//then set it to be an empty array
					if (! isset($reviews[$category_id]['user_review_ids'])) {
					$reviews[$category_id]['user_review_ids'] = [];
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					
					}
						
					//if nothing has been set for private_review_ids
					//then set it to be an empty array
					if (! isset($reviews[$category_id]['private_review_ids'])) {
					$reviews[$category_id]['private_review_ids'] = [];
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
				    $reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					
					}

					//if the review has not already been placed in the private_review_ids array
					//and also in the user_review_ids array
					//then put it in public_review_ids array
					if ( ! in_array($review_id, $reviews[$category_id]['private_review_ids']) AND 
					! in_array($review_id, $reviews[$category_id]['user_review_ids'])) {	
					$reviews[$category_id]['public_review_ids'][] = $review_id;
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					
					}

				}
				
            //make $reviews into a JSON Array
			echo json_encode(array_values($reviews)); 

?>
				
