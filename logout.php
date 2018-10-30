<?php   
session_start(); //to ensure you are using same sessions
session_destroy(); //destroy the session

header("location:verify.php"); //to redirect back to "verify.php" after logging out
exit();
?>