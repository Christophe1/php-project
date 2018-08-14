<?php

require('dbConnect.php');

//this is me, my username in the user table
$Number = $_POST['phonenumberofuser'];
//$Number = "+353872934480";
// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// get the username in the user table - the phone number, which is unique - then get the matching user_id
				// so we can check contacts against it 
				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
				
			while ($row = $result->fetch_assoc()) {
				//get the corresponding user_id in the row
			//this is the matching user_id in the user table of the user
            //echo $row['user_id']."<br />";
			$user_id = $row["user_id"];
			//$username = $row["username"];
			//echo $user_id;
			}
			
			
			//this is me, my user_id in the user table
//$user_id = $_POST['useridofuser'];
//'$user_id'
//$user_id=3;

$sql2 = "SELECT * FROM review WHERE user_id = ?";
$stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
$result2 = $stmt2->get_result();

$results = array();

	//$result2 = mysqli_query($con,$sql2);

		//if user_id has reviews in the db
	while($row = mysqli_fetch_array($result2)) {
		//make an array called $results
				 $results[] = array(
		 'publicorprivate' => $row['public_or_private'], 	 
		 'category' => $row['cat_name'], 
		 'name' => $row['name'],
		 'phone' => $row['phone'],
		 'address' => $row['address'],
		 'comment' => $row['comment'],
		 'reviewid' => $row['review_id'],
		 );
	}
	$json = json_encode($results);
//echo $user_id;	
echo $json;
//echo $Number;		
			
			
		?>