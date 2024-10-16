<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the homepage or login page
header("Location: ../malefashion-master/login-male.php"); // Change the location as needed
exit;
?>