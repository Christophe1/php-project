<?php

require('file.php');

$ReviewIDs = array("32","76");

$results = array();

foreach($ReviewIDs as $ReviewID) {

$sql2 = "SELECT * FROM review WHERE review_id = ?";
$stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
$stmt2->bind_param('i', $ReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
$result2 = $stmt2->get_result();

//$results = array();

	//$result2 = mysqli_query($con,$sql2);

		//if user_id has reviews in the db
	while($row = mysqli_fetch_array($result2)) {
		//make an array called $results
				 $results[] = array(
		 'category' => $row['cat_name'], 
		 'name' => $row['name'],
		 'phone' => $row['phone'],
		 'comment' => $row['comment'],
		 'reviewid' => $row['review_id'],
		 );
	}

}
	$json = json_encode($results);
echo $json;
			
			
		?>