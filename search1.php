


<?php

require('dbConnect.php');
/* $dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'codexworld';
//connect with the database
$db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName); */
//get search term
$searchTerm = $_GET['term'];

//get info from all cells in the row where review_id is equal to the review selected. The name, address etc...
//$sql = "SELECT * FROM review WHERE review_id = " .$_GET['id'];
$sql = "SELECT * FROM category WHERE cat_name LIKE '%".$searchTerm."%' ORDER BY cat_name ASC";

//get the result of the above
$result = mysqli_query($con, $sql);

//get matched data from skills table
//$query = $db->query("SELECT * FROM review WHERE cat_name LIKE '%".$searchTerm."%' ORDER BY skill ASC");
while ($row = $result->fetch_assoc()) {
    $data[] = $row['cat_name'];
}
//return json data
echo json_encode($data);
?>