<?php 

require('dbConnect.php'); 

//use the variables we created in volleyLogin.php
	session_start();
	$username = $_SESSION['username'];
	$user_id = $_SESSION['user_id'];
	echo "user name is " . $username . "<br>";
	echo "user id is " . $user_id . "<br>"; 
	
	// If the 'create new Contact' button is clicked
if (isset($_POST['create'])) {
	
	
//	$sql = "INSERT INTO category VALUES(cat_id, cat_name, user_id) VALUES('123', 'Bin man', '456')";
	
	$category = ($_POST['category']);
	//$name = ($_POST['name']);
	//$phonenumber = ($_POST['phonenumber']);
	//$address = ($_POST['address']);
	//$comment = ($_POST['comment']);
	//check if the category being entered is already there
	$select_from_cat_table = "SELECT * FROM category WHERE cat_name = '$_POST[category]'";
	$result=mysqli_query($con,$select_from_cat_table);
	$num_rows = mysqli_num_rows($result);
	
	  // get the associated rows where the column cat_name = '$_POST[category]
	   $row = mysqli_fetch_assoc($result);
	   // get the associated cat_id column in that row, cat_id is the auto increment value
       $cat_id = $row["cat_id"];

//if the category name already exists in the category table, then don't add it in again
	//if($num_rows >= 1) {
  //  echo "This Already Exists<br/>";
	//but do add it to the review table
	//NULL is the auto increment, the review_id
	//for the cat_id, we want to get the cat_id of the category name that has 
	//just been posted. This is $cat_id. $user_id is the user id of the person posting
//	$insert_review_command = "INSERT INTO review VALUES(NULL,'$cat_id','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";
 //   $insert_into_review_table = mysqli_query($con,$insert_review_command);
	
//}
	//if the category is not in the category table, then add the category in the category table.
//else if ($num_rows < 1) 
	
//{

//	$insert_category_command = "INSERT INTO category VALUES(NULL, '{$category}', '$user_id')";
	
	//$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '1', '$user_id','3')";
   // $insert_into_category_table = mysqli_query($con,$insert_category_command);
	//get the last autoincrement value of the category table
    $cat_id = mysqli_insert_id($con);

	//and add it to the review table
//	$insert_review_command = "INSERT INTO review VALUES(NULL,'$cat_id','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";
 //   $insert_into_review_table = mysqli_query($con,$insert_review_command);

//echo "Yes, it's been added correctly";
//echo $cat_id;


/* 	} else {
	echo "Error: " . $sql . "<br>" . $con->error;
} */

}




//$con->close();
//header('Location:volleyLogin.php');
//}
	


?>

	<!doctype html>
	<html>
	<body>
	<h2>Create new Contact</h2>
	<form method="post" action="" name="frmAdd">
	<p><input type="text" name = "category" placeholder = "category"></p>
	<!--<p><input type="text" name = "name" placeholder = "name"></p>
	<p><input type="text" name = "phonenumber" placeholder = "phone number"></p>
	<p><input type="text" name = "address" placeholder = "address"></p>
	<p><input type="text" name = "comment" placeholder = "comment"></p> -->
	
<!-- 	 <input type="checkbox" name="vehicle" value="Bike" checked> I have a bike<br>
  <input type="checkbox" name="vehicle" value="Car" checked> I have a car<br> 
	<h2>Share with:</h2>
	<input type="radio" name="select_who" value="Public">Public<br>
	<input type="radio" name="select_who" value="Just me">Just me<br>
	<input type="radio" name="select_who" value="All Contacts" checked>All my Contacts<br>
	<input type="radio" name="select_who" value="Select Who">Select Who<br>
</body>
</html> -->

<?php
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
	 <input type='checkbox' name='create[]' value='<?=$row['contact_id']?>'> <?php echo $row['username'] ?> </br>
    
		<?php	
		//we need the php bracket below to close the while loop


		}

			?>
		<input type="submit" value="click here"/>
    </form>




<?php

if(!empty($_POST['create'])) {
	    //$insert_into_category_table = mysqli_query($con,$insert_category_command);
    foreach($_POST['create'] as $check) {
			    //$insert_into_category_table = mysqli_query($con,$insert_category_command);
	$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '1', '$user_id','3')";

		//we want to save the checked contacts into the review_shared table
		//$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '1', '2')";
		$insert_into_review_shared_table = mysqli_query($con,$insert_review_shared_command);
			//$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '$review_id', '$user_id')";
  //  $insert_into_category_table = mysqli_query($con,$insert_category_command);
	
           // echo $check; //echoes the value set in the HTML form for each checked checkbox.
                         //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                         //in your case, it would echo whatever $row['Report ID'] is equivalent to.
    }
}
	
	$con->close();
	
	?> 
	
	

