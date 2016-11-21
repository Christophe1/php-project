<?php 

require('dbConnect.php');

//select everything from user
	$sql = "SELECT * FROM user ";

	$get_value = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_assoc($get_value)) {
		echo "<br>";
        echo $row["username"];
		echo "testing";
	}
?>