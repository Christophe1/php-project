

<?php

require('dbConnect.php');

//this is the review_id of the review the user wants to edit
$Review_id = $_POST['review_id'];
//$Number = "51";
// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// get the review_id in the review table
/* 				$query = "SELECT * FROM review WHERE review_id = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Review_id) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result(); */
				
				//received from app

				$category = $_POST["category"];
				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
				
				// get the matching fields in the review_id row, the category, name, phone, address and comment
			
			//while($row = mysqli_fetch_array($result)) {
				
				//insert into those fields
				$stmt = $con->prepare("UPDATE review SET cat_name=?, name=?, phone=?, address=?, comment=? WHERE Review_id=?") or die(mysqli_error($con));
				$stmt->bind_param('ssssss', $category, $name, $phone, $address, $comment, $Review_id ) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
				//$result2 = $stmt2->get_result();
	//}
	
	//$json = json_encode($review);
echo $Review_id . " " . $category . " " .  $name . " " .  $phone . " " .  $address . " " .  $comment;

//echo $json;
//echo $Number;		
			

		?>