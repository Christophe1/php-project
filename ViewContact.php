



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
				$stmt->bind_param('s', $Review_id) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();

				//set up the object called Review
				class Review {
					
					public $category = "";
					public $name = "";
					public $phone = "";
					public $address = "";
					public $comment = "";
					
				}
				
				$review = new Review();
				
			while($row = mysqli_fetch_array($result)) {
				//get the corresponding fields in the review_id row
				//make it into a json object
			$review -> category = $row["cat_name"];
			$review -> name = $row["name"];
			$review -> phone = $row["phone"];
			$review -> address = $row["address"];
			$review -> comment = $row["comment"];
	}
	
	$json = json_encode($review);
//echo $user_id;	
echo $json;
//echo $Number;		
			

		?>