
This is the format I want :

{
	"category": "ballet instructor",
	"category_id": 182,
	"name": "bob",
	"phone": "12345678",
	"address": "Poland",
	"comment": "she is fantastic",
	"checkedcontacts": [{
			"checkedcontact": 23
		},
		{
			"checkedcontact": 33
		}
	]
}

This is what I am getting :

{
	"category": "ballet instructor",
	"category_id": 182,
	"name": "bob",
	"phone": "12345678",
	"address": "Poland",
	"comment": "she is fantastic",
	"checkedcontact": 23
		}



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

				//set up the object called Review
				class Review {
					
					public $category = "";
					public $category_id = "";
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
			$review -> category_id = $row["cat_id"];
			$review -> name = $row["name"];
			$review -> phone = $row["phone"];
			$review -> address = $row["address"];
			$review -> comment = $row["comment"];
	}
	
	$json = json_encode($review);
//echo $user_id;	
//echo $json;
//echo $Number;	




//**********we want to get the contacts who the review is shared with***********
//In review_shared table let's get the review_id and then the matching contact_id in those rows	

				$query = "SELECT * FROM review_shared WHERE review_id = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('i', $Review_id) or die ("Review_shared, MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("Review_shared, MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			//echo $result;
			
				//set up the object called Checkedcontacts
				class Checkedcontacts {
					
					public $checkedcontact = "";
					
				}
			
			$checkedcontacts = new Checkedcontacts();
			
				while ($row = $result->fetch_assoc()) {
			//get the corresponding contact_id in each row, remember to keep it in the while loop to get multiples
			//this is the matching contact_id in the review_shared table of review_id
			
			$checkedcontacts -> checkedcontact = $row["contact_id"];
			//$contact_id = $row["contact_id"];
			//echo $contact_id;
			$json_contact_ids = json_encode($checkedcontacts);
						echo $json_contact_ids;

			}
			

			

		?>