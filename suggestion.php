<?php 


require('dbConnect.php');

//this is the review_id of the ViewContact class, received from the PopulistoListView class
/$name = $_REQUEST['name'];
//$name = $_POST['name'];
//$name = "to";
//$name = $string . '%%';

// The ? below are parameter markers used for variable binding
// auto increment does not need prepared statements

// get the review_id in the review table, then get the matching fields in the row 
				//$query = "SELECT cat_id, cat_name FROM category WHERE cat_name like ?";
				//$query = "SELECT cat_id, cat_name FROM category WHERE cat_name like '%".$name."%'";
				//$query = "SELECT * FROM category WHERE cat_name like '$name%'";
				//$query = "SELECT * FROM category WHERE cat_name = ?";
				//$stmt = $con->prepare($query) or die(mysqli_error($con));
				//$stmt->bind_param('s', $name) or die ("MySQLi-stmt binding failed ".$stmt->error);
				//$stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
			    //$result = $stmt->get_result();
				
				//$sql = "SELECT cat_id, cat_name FROM category WHERE cat_name like like '$name%%'";
				$sql = "SELECT cat_id, cat_name FROM category WHERE cat_name like '%".$name."%'";

				$result = mysqli_query($con,$sql);

				$rows = array();
				
				while($row = mysqli_fetch_assoc($result)) {
					
					
			    $rows['results'][] = $row;
					
				}
				

/* $data = '{"results":[{"id":"1","name":"ab"},{"id":"2","name":"abc"},{"id":"3","name":"bc"},{"id":"4","name":"bcd"},{"id":"5","name":"cd"},{"id":"6","name":"cde"},{"id":"7","name":"ef"},{"id":"8","name":"efg"},{"id":"9","name":"hi"},{"id":"10","name":"hig"},{"id":"11","name":"jk"},{"id":"12","name":"jkl"},{"id":"13","name":"mn"},{"id":"14","name":"mno"},{"id":"15","name":"pq"},{"id":"16","name":"pqr"},{"id":"17","name":"st"},{"id":"18","name":"stu"},{"id":"19","name":"vw"},{"id":"20","name":"vwx"},{"id":"21","name":"yz"},{"id":"22","name":"yza"}]}'; */


  echo json_encode($rows);
  
 // $stmt->close();
  
?>