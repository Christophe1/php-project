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
	//check if the category being entered is already there
	$check="SELECT COUNT(*) FROM category WHERE cat_name = '$_POST[category]'";
$get_value = mysqli_query($con,$check);
//check the number of values of the category being posted
$data = mysqli_fetch_array($get_value, MYSQLI_NUM);
//if the category name already exists in the category table
if($data[0] >= 1) {
    echo "This Already Exists<br/>";
}

else if ($data[0] < 1)
{
	//if it's not in there, then add the category in the category table.
$sql = "INSERT INTO category VALUES(NULL, '{$category}', '$user_id')";
$rs1=mysqli_query($con, $sql); 
$rs2=mysqli_query($con, $sql);
//$sql = ("INSERT INTO category VALUES(NULL, '{$category}', '$user_id')"); ("INSERT INTO review VALUES(NULL,'22','bobby','$user_id', 'bobby','bobby','bobby', '1')");

//$sql2 = "INSERT INTO review VALUES(NULL,'222','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";
//$rs2=mysqli_query($con, $sql2);
//$sql = "INSERT INTO review VALUES(NULL,'22','bobby','$user_id', 'bobby','bobby','bobby', '1')";
//echo "this come up twice?";

		if ($con->query($sql) === TRUE) {
echo "Yes, it's been added correctly";

	//header('Location:volleyLogin.php');

	} else {
	echo "Error: " . $sql . "<br>" . $con->error;
}

}
$con->close();
}
	


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