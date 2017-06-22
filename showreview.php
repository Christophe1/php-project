<?php
require('dbConnect.php');

session_start();
$review = $_SESSION['review'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
echo "user id is " . $user_id . "<br>";
echo "user name is " . $username . "<br>";
echo "review id is " . $_GET['id'] . "<p>";
//echo $review_id;
//if(isset($_POST['show_review'])){

	
	//get info from all cells in the row where review_id is equal to the review selected. The name, address etc...
	$sql = "SELECT * FROM review WHERE review_id = " .$_GET['id'];
	//get the result of the above
	$result = mysqli_query($con, $sql);
	
	//*****************************************************
	//Don't think we need the 'if', as there will always be more than one review for the review we selected
	//if reviews from the user exist, show them
	if (mysqli_num_rows($result) > 0) {
	//*****************************************************
	//show review details for the review selected
		$row = mysqli_fetch_assoc($result);
        echo "Category: " . $row['cat_name'] . "<br>";
		echo "Name: " . $row['name'] . "<br>";
		echo "Phone: " . $row['phone'] . "<br>";
		echo "Address: " . $row['address'] . "<br>";
		echo "Comment: " . $row['comment'] . "<br>";
		echo "<br>";
		echo "Shared with :" . "<br>";
	
	}
	
		// make the specified category cell etc into a variable
	    $category=$row['cat_name'];
		$name=$row['name'];
		$phone=$row['phone'];
		$address=$row['address'];
		$comment=$row['comment'];
		
		
		
		
		//store the value of that variable, which we can use when we start a session, later
		$_SESSION['cat_name'] = $category;
		$_SESSION['name'] = $name;
		$_SESSION['phone'] = $phone;
		$_SESSION['address'] = $address;
		$_SESSION['comment'] = $comment;
	
	
			//we want to show the contacts that this review is shared with		
	$select_from_review_shared ="SELECT  review_shared.contact_id, user.username
FROM review_shared 
INNER JOIN user
ON review_shared.contact_id=user.user_id WHERE review_id = "  .$_GET['id'];
		
		
			//get the result of the above
	$result2=mysqli_query($con,$select_from_review_shared);

	//show the usernames, phone numbers
	while($row = mysqli_fetch_assoc($result2)) { 
	//$contact_id = $row["contact_id"];
	 echo $row["username"] . "<br>";
		
	}

	$con->close();
	
	?>
	
<html>
<body>
<form action="edit.php?id=<?=$_GET['id']?>" method="post">
<input type="submit" value="+" name="Edit"><br>

</form>
<p></p>
</body>
</html>
	
	