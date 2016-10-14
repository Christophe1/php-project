<?php

require('dbConnect.php');

$username = $_POST['username'];
//$username = "Mr Smith";
//need to keep this in a session, for other pages later on
session_start();
$_SESSION['username'] = $username;

$sql = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($con,$sql);


//printf ("%s (%s)\n",$row["user_id"],$row["username"]);
//$user_id = $row['user_id'];
//$user_id = $row["user_id"];

$check = mysqli_fetch_array($result);
$row = mysqli_fetch_assoc($result);
printf ("%s (%s)\n",$row["user_id"],$row["username"]);

if(isset($check)) :
//echo $user_id;
//if the username exists in the database, then show a html submit button
$con->close();
?>
     <html>
<body>
<form action="UserDetails.php" method="post">
 <input type="submit">
</form>
     </html>

<?php  else :{
    //if user is not in db, show this message
         echo 'Sorry about that, you cant come in.';
     }
     $con->close();
 ?>
 <?php endif; ?>