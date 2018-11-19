<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require('dbConnect.php');

//**************THIS IS FOR CHECKING IF LOGGED-IN USER HAS ALREADY REGISTERED *************************

//post the hash, generated in Android
$Hash = $_POST['hashpass'];
//$Hash = "06fc4db34c302f8c38294f7763f22c7a";

//post the user's phone number
$Number = $_POST['phonenumberofuser'];

// check to see if the hash exists in the user table
 				$query = "SELECT * FROM user WHERE hash = ? AND username = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('ss', $Hash, $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   
				   //If the hash exists...
			    If ($result->num_rows > 0) {
					
					 echo "True";
					 //echo $Hash;

					 $stmt->close();

				}
			  // }
				else {
				//If the hash doesn't exist...
					echo "False";
					//echo $Hash;
					$stmt->close();
					//return false;
        }

?>
