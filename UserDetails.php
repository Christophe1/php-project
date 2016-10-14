<?php
//$username = real_escape_string($_POST['username']);

//$username = $_GET['username'];
//include('volleyLogin.php');
require('dbConnect.php');
//3. $username = $_GET['username'];
//2. if (isset($_POST['username'])) {
//1. $username = real_escape_string($_POST['username']);



	session_start();
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

//echo  $username;
 //}
//Select from the cat_name column in the category table, check it against the User table and return values where things in the user_id
//column, in category table are equal to user_id in the user table, and where username in the usertable = "logged in user"
$resultSet = $con->query("SELECT category.cat_name FROM category INNER JOIN user ON category.user_id =
user.user_id WHERE user.username = '$username' ")
or die($con->error);


if ($resultSet->num_rows > 0) {

        while($rows = $resultSet->fetch_assoc()) {
// output data of each row
$catname = $rows['cat_name'];
//$useriid = $rows['user_id'];
        //$catname = catname;

        echo "<p> Name: $catname </p>";
}}
        else {
   echo "0 results";
}
//echo "userdetails.php works";

$con->close();  

//var_dump($_POST);
?>
<html>
<body>

<form action="AddNew.php" method="post">
<input type="submit" value="Add New" name="username"><br>

</form>

</body>
</html>
 