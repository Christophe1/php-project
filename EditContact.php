

<?php

require('dbConnect.php');

//received from app
				$category = $_POST["category"];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// check to see if the category being updated exists in the category table
				$query = "SELECT * FROM category WHERE cat_name = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $category) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
				
			   //if the category is not already in the category table, then put it in there.
			   // Get the cat_id and user_id from the review table
			   
			   	//received from app
				//this is the review_id of the review the user is editing
				$Review_id = $_POST['review_id'];
				//$Number = "51";
			
				// get the review_id in the review table, then get the matching fields in the row 
				$query2 = "SELECT * FROM review WHERE review_id = ?";
				$stmt2 = $con->prepare($query2) or die(mysqli_error($con));
				$stmt2->bind_param('s', $Review_id) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
			    $result2 = $stmt2->get_result();
			
				while ($row = $result2->fetch_assoc()) {
				//get the corresponding user_id and cat_id in the row
				$user_id = $row["user_id"];
				$cat_id = $row["cat_id"];
				//echo $user_id;
			}
			
				//If the updated category name doesn't exist in the category table....
			    If ($result->num_rows == 0) {
				//If the old category has nobody using it, simply update the category name, keeping the same cat_id
				
				//to do this : select from review_shared, if the cat_id is used only by current_user
				//then keep the cat_id and update the category name
				
				//select from review_shared, if the cat_id is used by more than just current_user
				//then add a new cat_id and category name
				
				
				$stmt5 = $con->prepare("UPDATE category SET cat_name=? WHERE cat_id=?") or die(mysqli_error($con));
				$stmt5->bind_param('si', $category, $cat_id ) or die ("MySQLi-stmt binding failed ".$stmt5->error);
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
				
				//If the old category has people using it then insert a new category
				$stmt3 = $con->prepare("INSERT INTO category (cat_name, user_id) VALUES(?,?)") or die(mysqli_error($con));
				$stmt3->bind_param('si', $category, $user_id) or die ("MySQLi-stmt binding failed ".$stmt3->error);
				$stmt3->execute() or die ("MySQLi-stmt execute failed ".$stmt3->error);
				//$result2 = $stmt2->get_result();
				//this is the last auto increment value, cat_id, which we need to put into the review table
				$last_id = $con->insert_id;
					
				//then put it in
			
			   
			   
			   
			   
			   //we also need to put in the user_id  - so we can know who made the category.
			   //The cat_id is auto increment, so it is put in automatically
			   
			   
			   
			   
			   
			   //**************************************
			   
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
			
			//*********************************************8
			
			
			//easier to get the $user_id this way, instead of above :
			

			   
			   //the other value in the category table, cat_id, is auto incremented, so it is inserted automatically
			   
			   	
				
				
			    				

				
				
				
				

					
					
				//If the old category has nobody using it, simply update the category name	

				
				

				
				
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
			
			//If the old category has nobody using it, delete it.
			
			}
					
				}


				
				// The ? below are parameter markers used for variable binding
				// auto increment does not need prepared statements

				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
				
								
				// get the matching fields in the review_id row, the category, name, phone, address and comment
				//review_id is auto increment so it is done automatically
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
				
				
				
				
				//If it already exists in the category table, then just put it only in the review table. 
				//this is the last inserted auto increment, which is cat_id
				
				
	/* 	 		$stmt = $con->prepare("INSERT INTO review (cat_id, cat_name, user_id, name, phone, address, comment ) VALUES(?,?,?,?,?,?,?)") or die(mysqli_error($con));
				$stmt->bind_param('isissss', $last_id, $category, $user_id, $name, $phone, $address, $comment) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);  */




//********************************************


				
				//received from app

			/* 	$category = $_POST["category"];
				$name = $_POST["name"];
				$phone = $_POST["phone"];
				$address = $_POST["address"];
				$comment = $_POST["comment"];
 */
			

		?>