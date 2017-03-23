<?php

require('dbConnect.php');

session_start();
$user_id = $_SESSION['user_id'];

//if a person arrives at this page other than doing a search then go to the main page
if(!isset($_POST['search'])) {
	header("Location:volleyLogin.php");
}

//it's either a first word or it has at least one space in front, we need to look through the users' categories
$search_sql="SELECT * FROM review WHERE user_id = '$user_id' AND  (cat_name LIKE '".$_POST['search']."%' or cat_name LIKE '% ".$_POST['search']."%')";
$search_query=mysqli_query($con,$search_sql);

//Now we need to look through the user's contacts' categories
 $search_sql2 = "SELECT review.cat_name, review.name, review.phone, review.user_id
FROM review
JOIN review_shared
ON
review.review_id=review_shared.review_id WHERE 
review_shared.contact_id 
= '$user_id' AND (review.cat_name LIKE '".$_POST['search']."%' or review.cat_name LIKE '% ".$_POST['search']."%')";

$search_query2=mysqli_query($con,$search_sql2);

// Return the number of rows in result set
$rowcount=mysqli_num_rows($search_query2);

//print the reviews of the logged in user under the category in question
if(mysqli_num_rows($search_query)>0) {

while($rows = mysqli_fetch_assoc($search_query)) {
	
	echo $rows['cat_name'] . "<br>";
 	echo $rows['name'] . "<br>";
	echo $rows['phone'] . "<p>"; 
		
}} 

//print the reviews of the logged in user's contacts under the category in question
if(mysqli_num_rows($search_query2)>=0) {

while($rows = mysqli_fetch_assoc($search_query2)) {
	
		//In review_shared, show how many times contact_id = $user_id when
//cat_name  = $_POST['search']
$search_query4 = 
	
	//this is to get the corresponding username of the user_id, it is in the user table
	$search_query3="SELECT * FROM user WHERE user_id = $rows[user_id]";

	$result=mysqli_query($con,$search_query3);
	//get the associated row
	$row=mysqli_fetch_assoc($result);
	echo $row['username'] . ":" . "<br>";
	//echo $rows['user_id'] . "<br>";
	echo $rows['cat_name'] . " " . $rowcount . "<br>";
 	echo $rows['name'] . "<br>";
	echo $rows['phone'] . "<p>"; 
		
}

} 

else {

//if(mysqli_num_rows($search_query)==0 AND mysqli_num_rows($search_query2==0)) {
	
	echo "No results";
	
}
?>

