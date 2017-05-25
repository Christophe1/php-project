<?php
//user_id_contacts_2_fk
//contacts_ibfk_1

//***************************************************
require('dbConnect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//this is me, +567890123, my user_id in the user table
$user_id = '20';

//post all contacts in my phone as a JSON array
$json = $_POST['phonenumber'];
$array = json_decode($json);

	foreach ($array as $value)
	{
		
		$phonenumber = $value->phone_number;
		
//***** NOT SECURE
		
//		$sql = "SELECT * FROM user WHERE username = '$phonenumber'";
//		$result = mysqli_query($con, $sql);
//$num_rows = mysqli_num_rows($result);
//*****

//*****SECURE
if ($stmt = $con->prepare("SELECT * FROM user WHERE username = ?")) {
$stmt->bind_param('s', $phonenumber);
$stmt->execute();

$num_rows = $stmt->num_rows();
//*****


	
	if($num_rows > 0) {

	echo "phonenumber is " . $phonenumber  . "<br>";
	
	// we want to put $phonenumber in the contacts table, as one of +567890123 contacts
	// In the user table get the associated rows of $phonenumber
	
//*****SECURE	
//	while ($row = $stmt->fetch_array(MYSQLI_ASSOC)) {
//*****		
		// get all row info in the user table : user_id and username
   	  while ($row = mysqli_fetch_assoc($result)) {

	// get the associated user_id in that row, that's what we want to put into the contacts table
	   $contact_id = $row['user_id'];
	   
/* 	   $sql2 = "SELECT count(*) FROM contacts WHERE contact_id = '$contact_id'";
	   $result2 = mysqli_query($con, $sql2);
	   $num_rows2 = mysqli_num_rows($result2);
	
	if($num_rows2 > 0) {
		
		echo "Duplicates";
		
	}
	
	else
		
		{ */
	   
	 //  echo "the user id of the contact is ", $contact_id;
	$insert_into_contacts_command = "INSERT INTO contacts VALUES(NULL, '$user_id','$contact_id')";
	$insert_into_contacts_table = mysqli_query($con,$insert_into_contacts_command);
		//}
	}
	//if ( false===$insert_into_contacts_table ) {
 // printf("error: %s\n", mysqli_error($con));
} 
	
	
	//if $phonenumber is NOT in the user table...
else {
	printf(mysqli_error($con));
	echo 'not a match.';
	} 
	}
	}



//THIS IS WORKING FOR INSERTING ALL NUMBERS INTO THE USER TABLE****************************************************
/* header('Content-Type: text/html; charset=utf-8');
require('dbConnect.php');

$json = $_POST['phonenumber'];
$array = json_decode($json);
//for($i=0; $i<count($array['phonenumber']);$i++) {
	foreach ($array as $value)
	{
		$phonenumber = $value->phone_number;
		$insert_into_user_command = "INSERT INTO user (username) VALUES ('$phonenumber')";
        $insert_into_user_table = mysqli_query($con,$insert_into_user_command);
		echo $value->phone_number ;
		//echo $value-> jsonArrayContacts;
	} */


//*******************************************************
/* require('dbConnect.php');
//this is me, +353872934480, my user_id in the user table
$user_id = '20';
//this is one of my contacts in my phone, for example +353864677745
//$CheckContact = '+123';
$CheckContact = $_POST['alContacts'];

foreach($_POST['phonenumber' as $CheckContact]{
//$CheckContact = $_POST['phonenumber'];
//see if +353864677745 is a user of my app - if they are in the user table. 
$sql = "SELECT * FROM user WHERE username = '$CheckContact'";
$result = mysqli_query($con, $sql);
$num_rows = mysqli_num_rows($result);

//if +353864677745 is in the user table...
	
	if($num_rows > 0) {
	echo 'success'; //. "<br>";
	echo "CheckContact is " . $CheckContact  . "<br>";
	// we want to put +353864677745 in the contacts table, as one of +353872934480 contacts
	// get the associated rows of $CheckContact
	   $row = mysqli_fetch_assoc($result);
	// get the associated user_id in that row, that's what we want to put into the contacts table
	   $contact_id = $row["user_id"];
	   echo "the user id of the contact is ", $contact_id;
	$insert_into_contacts_command = "INSERT INTO contacts VALUES(NULL, '$user_id','$contact_id')";
	$insert_into_contacts_table = mysqli_query($con,$insert_into_contacts_command);
	if ( false===$insert_into_contacts_table ) {
  printf("error: %s\n", mysqli_error($con));
}

//if +353864677745 is NOT in the user table...
else {
	
	echo 'failure';
	}
}
} */
	
	        //I need to check if a contact in the user's phone contacts is already a user of populisto.
        //If yes, then in the contacts table put in the user's user_id
        //and the contact's user id in contacts_id
// **************************************************
/* $CheckIfNumberIsaContact = $_POST['phonenumber'];
//$CheckIfNumberIsaContact = "54d54";
$sql = "SELECT username FROM user WHERE username = ?";

$stmt = $con -> prepare($sql);

	$stmt -> bind_param('s',$CheckIfNumberIsaContact);

	$stmt ->execute(); 

//save it as a variable so it can be reused later	
$stmt ->bind_result($username);
	
 	if($row = $stmt ->fetch())
	
	{
		echo "success";
	
	}
else {
	echo "failed";
} */

?>
