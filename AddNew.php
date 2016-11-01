<?php require('dbConnect.php'); 

session_start();
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
echo "user name is " . $username . "<br>";
echo "user id is " . $user_id . "<br>"; 

/* echo $row["user_id"];
echo $row["username"]; */
//echo "testtesting";
// Create new record in the db, if the 'Add New Record' button is clicked

//I want cat_id

if (isset($_POST['create'])) {
	
	$category = ($_POST['category']);
	$name = ($_POST['name']);
	$phonenumber = ($_POST['phonenumber']);
	$address = ($_POST['address']);
	$comment = ($_POST['comment']);
	
//create a new category id, category name in the category table, along with the person who made it
//$sql1 = "INSERT INTO category VALUES(NULL,'{$category}', '$user_id')";



//if the category has been correctly inserted into the database...
//if ($con->query($sql1) === TRUE) {
	//get the latest auto increment value of the category id so we can put it in the review table
//$category_id = mysqli_insert_id($con);
//echo $category_id;
//}

//in the review table, create a new id, put in the cat_id it comes under, the user id...
$sql2 = "INSERT INTO review VALUES(NULL,'666','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";
//$id2;
	
		if ($con->query($sql2) === TRUE) {
//			$id2 = mysqli_insert_id($con);
header('Location:volleyLogin.php');
  //  echo "New record created successfully";
	//echo "cat_id is $category_id";
	} else {
	echo "Error: " . $sql2 . "<br>" . $con->error;
}
/* if(mysqli_query($con,$sql1)){
	header('Location:volleyLogin.php');
} */


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