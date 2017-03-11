<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'codexworld';
//connect with the database
$db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
//get search term
$searchTerm = $_GET['term'];
//get matched data from skills table
$query = $db->query("SELECT * FROM review WHERE cat_name LIKE '%".$searchTerm."%' ORDER BY cat_name ASC");
while ($row = $query->fetch_assoc()) {
    $data[] = $row['cat_name'];
}
//return json data
echo json_encode($data);
?>