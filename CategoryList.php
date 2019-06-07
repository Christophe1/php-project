

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
				
				//Show loged-in user their already created categories, except public ones
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
				//Show logged-in user all public categories
 				$query3 = "SELECT DISTINCT cat_id,cat_name FROM review WHERE public_or_private = 2";
				$result3 = mysqli_query($con,$query3);

				$array2 = array();
				
				while($row = mysqli_fetch_assoc($result3)) {

			    $array2['results'][] = $row;
					
				}  
				
				
				//We want to see if logged-in user is included in shared reviews by contacts.
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
				
				//$array1 = Logged-in user categories, except public ones
				$array1 = json_encode($array1);
				//$array2 = all public categories, by all users
				$array2 = json_encode($array2);
				//array3 = all categories shared with logged-in user, except logged-in user and public reviews
				$array3 = json_encode($array3);

				$array1 =  json_decode($array1, TRUE);
				$array2 =  json_decode($array2, TRUE);
				$array3 =  json_decode($array3, TRUE);

				//merge array1, array2 and array3, need ??[] in case an array is null
				$array4 = array_merge_recursive($array1['results']??[], $array2['results']??[], $array3['results']??[]);
				
				//this function is to remove duplicates, to ensure that the category name
				//will only appear once in the array
				function array_key_unique($arr, $key) {
				$uniquekeys = array();
				$output     = array();
					foreach ($arr as $item) {
						if (!in_array($item[$key], $uniquekeys)) {
						$uniquekeys[] = $item[$key];
						$output[]     = $item;
						}
					}
					return $output;
					}
					
				//call the function to remove duplicates of cat_name
				$uniqueArray2['results'] = array_key_unique($array4, 'cat_name');
				
				echo json_encode($uniqueArray2);

		?>