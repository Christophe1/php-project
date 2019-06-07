<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require('dbConnect.php');

/* $user_id = $_POST['jsonarray'];
//$contact_id = 37;
$id_list = array(1,3,7);
$id_list2 = implode(',', $id_list);

                
				$query5 = "DELETE FROM test_table WHERE user_id = ? AND contact_id NOT IN ($id_list2)";

                //$query5 = "DELETE FROM contacts WHERE user_id = ? AND contact_id = ?";
				$stmt5 = $con->prepare($query5) or die(mysqli_error($con));
				$stmt5->bind_param('i', $user_id) or die ("MySQLi-stmt binding failed ".$stmt5->error);

				//$stmt5->bind_param('ii', $user_id, $contact_id) or die ("MySQLi-stmt binding failed ".$stmt5->error);
				$stmt5->execute() or die ("MySQLi-stmt execute failed ".$stmt5->error);
				$stmt5->close();  
				
				echo "done"; */


//**************THIS IS FOR INSERTING A PHONE NUMBER INTO USER TABLE *************************

//post the phone number of the user, which in the table is username
$Number = $_POST['phonenumberofuser'];
//post the hash, generated in Android
$Hash = $_POST['hashpass'];
//post the timestamp from Android
$TimeStamp = $_POST['timestamp'];

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// check to see if the username exists in the user table/* 
  				$query = "SELECT * FROM user WHERE username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   //if the username is not already in the user table, then put it in.
			   //the other value in the table, user_id, is auto incremented, so it is inserted automatically
			   //also put in hashpass and timestamp
			    If ($result->num_rows == 0) {
				$stmt2 = $con->prepare("INSERT INTO user (username,hash,timestamp) VALUES(?,?,?)") or die(mysqli_error($con));
				$stmt2->bind_param('sss', $Number, $Hash, $TimeStamp) or die ("MySQLi-stmt binding failed ".$stmt2->error);
				$stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error); 
				//$result2 = $stmt2->get_result(); 
				}
				
				
				//if the username is already in the db, then UPDATE the respective values
 				else 
 
					{
					$stmt5 = $con->prepare("UPDATE user SET hash = ?,timestamp = ? WHERE username = ?") or die(mysqli_error($con));
					$stmt5->bind_param('sss', $Hash, $TimeStamp,$Number ) or die ("MySQLi-stmt binding failed ".$stmt5->error);
					$stmt5->execute() or die ("MySQLi-stmt execute failed again ".$stmt5->error);		
						
					}   
					
					echo "Hello world!"; 
				
//print_r($stmt);

?>

