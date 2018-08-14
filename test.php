<?php

require('dbConnect.php');

//this is the username, the logged-in user, in the user table
//$Number = $_POST['phonenumberofuser'];
//$Number = "+353872934480";
$Number = "666";
// get the username of the user in the user table, then get the matching user_id in the user table
				// so we can check contacts against it 
				//$query = "SELECT user_id FROM user WHERE username = ?";
				//$stmt = $con->prepare($query) or die(mysqli_error($con));
				//$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				//$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    //$result = $stmt->get_result();
				
				$query = "SELECT review.review_id FROM review INNER JOIN review_shared ON review_shared.contact_id = ? WHERE review.public_or_private = 2";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();				
			
			//get the matching user_id
			while ($row = $result->fetch_assoc()) {
			//this is the user_id in the user table of the user
			$review_id = $row["review_id"];
			
			echo $review_id . ", ";
			}

		

		//"SELECT * FROM review INNER JOIN user ON review.user_id = user.user_id WHERE review_id = ?";
		
?>

