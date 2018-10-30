<?php
	include "connection.php";
?>
<!DOCTYPE html>
<html>
<head>	
	<title>Sign In</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<link href="main.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<style type="text/css" media="screen">
		html{
			height: unset;
		}
		body{
			height: 100%;
		    margin: 0;
		    width: 100%;
		    background: url(images/sign-in.jpg); 
		    background-repeat: no-repeat;
		    background-size: cover;
		    background-position: center center;
		    background-attachment: fixed;
		}
	</style>
</head>
<body>	
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 con2">
    	<div class="head"><p>Sign In</p></div>
    	<form action="" method="POST">            	
    	<label>
    		<i class="material-icons md-18">person</i>
    		<input class="in" type="text" name="user_t" placeholder="Username">
    	</label>
    	<label>
    		<i class="material-icons md-18">vpn_key</i>
    		<input class="in" type="password" name="pass_t" placeholder="Password">
    	</label>            	
    	<input type="submit" value="Sign In" id="sub3" class="sub" name="subbtn">
    	</form>
	</div>
</body>
</html>
<?php
session_start();
if (isset($_POST['subbtn']) && $_POST['user_t']!== '' && $_POST['pass_t']!== ''){	
	$user = $_POST['user_t'];
	$pass = $_POST['pass_t'];
	$sql = "SELECT user,keypass FROM users WHERE user = '".$user."' LIMIT 1";
	
	$result=mysqli_query($conn,$sql);

	while ($row=mysqli_fetch_assoc($result)) {
	    $check_user = $row['user'];
	    $check_pass = $row['keypass'];
	}
	if ($user === $check_user && $pass == $check_pass) {		
		$_SESSION['is_signed_in'] = true;
		$_SESSION['user'] = $check_user;
		header("Location: ./profile.php");
	}
	else{		
		$_SESSION['is_signed_in'] = false;		
		header("Location: ./verify.php");
	}
}
?>