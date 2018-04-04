<?php
require('dbConnect.php');
			
				//here is the user_id, which is the corresponding user_id for username +5555555

			$user_id = "21";
			//Select all related info in the review_shared table 
			//where the contact_id column is equal to $user_id.
				
			//a value in the contact_id column means a review is shared with a person, $user_name,
			//who owns that number, $user_id
			$sql = "SELECT * FROM review_shared WHERE contact_id = ?";
			$stmt2 = $con->prepare($sql) or die(mysqli_error($con));
			$stmt2->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
			$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			$privateReviews = $stmt2->get_result();
			
			//select all rows where public_or_private column = 2
			//in review table
			$sql2 = "SELECT * FROM review WHERE public_or_private = 2";
			$publicReviews = mysqli_query($con,$sql2);
			//$result = mysqli_query($con,$sql2);
			//$publicReviews = $result->get_result();
			
			//echo $privateReviews;
			// Prepare combined reviews array
			$reviews = [];
			
// Iterate through private review results and append to combined reviews
while (($row = $privateReviews->fetch_assoc())) {
    $category_id = $row['cat_id'];
    $review_id = $row['review_id'];

    $reviews[$category_id]['category'] = $category_id;
    $reviews[$category_id]['private_review_ids'][] = $review_id;
    $reviews[$category_id]['public_review_ids'] = [];
}

// Iterate through public review results and append to combined reviews
while (($row = $publicReviews->fetch_assoc())) {
    $category_id = $row['cat_id'];
    $review_id = $row['review_id'];

    $reviews[$category_id]['category'] = $category_id;

    // Create empty private reviews array, where it doesn't exist
    if (! isset($reviews[$category_id]['private_review_ids'])) {
        $reviews[$category_id]['private_review_ids'] = [];
    }

    // Add review id to public reviews where it doesn't exist in private reviews
    if (! in_array($review_id, $reviews[$category_id]['private_review_ids'])) {
        $reviews[$category_id]['public_review_ids'][] = $review_id;
    }
}

echo json_encode(array_values($reviews));

				/* //fetch all rows associated with the respective contact_id value
				//in review_shared table
			    while ($row = $result2->fetch_assoc()) {
					
				//get the corresponding cat_id in the row
			    $cat_id = $row["cat_id"];
				
				//get the corresponding review_id in the row
				$review_id = $row["review_id"];
				//make an array called $results
				$results[$row['cat_id']][] = $review_id; 
								
				}
				
				$jsonData = array_map(function($catId) use ($results) {
				return [
					'category' => $catId,
					'private_review_ids' => $results[$catId],
					];
			}, array_keys($results));
			//echo json_encode($jsonData);
			
				
				//**********************
				

			
				//fetch all associated rows where public_or_private column = 2
			    while ($row = $result2->fetch_assoc()) {
					
				//get the corresponding review_id in the row
				$review2_id = $row["review_id"];
				
				//get the corresponding cat_id in the row
				$cat2_id = $row["cat_id"];

				//make an array called $results
				$results2[$row['cat_id']][] = $review2_id;				
				
				}
				
				$jsonData2 = array_map(function($cat2Id) use ($results2) {
				return [
					'category' => $cat2Id,
					'public_review_ids' => $results2[$cat2Id],
					];
			}, array_keys($results2));
			//echo json_encode($jsonData2);
				
			echo json_encode(array_merge($jsonData,$jsonData2)); */
?>
				
