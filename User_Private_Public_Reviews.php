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
        $sql2 = "SELECT * FROM review WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $UserReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result2 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result2)) {//make an array called $results
            $results[] = array(
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
            );
        }
    }
	
	    foreach($PrivateReviewID as $PrivateReviewID) {
        $sql2 = "SELECT * FROM review WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $PrivateReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result2 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result2)) {//make an array called $results2
            $results2[] = array(
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
            );
        }
    }
	
		foreach($PublicReviewID as $PublicReviewID) {
        $sql2 = "SELECT * FROM review WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $PublicReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result3 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result3)) {//make an array called $results3
            $results3[] = array(
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
            );
        }
    }

    $combinedResults = array('user_review_ids' => $results, 'private_review_ids' => $results2, 'public_review_ids' => $results3);
    echo json_encode($combinedResults);
?>