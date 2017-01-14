


<?php require('dbConnect.php');

//use the variables we created earlier
	session_start();
	$user_id = $_SESSION['user_id'];
	echo "user id is " . $user_id . "<br>"; 

/* 
	//we want to show contact_ids in the contacts table that have the $user_id above. 
	$select_from_contacts_table = "SELECT * FROM contacts WHERE user_id = '$user_id'";
	
	//get the result of the above
	$result=mysqli_query($con,$select_from_contacts_table);
	
	while($row = mysqli_fetch_assoc($result)) {
	$contact_id=$row['contact_id'];
	echo "<input type='checkbox' name='vehicle' value='bike' checked>" . "contact is " . $contact_id  . "<br>";
 } 
  */
 

 //I'm using SELECT DISTINCT because I was getting Duplicate results
 //this code below will print the username, or phone number, of contacts
 $select_from_user_table = "SELECT  contacts.contact_id, user.username
FROM contacts 
INNER JOIN user
ON contacts.contact_id=user.user_id WHERE contacts.user_id = '$user_id'";

	//get the result of the above
	$result2=mysqli_query($con,$select_from_user_table); 
	//show the usernames, phone numbers
	while($row = mysqli_fetch_assoc($result2)) {
	echo "<input type='checkbox' name='vehicle' value='bike' checked>" . $row['username']  . "<br>";
}
 
 
 
   
	$con->close();
	
	?>