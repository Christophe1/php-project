<?php
session_start();
//review = $_SESSION['review'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
session_destroy();

?>