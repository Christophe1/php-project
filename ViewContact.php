



<?php

require('dbConnect.php');

//this is the review_id clicked in the ListView
$Review_id = $_POST['review_id'];
//$Number = "51";
// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// get the review_id in the review table, then get the matching fields in the row 
				$query = "SELECT * FROM review WHERE review_id = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('i', $Review_id) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
				
				
//In review_shared table let's get the review_id and then later the matching username (the phone number of contacts) in those rows	
				$query2 = "SELECT * FROM review_shared WHERE review_id = ?";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('i', $Review_id) or die ("Review_shared, MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("Review_shared, MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();				

				//set up the object called Review
				class Review {
					
					public $category = "";
					public $category_id = "";
					public $name = "";
					public $phone = "";
					public $address = "";
					public $comment = "";
					
					public $checkedcontacts = array();// declare this as array, because there's multiple checkboxes in the review
					
				}
				
				$review = new Review();
				
			while($row = mysqli_fetch_array($result)) {
				//get the corresponding fields in the review_id row
				//make it into a json object
			$review -> category = $row["cat_name"];
			$review -> category_id = $row["cat_id"];
			$review -> name = $row["name"];
			$review -> phone = $row["phone"];
			$review -> address = $row["address"];
			$review -> comment = $row["comment"];
	}
	
			$review -> checkedcontacts = array();
	
		    while ($row = $result2->fetch_assoc()) {
			//get the corresponding username in each row,
			//this is the matching username (the phone number) in the review_shared table of review_id
			$review -> checkedcontacts[] = $row["username"];
			
			}
			
			
				$json = json_encode($review);
			echo $json;
	
		?>
		
		