

<?php

require('dbConnect.php');


//First, let's see if the category being created exists already in the category table.
//If yes, take that category id and put it in the review table.
//If no, create the category in the category table and then put it into the review table.

				//received from app
				$category = $_POST["category"];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// check to see if the category exists in the category table
				$query = "SELECT * FROM category WHERE cat_name = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   //if the category is not already in the category table, then put it in there.
			   //we also need to put in the user_id  - so we can know who made the category.
			   //The cat_id is auto increment, so put in automatically
			   
			   //we are making a new contact, so we need to put in the user_id of the user making the contact
//post the phone number of the user, which in the table is username

//received from app
$Number = $_POST['phonenumberofuser'];

//now we need to get the matching user_id

// check the username in the user table and get the matching user_id
				$query2 = "SELECT * FROM user WHERE username = ?";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();
				
				while ($row = $result2->fetch_assoc()) {
				//get the corresponding user_id in the row
			//this is the matching user_id in the user table of the user
			$user_id = $row["user_id"];
			//echo $user_id;
			}
			   
			   //the other value in the category table, cat_id, is auto incremented, so it is inserted automatically
			    
				//so, check to see if the category already exists in the category table. If it doesn't exist, then 
				//put the values in the category table...
			    If ($result->num_rows == 0) {
				$stmt3 = $con->prepare("INSERT INTO category (cat_name, user_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt3->bind_param('si', $category, $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
				//$result2 = $stmt2->get_result();
				$last_id = $con->insert_id;
				}
				
				else {
					
					//if it exists don't insert it into category table but we need the cat_id that
					//is already there
					
					// check the cat_name in the category table and get the matching cat_id
				$query4 = "SELECT * FROM category WHERE cat_name = ?";
				$stmt4 = $con->prepare($query4) or die(mysqli_error($con));
				$stmt4->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt4->execute() or die ("MySQLi-stmt execute failed ".$stmt4->error);
			    $result4 = $stmt4->get_result();
				
				while ($row = $result4->fetch_assoc()) {
				//get the corresponding cat_id in the row
			//this is the matching cat_id in the category table of cat_name
			$last_id = $row["cat_id"];
			//echo $user_id;
			}
					
				}

				//received from app

				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
				
				//If it already exists in the category table, then just put it only in the review table. 
				//this is the last inserted auto increment, which is cat_id
				
				
		 		$stmt = $con->prepare("INSERT INTO review (cat_id, cat_name, user_id, name, phone, address, comment ) VALUES(?,?,?,?,?,?,?)") or die(mysqli_error($con));
				$stmt->bind_param('isissss', $last_id, $category, $user_id, $name, $phone, $address, $comment) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error); 
				
	//$json = json_encode($review);
	echo $last_id
//echo $user_id . " " . $category . " " .  $name . " " .  $phone . " " .  $address . " " .  $comment;
	//echo $Review_id . " " . $category . " " .  $name . " " .  $phone . " " .  $address . " " .  $comment;

//echo $json;
//echo $Number;		
			

		?>