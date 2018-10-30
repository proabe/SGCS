<?php
include "connection.php";
if(array_key_exists('fname_t', $_POST)){
	echo("Taker");
	$user_type = 1;
	$full_name = $_POST['fname_t'];
	$user_name = $_POST['user_t'];
	$user_key = $_POST['pass_t'];
	if (strlen($user_type) !== 0 && strlen($full_name) !== 0 && strlen($user_key) !== 0) {
		$sql = "INSERT INTO users(full_name,user,keypass,user_type) VALUES('$full_name','$user_name','$user_key','$user_type')";
		if ($conn->query($sql) === TRUE) {
			header("Location: ./verify.php");
	    	echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}	
	}
	
}
if (array_key_exists('fname_g', $_POST)) {
	echo("Giver");
	$user_type = 0;
	$full_name = $_POST['fname_g'];
	$user_name = $_POST['user_g'];
	$user_key = $_POST['pass_g'];
	if (strlen($user_type) !== 0 && strlen($full_name) !== 0 && strlen($user_key) !== 0) {
		$sql = "INSERT INTO users(full_name,user,keypass,user_type) VALUES('$full_name','$user_name','$user_key','$user_type')";
		if ($conn->query($sql) === TRUE) {
			header("Location: ./verify.php");
	    	echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

?>