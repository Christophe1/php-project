
<?php require('dbConnect.php'); 

session_start();
//review = $_SESSION['review'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
echo "user id is " . $user_id . "<br>";
echo "user name is " . $username . "<br>";
echo "review id is " . $_GET['id'] . "<p>";

//these values are being remembered from the showreview.php page
$category=$_SESSION['cat_name'];

		$name=$_SESSION['name'];
		$phone=$_SESSION['phone'];
		$address=$_SESSION['address'];
		$comment=$_SESSION['comment'];

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
	//	header('Location:volleyLogin.php');
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

		//we want to get contacts who the user shares the review with, which are those contacts in review_shared

$sql="SELECT contact_id FROM review_shared where user_id = '$user_id' AND review_id = " .$_GET['id'] ;
//make it into an array, so we can compare it against all the contacts the user has, in the contacts table
$review_shared = array();
$result = mysqli_query($con,$sql);
//fill the array
while ($row = mysqli_fetch_assoc($result))
{
  $review_shared[] = $row['contact_id'];
  //echo $row['contact_id'];
		//show the usernames, phone numbers
}
	
     // $review_shared=array(3,5,6);//get contact_id in this from shared table
     while($row = mysqli_fetch_assoc($result2)) {
		 //if contact_id in the contacts table is also in the review_shared table
        if(in_array($row['contact_id'],$review_shared)){ ?>
             <input type='checkbox' name='check_contacts[]' value='<?=$row['contact_id']?>' checked="checked"> <?php echo $row['username'] ?> </br>
         <?php  }else{?>
        <input type='checkbox' name='check_contacts[]' value='<?=$row['contact_id']?>' > <?php echo $row['username'] ?> </br>           
   <?php }}?>
	
	

<p><input type="submit" name = "Save" value = "Save"></p>
<p><input type="submit" name = "Delete" value = "Delete"></p>
<a href="exit.php">Exit</a>
</form>

</body>
</html>

	<?php
//more php code, here we want to save the checked contacts to the review_shared table ;  that is,
//who the user wants to share reviews with
if(!empty($_POST['check_contacts'])) {
	
			$already_checked = "DELETE from review_shared WHERE user_id = '$user_id' AND review_id = " .$_GET['id'];
		
		//echo "<pre>$already_checked</pre>";
		mysqli_query($con,$already_checked);
	

		
	foreach($_POST['check_contacts'] as $check) {
	
			//$check="";
	//if the contact in review_shared is already checked, we don't want to save it multiple times
/* 		$already_checked = "DELETE * from review_shared WHERE user_id = '$user_id' AND contact_id = '$check' AND review_id = " .$_GET['id'];
		
		mysqli_query($con,$already_checked); */
	  //  $num_rows = mysqli_num_rows($already_checked_result);
		
	/* 	if($num_rows >= 1) {
		echo "This is already a contact";
		}  */
		
	//else if ($num_rows < 1) {
	
		//$_GET['id'] is the current review for which contacts are being edited, we are checking a contact to share that review with
			$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL," .$_GET['id']. ", '$user_id','$check')";

		//we want to save the checked contacts into the review_shared table
		//$insert_review_shared_command = "INSERT INTO review_shared VALUES(NULL, '1', '2')";
		$insert_into_review_shared_table = mysqli_query($con,$insert_review_shared_command);
            echo $check; //echoes the value set in the HTML form for each checked checkbox.
                         //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                         //in your case, it would echo whatever $row['Report ID'] is equivalent to.
		// }
	//}
	
	//	go to the VolleyLogin page when changes have been saved
    header('Location:volleyLogin.php');
}
}



	$con->close();

	
	?> 
