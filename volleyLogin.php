<?php
require('dbConnect.php');

/* if (!isset($_POST['username'])) {
	session_start();
$username = $_SESSION['username'];
	
} */


//helps stop sql injection
$username = mysqli_real_escape_string($con,$_POST['username']);
//define this so user_id doesn't give us an error
//$user_id="";


//username and user_id
$sql = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($result);
$user_id = $row["user_id"];

//Select categories and matching name and phone created by this user
//take it from the review table, rather than the category table, so below needs to be changed
//Select everything from the review table, check it against the User table and return values where things in the user_id
//column in review table are equal to user_id in the user table, and where username in the user table = "logged in user"
//$sql2 = "SELECT * FROM review INNER JOIN user ON review.user_id =
//user.user_id WHERE user.username = '$username' ";

$sql2 = "SELECT review.user_id FROM review WHERE review.user_id = '$user_id' ";

//$sql2 = "SELECT category.cat_name FROM category INNER JOIN user ON category.user_id =
//user.user_id WHERE user.username = '$username' ";

$result = mysqli_query($con,$sql);

//get the details of the relevant row in the SELECT query
$row = mysqli_fetch_assoc($result);
$user_id = $row["user_id"];
echo $user_id;


$result2 = mysqli_query($con,$sql2);

//store the session details, which can then be used on other pages
session_start();
$_SESSION['username'] = $username;
$_SESSION['user_id']= $user_id;
//$_SESSION['catname'] = $catname; 

//get the details of the relevant row in the SELECT query
/* $row = mysqli_fetch_assoc($result2);
$catname = $row["cat_name"];
$name = $row["name"];
echo $catname;
echo $name; */

//printf ("%s (%s)\n",$row["user_id"],$row["username"]);
//$checkUserID = mysql_query("SELECT fbUserID from submissions WHERE fbUserID = '$user_id'");

//if username isn't in the db
if (mysqli_num_rows($result)==0) {
    echo "Failed, sorry";
}
	
//if username is in the db
if (mysqli_num_rows($result) > 0) {
    echo "User id exists already.";
	
	//store the session details, which can then be used on other pages
/* 	session_start();
$_SESSION['username'] = $username;
$_SESSION['user_id']= $user_id;
$_SESSION['catname'] = $catname; */

		//if username has categories in the db
	        while($rows = mysqli_fetch_assoc($result2)) {
// output data of each category in the row
$review_id = $rows['review_id'];
$cat_name = $rows['cat_name'];
$name = $rows['name'];
$phone = $rows['phone'];

        echo "<p> Category: $cat_name </p>";
		echo "<p> Name: $name </p>";
		echo "<p> Phone Number: $phone </p>";
		

		
//store the session details, which can then be used on other pages
/* 	session_start();
$_SESSION['catname'] = $catname;
$_SESSION['name']= $name;
$_SESSION['phone']= $phone; */
		?>
		<!-- show the + button, click for more details -->
				<html>
<body>

<form action="showreview.php?id=<?=$row['review_id']?>" method="post">
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

