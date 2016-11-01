<?php

require('dbConnect.php');
//$username = "gg";

//if($_SERVER['REQUEST_METHOD']=='POST'){
$username = $_POST['username'];
//$userid = $_GET['user_id'];
//how can i get this part ??
//$userid = $_GET[user.user_id];

//need to keep this in a session, for other pages later on
//session_start();
//    $_SESSION['username'] = $username;
//	$_SESSION['user_id'] = $user_id;
	
//	$_SESSION['user_id'] = $userid;
	
	// Get it from the db, after login

//$_SESSION['user_id'] = $userid;
	
//$username = $con->real_escape_string($_POST['username']);

$sql = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($con,$sql);

$check = mysqli_fetch_array($result);
//get the row in the db that contains our $username
$row = mysqli_fetch_assoc($result);
//in that row, get the user_id
$user_id = $row['user_id'];

//session_start();
  //  $_SESSION['user_id'] = $user_id;

if(isset($check)) :

/* session_start();
    $_SESSION['username'] = $username;
	$_SESSION['user_id'] = $user_id; */
echo 'You are in';
echo '<br/>';
echo $username;
echo $user_id;
//echo $user_id;
//if the username exists in the database, then show a html submit button
$con->close();
?>
<!--      <html>
<body>
<form action="UserDetails.php" method="post">
 <input type="submit">
</form>
     </html> ->

<?php  else :{
	//if user is not in db, show this message
		 echo 'Sorry about that';
	 }
	 $con->close();
 ?>
 <?php endif; ?>