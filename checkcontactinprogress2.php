<?php

    require('dbConnect.php');

    //this is the username in the user table
    $Number = "+353872934480";

    // get the username of the user in the user table, then get the matching user_id in the user table
                    // so we can check contacts against it 
                    $query = "SELECT * FROM user WHERE username = ?";
                    $stmt = $con->prepare($query) or die(mysqli_error($con));
                    $stmt->bind_param('s', $Number) or die ("MySQLi-stmt binding failed ".$stmt->error);
                    $stmt->execute() or die ("MySQLi-stmt execute failed ".$stmt->error);
                    $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {

                //this is the corresponding user_id in the user table of the user
                $user_id = $row["user_id"];
                }

    //post all contacts for user_id as a JSON array
    $phonenumberofcontact ='["+11111","+222222","12345","67890","123456","78901","234567","8901234"]';
    $array = json_decode($phonenumberofcontact);

    //We want to check if contacts of user_id are also users of the app. 
     $query = "SELECT * FROM user WHERE username = ?";
     $stmt2 = $con->prepare($query) or die(mysqli_error($con));
     $stmt2->bind_param('s', $phonenumberofcontact) or die ("MySQLi-stmt binding failed ".$stmt->error);

     //for each value, call it $phonenumberofcontact
$i = 0;
$tempArray = array();
        foreach ($array as $value)
        {
                    $phonenumberofcontact = $value;

    $stmt2->execute() or die ("MySQLi-stmt execute failed ".$stmt2->error);

    //match the phone numbers in the JSON Array against those in the user table
         $result2 = $stmt2->get_result(); 

                while ($row = $result2->fetch_assoc()) {

                //make an array called $results
                //this is a matching number in user table and in the JSON Array
                //call this username contact_phonenumber
        $results = array();

                        $results[] = array(
             'contact_phonenumber' => $row['username'], 
             );


               $tempArray[$i] = $results;
    $i++;
        }
$json2 = json_encode($results); 
echo $json2;
        }

            ?>



		
	