

<?php

require('dbConnect.php');

//this is the review_id of the current review, we want to delete everything associated with it, all records,
//in the review table
$Review_id = $_POST['review_id'];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// delete the review_id in the review table and all the matching fields in the row 
				$query = "DELETE FROM review WHERE review_id = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed  ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt binding failed  ".$stmt->error);
			    //$result = $stmt->get_result();
			
echo "deleted succesfully";
		?>