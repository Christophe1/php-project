<?php
$fromPost = $_POST['contact'];
$object = json_decode($fromPost, true); // Read the doc to decide whether you want the "true" or not
var_dump($object);

?>
