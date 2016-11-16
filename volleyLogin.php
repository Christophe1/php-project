



<html>
	<body>
		<form action = 'search.php' method='post'>
			<input type = 'text' name='search' size='50' />
			<input type = 'submit' value = '>>'/>
		</form>
		

	</body>
</html>

<?php

require('dbConnect.php');
//better to have this right at the top, didn't work otherwise.
	session_start();
	
//if the session is already active, like we are coming back to this page from AddNew.php
	if (session_status() == PHP_SESSION_ACTIVE) {
//session_start();
	$username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

 //if user is logging in
	if(isset($_POST['username'])){
//helps stop sql injection
	$username = mysqli_real_escape_string($con,$_POST['username']);

 } 

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


	$sql2 = "SELECT * FROM review WHERE user_id = '$user_id'";


	$result2 = mysqli_query($con,$sql2);

//if username isn't in the db
	if (mysqli_num_rows($result)==0) {
    echo "Failed, sorry";
}
	
//if username is in the db
	if (mysqli_num_rows($result) > 0) {

		//if username has reviews in the db
	while($rows = mysqli_fetch_assoc($result2)) {

        $review_id=$rows['review_id'];
		$_SESSION['review'] = $review_id;
	
		echo "review id is " . $review_id  . "<br>";
		echo  "<br>";
        echo "Category: " . $rows['cat_name'] . "<br>";
		echo "Name: " . $rows['name'] . "<br>";
		echo "Phone: " . $rows['phone'] . "<br>";

//html stuff comes next
		?>
		<!-- show the + button, click for more details -->
				<html>
	<body>

	<form action="showreview.php?id=<?=$review_id?>" method="post">
	<input type="submit" value="+" name="show_review"><br>

	</form>
	<p></p>
	</body>
	</html>
		
		<?php	
}

			?>
			
		<html>
	<body>

	<form action="AddNew.php" method="post">
	<input type="submit" value="Add New" name="username"><br>

	</form>

	</body>
	</html>
		
<?php		



}


	$con->close();
?>

