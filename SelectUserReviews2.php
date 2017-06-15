<?php

require('dbConnect.php');

//this is me, my user_id in the user table
$user_id = $_POST['useridofuser'];



$sql2 = "SELECT * FROM review WHERE user_id = '$user_id'";
$results = array();

	$result2 = mysqli_query($con,$sql2);

		//if user_id has reviews in the db
	while($row = mysqli_fetch_array($result2)) {
		//make an array called $results
				 $results[] = array(
		 'category' => $row['cat_name'], 
		 'name' => $row['name'],
		 'phone' => $row['phone'],
		 'comment' => $row['comment'],
		 );
	}
	$json = json_encode($results);
echo $json;
		?>