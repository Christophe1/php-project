<?php
    require('dbConnect.php');
	
	//reviewidprivate from the APP will be a string like 3,12,34
	//explode it, break it into individual strings between the commas
	$PrivateReviewID = $_POST['reviewidprivate'];
	
	//if explodable, like 3,12,34...
	if(strpos ($PrivateReviewID, ",") !== false) {
	
	
    $PrivateReviewID = explode(",",$PrivateReviewID);

	//for private_review_ids
	$results2 = array();
	
		//for each review_id of a matching category belonging to a phone contact of the logged-in user
	    foreach($PrivateReviewID as $PrivateReviewID) {
        $sql2 = "SELECT * FROM review INNER JOIN user ON review.user_id = user.user_id WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $PrivateReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result2 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result2)) {//make an array called $results2
            $results2[] = array(
				'publicorprivate' => $row['public_or_private'], 
				'date_created' => $row['time_stamp'],
                'category' => $row['cat_name'],
                'name' => $row['name'],
				'address' => $row['address'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
				'username' => $row['username'],
            );
        }
    }
	}
	
	    $combinedResults = array('private_review_ids' => $results2);
    echo json_encode($combinedResults);
?>