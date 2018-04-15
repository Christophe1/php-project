<?php
    require('dbConnect.php');
	
	$ReviewID = $_POST['reviewiduser'];
	
    $ReviewID = explode(",",$ReviewID);
	
	//$ReviewID = '32,76';
   
   //$ReviewID = explode(",",$ReviewID);
	
	$results = array();
	
    foreach($ReviewID as $ReviewID) {
        $sql2 = "SELECT * FROM review WHERE review_id = ?";
        $stmt2 = $con->prepare($sql2) or die(mysqli_error($con));
        $stmt2->bind_param('i', $ReviewID) or die ("MySQLi-stmt binding failed ".$stmt2->error);
        $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);
        $result2 = $stmt2->get_result();
        
        while($row = mysqli_fetch_array($result2)) {//make an array called $results
            $results[] = array(
                'category' => $row['cat_name'],
                'name' => $row['name'],
                'phone' => $row['phone'],
                'comment' => $row['comment'],
                'reviewid' => $row['review_id'],
            );
        }
    }
   // $json = json_encode($results);
    //echo $json;
	
	    echo json_encode($results);

?>