<?php
require('dbConnect.php');

//search
	$output='';
if(isset($_POST['search'])){
	//make a search query string
	$searchq = $_POST['search'];
	//only allow letters and numbers, replace anything not in this category with empty
	$searchq = preg_replace("#[^0-9a-z]#i","",$searchq);
	//get everything in the cat_name column of the review table that sounds like searchq
	$query = mysqli_query($con,"SELECT cat_name FROM review WHERE cat_name LIKE '%$searchq%'") or die("could not search");
	$count = mysqli_num_rows ($query);
	//get the number of rows that sound like our search query
	
	if($count == 0){
		$output = 'There are no search results';
	}
		else {
			while ($row = mysqli_fetch_array($query)) {
			$cat = $row['cat_name'];
			
			$output .='<div> ' .$cat. '</div>';
			
			
			
		}
		//$_SESSION['search'] = $output;
	}
}
?>

<html>
	<body>

		<?php print("$output"); ?>

	</body>
</html>