<?php 
//When the user clicks 'Create New Contact', save the checked values of 
//contacts into the review_shared table. 
//review_shared table : auto_inc, review_id, contact_id

require('dbConnect.php'); 

//use the variables we created in volleyLogin.php
	session_start();
	$username = $_SESSION['username'];
	$user_id = $_SESSION['user_id'];
	echo "user name is " . $username . "<br>";
	echo "user id is " . $user_id . "<br>"; 
	
	

 //this code below will get the username (phone number) of contacts
 // for $user_id 
 $select_from_user_table = "SELECT  contacts.contact_id, user.username
FROM contacts 
INNER JOIN user
ON contacts.contact_id=user.user_id WHERE contacts.user_id = '$user_id'";

	//get the result of the above
	$result2=mysqli_query($con,$select_from_user_table); 
	?>

		<form action="" method="post">
	<?php
	//show the usernames, phone numbers
	while($row = mysqli_fetch_assoc($result2)) { ?>
	 <input type='checkbox' name='check_contacts[]' value='<?=$row['contact_id']?>'> <?php echo $row['username'] ?> </br>
    
		<?php	
		//we need the php bracket below to close the while loop


		}

			?>
		<input type="submit" value="click here"/>
    </form>




<?php

if(!empty($_POST['check_contacts'])) {
    foreach($_POST['check_contacts'] as $check) {
		
			$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '1', '$user_id','$check')";

		//we want to save the checked contacts into the review_shared table
		//$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '1', '2')";
		$insert_into_review_shared_table = mysqli_query($con,$insert_review_shared_command);
            echo $check; //echoes the value set in the HTML form for each checked checkbox.
                         //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                         //in your case, it would echo whatever $row['Report ID'] is equivalent to.
    }
}
	
	$con->close();
	
	?> 
	

	