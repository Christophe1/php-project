<?php
    require('dbConnect.php');
	
	$UserReviewID = $_POST['reviewiduser'];
    $UserReviewID = explode(",",$UserReviewID);
	
	$PrivateReviewID = $_POST['reviewidprivate'];
    $PrivateReviewID = explode(",",$PrivateReviewID);
	
	$PublicReviewID = $_POST['reviewidpublic'];
    $PublicReviewID = explode(",",$PublicReviewID);
	
/* 	$UserReviewID = ('32,76');
	$UserReviewID = explode(",",$UserReviewID);
	
	$PrivateReviewID = ('240,241');
	$PrivateReviewID = explode(",",$PrivateReviewID);
	
	$PublicReviewID = ('284,271');
	$PublicReviewID = explode(",",$PublicReviewID); */

	//for user_review_ids
	$results = array();
	
	//for private_review_ids
	$results2 = array();
	
	//for public_review_ids
	$results3 = array();
	
		foreach($UserReviewID as $UserReviewID) {
			
		//join review table with the user table
        $sql2 = "SELECT * FROM review INNER JOIN user ON review.user_id = user.user_id WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $UserReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result2 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result2)) {//make an array called $results
            $results[] = array(
				'publicorprivate' => $row['public_or_private'], 
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
				'username' => $row['username'],
            );
						
        }
		
		
		
    }
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
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
				'username' => $row['username'],
            );
        }
    }
		//for each review_id of a matching category belonging to the general public
		foreach($PublicReviewID as $PublicReviewID) {
        $sql2 = "SELECT * FROM review INNER JOIN user ON review.user_id = user.user_id WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $PublicReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result3 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result3)) {//make an array called $results3
            $results3[] = array(
				'publicorprivate' => $row['public_or_private'], 
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
				'username' => $row['username'],
            );
        }
    }

    $combinedResults = array('user_review_ids' => $results, 'private_review_ids' => $results2, 'public_review_ids' => $results3);
    echo json_encode($combinedResults);
?>