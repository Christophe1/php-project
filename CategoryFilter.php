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
				
				//we need this statement so we can get $contact_id
			$query1 = "SELECT * FROM contacts WHERE user_id = ?";
			$stmt1 = $con->prepare($query1) or die(mysqli_error($con));
			$stmt1->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt1->error);
			$stmt1->execute() or die ("MySQLi-stmt execute failed ".$stmt1->error);
			$result1 = $stmt1->get_result();

				//fetch all rows associated with the value +353864677745
				while ($row = $result1->fetch_assoc()) {
				//for the user_id row associated with user_name +353864677745, call it $user_id
				$contact_id = $row["contact_id"];
			
				//here is the user_id, which is the corresponding user_id for username +353864677745
				//echo $contact_id;
				}
				
				
			//here is the user_id, which is the corresponding user_id for username +5555555

			//$user_id = "21";
			//Select all related info in the review_shared table 
			//where the contact_id column is equal to $user_id.
			
			//FOR $userPersonalReviews REVIEWS
			//where user_id = contact_id
			$sql = "SELECT * FROM review_shared INNER JOIN category ON review_shared.cat_id = category.cat_id WHERE review_shared.user_id = ? AND review_shared.contact_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('ii', $user_id,$user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			
			//this is for reviews that logged-in user has made
			$userPersonalReviews = $stmt2->get_result();
			
				
			//********** PRIVATE REVIEWS *****************
			
			//a value in the contact_id column of REVIEW_SHARED table means a review has been shared with 
			//the logged-in user, $user_id. So any review made by contact_id in this column will
			//appear to logged-in user as (1,0)
			//(in this case the contact must be reciprocated for privateReviews to work, for logged-in user
			//to see review as private he must be a contact in review makers's phone book)
			//we are joining the review_shared table with the category table, so we can instantly get the category name
			//and then join that to contacts
			
      		$sql = "SELECT * FROM review_shared INNER JOIN category ON review_shared.cat_id = category.cat_id 
			INNER JOIN contacts ON review_shared.contact_id = contacts.user_id and (contacts.user_id, contacts.contact_id) in (
			select contacts.contact_id, contacts.user_id from contacts)
			WHERE review_shared.contact_id = ?";  
			
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			//$stmt2->bind_param('i', $contact_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error); 
			
			//the above is for contacts who have shared review with logged-in user, and logged-in user also has them as contact on //their phone.
			$privateReviews[] = $stmt2->get_result();  
			//$privateReviews = $stmt2->get_result();   
			 
			//this is to cover the (1,0) scenario where logged-in user has a contact in their phone book but that contact does
			//not have logged-in user in their phonebook, and the contact's review is public. We want it to appear as (1,0)
    			 $sql2 =  "SELECT *
			FROM contacts INNER JOIN review on review.user_id = contacts.contact_id 
			where contacts.user_id = ?
			and (contacts.user_id, contacts.contact_id) not in (
			select contacts.contact_id, contacts.user_id from contacts) AND review.public_or_private = 2";
			 
			$stmt3 = $con->prepare($sql2) or die(mysqli_error($con));
			$stmt3->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
			$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			
			$privateReviews[] = $stmt3->get_result();  
			 
			//echo "monkey"; 
						//$privateReviews = $stmt3->get_result();  

						
			//************ PUBLIC REVIEWS **********************
			
			//FOR $publicReviews REVIEWS	
			//select all rows where public_or_private column = 2 in review table
			//we are joining with the category table, so we can instantly get the category name
/* 			$sql2 = "SELECT * FROM review INNER JOIN category ON review.cat_id = category.cat_id 
			INNER JOIN contacts ON contacts.contact_id WHERE contacts.user_id <> ? AND contacts.contact_id <> ?  AND
			review.public_or_private = 2";   */
			
			$sql2 = "SELECT * FROM review INNER JOIN category ON review.cat_id = category.cat_id
            WHERE review.public_or_private = 2 AND NOT EXISTS(SELECT * FROM contacts WHERE contacts.user_id = ? AND contacts.contact_id = category.user_id)
			";
			
 			$stmt3 = $con->prepare($sql2) or die(mysqli_error($con));
			$stmt3->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
			$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
			 
			 
			 $publicReviews = $stmt3->get_result();
			 
			//$publicReviews =  mysqli_query($con,$sql2);
			
			//these are pubic reviews
			//$publicReviews =  mysqli_query($con,$sql2);
			
			// Prepare combined reviews array
			$reviews = [];
			
			//echo "user_review_ids values :";
			
				//Iterate through user personal review results and append to combined reviews
				while (($row = $userPersonalReviews->fetch_assoc())) {
				
				$review_id = $row['review_id'];
				$category_id = $row['cat_name'];
				
				//whatever review ids satisfy the $userPersonalReviews->fetch_assoc
				//condition  then put the review_id in user_personal_review_ids array
				$reviews[$category_id]['cat_name'] = $category_id;
				$reviews[$category_id]['user_review_ids'][] = $review_id;
				$reviews[$category_id]['private_review_ids'] = [];
				$reviews[$category_id]['public_review_ids'] = [];
				$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
				$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
				$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);

				//echo json_encode($reviews[$category_id]['user_review_ids']);

				}
				
				//echo "<br> private_review_ids values :";

			    //Iterate through private review results and append to combined reviews
  				for($i = 0; $i < 2; $i++){
				while (($row = $privateReviews[$i] ->fetch_assoc())) {  
				//while (($row = $privateReviews ->fetch_assoc())) {
				$category_id = $row['cat_name'];
			
				$review_id = $row['review_id'];
				
				//each JSON object will be of form
				//{"cat_name":VARCHAR,"user_personal_review_ids":[INT,],"private_review_ids":[INT,],
				//"public_review_ids":[INT,],"user_personal_count":INT,"private_count":INT,"public_count":INT}
				$reviews[$category_id]['cat_name'] = $category_id;

					//if nothing has been set for user_review_ids
					//then set it to be an empty array
					if (! isset($reviews[$category_id]['user_review_ids'])) {
					$reviews[$category_id]['user_review_ids'] = [];
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);					
					}
					
					//if the review has not already been placed in the user_review_ids array
					//then put it in private_review_ids array
					if (! in_array($review_id, $reviews[$category_id]['user_review_ids'])) {
					//$reviews[$category_id]['user_review_ids'] = [];
					$reviews[$category_id]['private_review_ids'][]= $review_id;
					$reviews[$category_id]['public_review_ids'] = [];
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					}
					
					//echo json_encode($reviews[$category_id]['private_review_ids']);
				//}
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
					//$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
				    $reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					
					}

					//if the review has not already been placed in the private_review_ids array
					//and also not in the user_review_ids array
					//then put it in public_review_ids array
					if ( ! in_array($review_id, $reviews[$category_id]['private_review_ids']) AND 
					! in_array($review_id, $reviews[$category_id]['user_review_ids'])) {	
					$reviews[$category_id]['public_review_ids'][] = $review_id;
					$reviews[$category_id]['user_personal_count'] = count($reviews[$category_id]['user_review_ids']);
					$reviews[$category_id]['private_count'] = count($reviews[$category_id]['private_review_ids']);
					$reviews[$category_id]['public_count'] = count($reviews[$category_id]['public_review_ids']);
					
					}
					
					
					//$all_user_ids = [];
					//all_private_ids
					//all_public_ids

				}
				
            //make $reviews into a JSON Array
			//echo $contact_id;
			
			echo json_encode(array_values($reviews)); 

?>
				
