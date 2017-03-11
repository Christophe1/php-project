<?php
require('dbConnect.php');

session_start();
//review = $_SESSION['review'];
$user_id = $_SESSION['user_id'];

$sql="SELECT contact_id FROM review_shared where user_id = '$user_id' AND review_id = 34";

$dept = array();
$result = mysqli_query($con,$sql);
while ($row = mysqli_fetch_assoc($result))
{
  $dept[] = $row['contact_id'];
  echo $row['contact_id'];
}
var_dump($dept);

/* require('dbConnect.php');
session_start();
//review = $_SESSION['review'];
$user_id = $_SESSION['user_id'];

$numbers = array(1,2, 3, 4, 5);
 echo $user_id . "<br>";
// Loop through colors array
foreach($numbers as $value){
    echo $value . ",";
}

if (in_array(2,$numbers)) {
	echo "gotcha";
}
else {
	
	echo "no, sorry";
}

		//we want to get contacts who the user shares the review with, those contacts in review_shared
		$sql_review_shared= mysqli_query($con,"SELECT contact_id FROM review_shared where user_id = '$user_id' AND review_id = 30");

		    $userinfo = array();
    while($row_user = mysqli_fetch_array($sql_review_shared)){
    $userinfo = $row_user['contact_id'];
    }

foreach($userinfo as $usinfo){
echo $usinfo."<br/>";
} */
		
?>
