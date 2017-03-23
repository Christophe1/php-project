
<?php 

require('dbConnect.php');

//use the variables we created in volleyLogin.php
	session_start();
	$username = $_SESSION['username'] . "<br>";
	$user_id = $_SESSION['user_id'];
	echo $username;
	echo $user_id;
	
	//-we want to show contact_ids in the contacts table that have the $user_id above. 
	
	//select everything from user
	$sql = "SELECT * FROM user WHERE username = '$username'";
//get the result of the above
	$result = mysqli_query($con,$sql);
//get every other record in the same row 
	$row = mysqli_fetch_assoc($result);
//make the user_id record in that row a variable 
	$user_id = $row["user_id"];
	$username = $row["username"];
	echo "user id is " . $user_id . "<br>";
	echo "user name is " . $username . "<br>";

	//session_start();
	$_SESSION['user_id']= $user_id;
	$_SESSION['username'] = $username;


	//$sql2 = "SELECT * FROM review WHERE user_id = '$user_id'";
	$sql2 = "SELECT * FROM contacts WHERE user_id = '$user_id'";


	$result2 = mysqli_query($con,$sql2);

//if no contacts for the user_id
	if (mysqli_num_rows($result)==0) {
    echo "No Contacts";
}
	
//if username is in the db
	if (mysqli_num_rows($result) > 0) {

		//if username has reviews in the db
	while($rows = mysqli_fetch_assoc($result2)) {

        $contact_id=$rows['contact_id'];
	
		echo "contact is " . $contact_id  . "<br>";
		
?>