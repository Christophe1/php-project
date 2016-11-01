<?php
require('dbConnect.php');


// let's keep our sessions at top, so define as blank username and user_id
//$username = ''; 
//$user_id = ''; 

/* session_start();
$username='';
$user_id='';
$_SESSION['username'] = $username;
$_SESSION['user_id'] = $user_id;  */

 
if (session_status() == PHP_SESSION_ACTIVE) {
//session_start();
	$username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

 //$username = "";

 if(isset($_POST['username'])){
//helps stop sql injection
$username = mysqli_real_escape_string($con,$_POST['username']);
//$user_id = '';
//var_dump($_POST);
 } 

/* if (session_status() == PHP_SESSION_NONE) {	 
session_start();
$_SESSION['username'] = $username;
$_SESSION['user_id'] = $user_id; 

 } */

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


/* $username = $_SESSION['username'];
$user_id = $_SESSION['user_id']; */
session_start();
$_SESSION['user_id']= $user_id;
$_SESSION['username'] = $username;


//Select categories and matching name and phone created by this user
//take it from the review table, rather than the category table, so below needs to be changed
//Select everything from the review table, check it against the User table and return values where things in the user_id
//column in review table are equal to user_id in the user table, and where username in the user table = "logged in user"
//$sql2 = "SELECT * FROM review INNER JOIN user ON review.user_id =
//user.user_id WHERE user.username = '$username' ";


//$row = mysqli_fetch_assoc($result2);


//get the details of the relevant row in the SELECT query
/* $row = mysqli_fetch_assoc($result2);
$catname = $row["cat_name"];
$name = $row["name"];
echo $catname;
echo $name; */

//printf ("%s (%s)\n",$row["user_id"],$row["username"]);
//$checkUserID = mysql_query("SELECT fbUserID from submissions WHERE fbUserID = '$user_id'");
$sql2 = "SELECT * FROM review WHERE user_id = '$user_id'";

//$sql2 = "SELECT category.cat_name FROM category INNER JOIN user ON category.user_id =
//user.user_id WHERE user.username = '$username' ";

//$result = mysqli_query($con,$sql);

//get the details of the relevant row in the SELECT query
//$row = mysqli_fetch_assoc($result);
//$user_id = $row["user_id"];
//echo $user_id;


$result2 = mysqli_query($con,$sql2);
//if username isn't in the db



//store the session details, which can then be used on other pages

/* if (session_status() == PHP_SESSION_NONE) {
session_start();
$_SESSION['username'] = $username;
$_SESSION['user_id']= $user_id;
} */



if (mysqli_num_rows($result)==0) {
    echo "Failed, sorry";
}
	
//if username is in the db
if (mysqli_num_rows($result) > 0) {
 //   echo "User id exists already.";

		//if username has reviews in the db
while($rows = mysqli_fetch_assoc($result2)) {
// output data of each category in the row
/* $review_id = $row['review_id'];
$cat_name = $row['cat_name'];
$name = $row['name'];
$phone = $row['phone']; */
        $review_id=$rows['review_id'];
	//	define("review", $review_id);
		$_SESSION['review'] = $review_id;
	
		echo "review id is " . $review_id  . "<br>";
		echo  "<br>";
        echo "Category: " . $rows['cat_name'] . "<br>";
		echo "Name: " . $rows['name'] . "<br>";
		echo "Phone: " . $rows['phone'] . "<br>";

/*         echo "<p> Category: $cat_name </p>";
		echo "<p> Name: $name </p>";
		echo "<p> Phone Number: $phone </p>"; */
		

		
//store the session details, which can then be used on other pages
/* 	session_start();
$_SESSION['catname'] = $catname;
$_SESSION['name']= $name;
$_SESSION['phone']= $phone; */
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

