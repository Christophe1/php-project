
<?php require('dbConnect.php'); 

session_start();
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
echo $username;
echo $user_id;
// Create new record in the db, if the 'Add New Record' button is clicked

//I want cat_id

if (isset($_POST['create'])) {
	
}

	$category = '';
	$name = '';
	$phonenumber = '';
	$address = '';
	$comment = '';

$con->close();


?>

<!doctype html>
<html>
<body>
<h2>Create new category</h2>
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

<p><input type="submit" name = "create" id = "create" value = "Create new Record"></p>

</form>

</body>
</html>
