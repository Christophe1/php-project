<?php
require('dbConnect.php');

	session_start();
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

	//session_start();
//$cat_name = $_SESSION['cat_name'];
//$name = $_SESSION['name']; 
//$phone = $_SESSION['phone'];

//select everything from the review table for a particular review

echo  "<p>$username</p>";
//echo  "<p>Category: $cat_name</p>";
//echo  "<p>Name: $name</p>";
//echo  "<p>Phone number: $phone</p>";
//echo  "<p>Address: $address</p>";
//echo  "<p>Comment: $comment</p>";
?>