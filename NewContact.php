

<?php

require('dbConnect.php');

//we are making a new contact, so we need to put in the user_id of the user making the contact
//post the phone number of the user, which in the table is username
$Number = $_POST['phonenumberofuser'];

//now we need to get the matching user_id

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// check the username in the user table and get the matching user_id
				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
				
				while ($row = $result->fetch_assoc()) {
				//get the corresponding user_id in the row
			//this is the matching user_id in the user table of the user
			$user_id = $row["user_id"];
			//echo $user_id;
			}

				//received from app

				$category = $_POST["category"];
				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
				
				$stmt = $con->prepare("INSERT INTO review (cat_name, user_id, name, phone, address, comment ) VALUES(?,?,?,?,?,?)") or die(mysqli_error($con));
				$stmt->bind_param('sissss', $category, $user_id, $name, $phone, $address, $comment) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
				
				// get the matching fields in the review_id row - the category, name, phone, address and comment
				//insert into those fields
	/* 			$stmt = $con->prepare("UPDATE review SET cat_name=?, name=?, phone=?, address=?, comment=? WHERE Review_id=?") or die(mysqli_error($con));
				$stmt->bind_param('ssssss', $category, $name, $phone, $address, $comment, $Review_id ) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error); */
				//$result2 = $stmt2->get_result();
	//}
	
	//$json = json_encode($review);
echo $user_id . " " . $category . " " .  $name . " " .  $phone . " " .  $address . " " .  $comment;
	//echo $Review_id . " " . $category . " " .  $name . " " .  $phone . " " .  $address . " " .  $comment;

//echo $json;
//echo $Number;		
			

		?>