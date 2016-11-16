<?php require('dbConnect.php'); 

//use the variables we created in volleyLogin.php
	session_start();
	$username = $_SESSION['username'];
	$user_id = $_SESSION['user_id'];
	echo "user name is " . $username . "<br>";
	echo "user id is " . $user_id . "<br>"; 
	
/* 	//select everything from user
	$sql = "SELECT * FROM user WHERE username = '$username'";
//get the result of the above
	$result = mysqli_query($con,$sql);
//get every other record in the same row 
	$row = mysqli_fetch_assoc($result);
//make the user_id record in that row a variable 
	$user_id = $row["user_id"];
	//$cat_id = $row["cat_id"]; */

if (isset($_POST['create'])) {
	
	
//	$sql = "INSERT INTO category VALUES(cat_id, cat_name, user_id) VALUES('123', 'Bin man', '456')";
	
	$category = ($_POST['category']);
	$name = ($_POST['name']);
	$phonenumber = ($_POST['phonenumber']);
	$address = ($_POST['address']);
	$comment = ($_POST['comment']);
	
	$check="SELECT COUNT(*) FROM category WHERE cat_name = '$_POST['category]'";
if (mysqli_query($con,$check)>=1)
{
    echo "User Already in Exists<br/>";
}
	
	
	$sql = "INSERT INTO category VALUES(NULL, '{$category}', '$user_id')";

//in the review table, create a new id, put in the cat_id it comes under, the user id...
	//$sql2 = "INSERT INTO review(review_id, cat_id) VALUES(NULL,'456')";

//	$sql2 = "INSERT INTO review VALUES(NULL,'22','bobby','$user_id', 'bobby','bobby','bobby', '1')";

//	$sql2 = "INSERT INTO review VALUES(NULL,'222','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";

	
		if ($con->query($sql) === TRUE) {
echo "successse";
	//header('Location:volleyLogin.php');

	} else {
	echo "Error: " . $sql . "<br>" . $con->error;
}
//echo "done";
}


	$con->close();


?>

	<!doctype html>
	<html>
	<body>
	<h2>Create new Contact</h2>
	<form method="post" action="" name="frmAdd">
	<p><input type="text" name = "category" id = "category" placeholder = "category"></p>
	<p><input type="text" name = "name" id = "name" placeholder = "name"></p>
	<p><input type="text" name = "phonenumber" id = "phonenumber" placeholder = "phone number"></p>
	<p><input type="text" name = "address" id = "address" placeholder = "address"></p>
	<p><input type="text" name = "comment" id = "comment" placeholder = "comment"></p>
	<h2>Visible to :</h2>
	<input type="radio" name="allmycontacts" value="All my Contacts">All my Contacts
	<input type="radio" name="selectwho" value="Select Who">Select Who
	<input type="radio" name="public" value="Public">Public
	<input type="radio" name="justme" value="Just me">Just me

	<p><input type="submit" name = "create" id = "create" value = "Create new Contact"></p>
	<a href="exit.php">Exit</a>

	</form>

	</body>
	</html>