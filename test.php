<?php

require('dbConnect.php');


$json = '[{"phone_number":"12345","name":"Bob"},{"phone_number":"67890","name":"Sally"},{"phone_number":"11223344","name":"Jim"},{"phone_number":"987654","name":"Marge"}]';

//decode the JSON into PHP language, will look something like ["phone_number"] => "12345" etc...
$the_result = json_decode($json, true);

var_dump($the_result);

		
?>

