

<?php

	//When the own-user is making a new contact, or editing an existing contact, we want the category being created to be autofilled
	//instead of own-user having to type it all in.
	require('dbConnect.php');
	
	//First, let's get own-user details.
	//post the phone number of the user, which in the user table is username, and get associated user_id

	//received from app, the phonenumber, which in the DB is username
	$Number = $_POST['phonenumberofuser'];
	//$Number = "+353872934480";

				//now we need to get the matching user_id
				// check the username in the user table and get the matching user_id
				$query1 = "SELECT * FROM user WHERE username = ?";
				$stmt1 = $con->prepare($query1) or die(mysqli_error($con));
				$stmt1->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt1->error);
				$stmt1->execute() or die ("MySQLi-stmt execute failed ".$stmt1->error);
			    $result1 = $stmt1->get_result();
				
				while ($row = $result1->fetch_assoc()) {
				//get the corresponding user_id in the row
				//this is the matching user_id in the user table of the user
				$user_id = $row["user_id"];
				}
				
				//FOR OWN-USER, PUBLIC_OR_PRIVATE = 0 or 1
				//Show own-user their already created categories
				$query2 = "SELECT DISTINCT cat_id,cat_name FROM review WHERE user_id = ? AND public_or_private <>2";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();
								
				$array1 = array();
				
				while ($row = mysqli_fetch_assoc($result2)) {
					
					$array1['results'][] = $row;
					
				} 
				
 				//PUBLIC_OR_PRIVATE = 2
				//Show own-user all public categories
 				$query3 = "SELECT DISTINCT cat_id,cat_name FROM review WHERE public_or_private = 2";
				$result3 = mysqli_query($con,$query3);

				$array2 = array();
				
				while($row = mysqli_fetch_assoc($result3)) {

			    $array2['results'][] = $row;
					
				}  
				
				
				//NOW, we want to see IF OWN-USER IS INCLUDED IN SHARED REVIEWS by peope own-user knows
				//PUBLIC_OR_PRIVATE = 1 FOR NOT OWN-USER REVIEWS
				$query4 = "SELECT DISTINCT review.cat_id,cat_name FROM review INNER JOIN review_shared ON review.review_id = review_shared.review_id
				WHERE review.public_or_private = 1 AND review.user_id <> ?";
				$stmt4 = $con->prepare($query4) or die(mysqli_error($con));
				$stmt4->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt4->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
			    $result4 = $stmt4->get_result();
				
				$array3 = array();

				while ($row = mysqli_fetch_assoc($result4)) {
				$array3['results'][] = $row;

				}
				

				$array1 = json_encode($array1);
				$array2 = json_encode($array2);
				$array3 = json_encode($array3);

				$array1 =  json_decode($array1, TRUE);
				$array2 =  json_decode($array2, TRUE);
				$array3 =  json_decode($array3, TRUE);

				$array4 = array_merge_recursive($array1['results'], $array2['results'], $array3['results']);

				$uniqueArray2['results'] = array_values(array_unique($array4, SORT_REGULAR));
				
				echo json_encode($uniqueArray2);

		?>