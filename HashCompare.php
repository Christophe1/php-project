<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require('dbConnect.php');

//**************THIS IS FOR INSERTING A PHONE NUMBER INTO USER TABLE *************************

//post the hash, generated in Android
//$Hash = $_POST['hashpass'];
$Hash = "06fc4db34c302f8c38294f7763f22c7a";

// check to see if the hash exists in the user table
 				$query = "SELECT * FROM user WHERE hash = ?";
				$stmt = $con->prepare($query) or die(mysqli_error($con));
				$stmt->bind_param('s', $Hash) or die ("MySQLi-stmt binding failed ".$stmt->error);
				$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    $result = $stmt->get_result();
			
			   //If the hash exists...
			    If ($result->num_rows > 0) {
					
					 echo "True";
					 $stmt->close();

				}
				else {
				//If the hash doesn't exist...
					echo "False";
					$stmt->close();
					return false;
        }

?>