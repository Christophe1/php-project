
<?php require('dbConnect.php'); 

if (isset($_POST['create'])) {
		
	$category = ($_POST['category']);
	$name = ($_POST['name']);
	$phonenumber = ($_POST['phonenumber']);
	$address = ($_POST['address']);
	$comment = ($_POST['comment']);
	//check if the category being entered is already there
	$select_from_cat_table = "SELECT * FROM category WHERE cat_name = '$_POST[category]'";
	$result=mysqli_query($con,$select_from_cat_table);
	$num_rows = mysqli_num_rows($result);
	
	  // get the matching cat_id 
	   $row = mysqli_fetch_assoc($result);
	   	

       //$cat_id = $row["cat_id"];

//	   ****FIRST PART****    $CAT_ID IS INSERTED INTO THE DB
	   
//if the category name already exists in the category table, then don't add it in again
	if($num_rows >= 1) {
    echo "This Already Exists<br/>";
	//but do add it to the review table
	//for the cat_id, we want to get the cat_id of the category name that already exists, that has 
	//just been posted. This is $cat_id. $user_id is the user id of the person posting
	$insert_review_command = "INSERT INTO review VALUES(NULL,'$cat_id','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";
    $insert_into_review_table = mysqli_query($con,$insert_review_command);
	
}

//	   ****SECOND PART****    $CAT_ID IS NOT INSERTED INTO THE DB
else if ($num_rows < 1) 
	
{
	//if it's not in there, then add the category in the category table.
	$insert_category_command = "INSERT INTO category VALUES(NULL, '{$category}', '$user_id')";
    $insert_into_category_table = mysqli_query($con,$insert_category_command);
	$cat_id = mysqli_insert_id($con);
	//****WHY IS CAT_ID NOT WORKING HERE????******
	
	//and add it to the review table
	//for the cat_id, we want to get the cat_id of the category name that already exists, that has 
	//just been posted. This is $cat_id. $user_id is the user id of the person posting

	$insert_review_command = "INSERT INTO review VALUES(NULL,'$cat_id','{$category}','$user_id', '{$name}','{$phonenumber}','{$address}', '{$comment}')";
    $insert_into_review_table = mysqli_query($con,$insert_review_command);

echo "Yes, it's been added correctly";
echo $cat_id;


}


$con->close();
header('Location:volleyLogin.php');
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