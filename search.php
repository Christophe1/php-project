
<html>
	<body>
		<form action = 'search.php' method='post'>
			<input type = 'text' name='search' size='50' />
			<input type = 'submit' value = '>>'/>
		</form>
		

	</body>
</html>

<?php
require('dbConnect.php');

//--------take out the below and it sort of works

$searchTerm = $_GET['term'];


//get info from all cells in the row where review_id is equal to the review selected. The name, address etc...
//$sql = "SELECT * FROM category WHERE cat_name = " .$_GET['id'];
//$sql = "SELECT * FROM category WHERE cat_name LIKE '%".$searchTerm."%' ORDER BY cat_name ASC";
$sql = "SELECT * FROM category WHERE cat_name LIKE '%".$searchTerm."%' AND user_id = 2 ORDER BY cat_name ASC";

//get the result of the above
$result = mysqli_query($con, $sql);

//get matched data from skills table
//$query = $db->query("SELECT * FROM review WHERE cat_name LIKE '%".$searchTerm."%' ORDER BY skill ASC");
while ($row = $result->fetch_assoc()) {
    $data[] = $row['cat_name'];
}
//return json data
echo json_encode($data);

//---------works kind of when the above is taken out

//search
	//$output='';
if(isset($_POST['search'])){
	//make a search query string
	$searchq = $_POST['search'];
	//only allow letters and numbers, replace anything not in this category with empty
	$searchq = preg_replace("#[^0-9a-z]#i","",$searchq);
	//get everything in the cat_name column of the review table that sounds like searchq
	$query = mysqli_query($con,"SELECT cat_name FROM review WHERE cat_name LIKE '%$searchq%' AND user_id = 2") or die("could not search");
	$count = mysqli_num_rows ($query);
	//get the number of rows that sound like our search query
	
	if($count == 0){
		echo 'There are no search results';
	}
		else {
			while ($row = mysqli_fetch_array($query)) {
			$cat = $row['cat_name'] . "<br>";
			
			echo $cat;
			//$output .='<div> ' .$cat. '</div>';
			
			
			
		}
		//$_SESSION['search'] = $output;
	}
}
?>

