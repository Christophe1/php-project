
<?php require('dbConnect.php'); 

session_start();
//review = $_SESSION['review'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
echo "user id is " . $user_id . "<br>";
echo "user name is " . $username . "<br>";
echo "review id is " . $_GET['id'] . "<p>";


$category=$_SESSION['cat_name'];

		$name=$_SESSION['name'];
		$phone=$_SESSION['phone'];
		$address=$_SESSION['address'];
		$comment=$_SESSION['comment'];


//$user_id=$_SESSION['user_id'];
//$review_id = $_SESSION['review_id'];
//$review_id = $_SESSION['review_id'];

/* echo $category;
echo "<br>";
echo $_GET['id']; */
//echo review;
// Edit a new record if the button 'Save' is clicked.
if (isset($_POST['Save'])) {

	$sql = "update review set cat_name = '".$_POST['category']."',
							  name = '".$_POST['name']."',
							  phone = '".$_POST['phone']."',
							  address = '".$_POST['address']."',
							  comment = '".$_POST['comment']."'
	where review_id = '" .$_GET['id']."'";
		//$sql = "update review set cat_name = '".$_POST['category']."' where review_id = '$review_id'";

	if(mysqli_query($con, $sql)) {
	//	echo "Success";
		header('Location:volleyLogin.php');
	} else {
		echo "Error " .mysqli_error($con);
	}
	
}

// Delte the record if the button 'Delete' is clicked.
if (isset($_POST['Delete'])) {
	
	$sql = "delete from review where review_id = ".$_GET['id'];
	
	            $retval = mysqli_query( $con,$sql );
                 header('Location:volleyLogin.php');
            if(! $retval ) {
               die('Could not delete data: ' . mysqli_error());
            }
            
         //   echo "Deleted data successfully\n";
            
	
}
//}
//}
/* 	$id = '';
	$category = '';
	$name = '';
	$phonenumber = '';
	$address = '';
	$comment = '';
	echo $_GET['id']; */
/* if (ISSET($_GET['id'])) {
	
	$sql = "select * from review where review_id = " .$_GET['id'];
	$result = mysqli_query($con, $sql);
	
if (mysqli_num_rows($result) > 0) {
	
	$row = mysqli_fetch_assoc($result);
	$id = $row['user_id'];
	$category = $row['cat_name'];
	$name = $row['name'];
	$phonenumber = $row['phone'];
	$address = $row['address'];
	$comment = $row['comment'];
	
	echo "user id is " .$id;
	
}

} */
//$con->close();


?>

<!doctype html>
<html>
<body>
<h2>Edit Record</h2>
<form method="post" action="">
<p><input type="text" name = "category" value = "<?=$category?>"></p>
<p><input type="text" name = "name" value = "<?=$name?>"></p>
<p><input type="text" name = "phone" value = "<?=$phone?>"></p>
<p><input type="text" name = "address" value = "<?=$address?>"></p>
<p><input type="text" name = "comment"  value = "<?=$comment?>"></p>

<!--<h2>Visible to :</h2>
<input type="radio" name="allmycontacts" value="All my Contacts">All my Contacts
<input type="radio" name="selectwho" value="Select Who">Select Who
<input type="radio" name="public" value="Public">Public -->

<form action="" method="POST">
	
		<?php
		
		 //this code below will get the username (phone number) of contacts
 // for $user_id. we get the 'contact_id'
 //values in the contacts table for $user_id, match those contact_ids to the corresponding 
 //'user_ids' in the user table, and then show the 'usernames' for each of those user_ids
 $select_from_user_table = "SELECT  contacts.contact_id, user.username
FROM contacts 
INNER JOIN user
ON contacts.contact_id=user.user_id WHERE contacts.user_id = '$user_id'";

	//get the result of the above
	$result2=mysqli_query($con,$select_from_user_table);

		
	//show the usernames, phone numbers
	while($row = mysqli_fetch_assoc($result2)) { ?>
	 <input type='checkbox' name='check_contacts[]' value='<?=$row['contact_id']?>'> <?php echo $row['username'] ?> </br>
    
		<?php	
		//we need the php bracket below to close the while loop


		}

			?>
	
    <!--<input type="submit" name = "create" value = "Create new Contact"></p> -->
	
	
	
	<!--</form> -->


<p><input type="submit" name = "Save" value = "Save"></p>
<p><input type="submit" name = "Delete" value = "Delete"></p>
<a href="exit.php">Exit</a>
</form>

</body>
</html>
