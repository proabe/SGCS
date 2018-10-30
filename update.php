<?php include 'connection.php'; ?>
<?php
session_start();
if (isset($_SESSION['is_signed_in'])) {
	if ($_SESSION['is_signed_in'] === true) {
		$id = $_POST['data'];
		$state = $_POST['state'];
		$status = array();
		if ($state === 'Y' || $state === 'N') {
			$sql = "UPDATE giver_table SET status = '".$state."' WHERE id = ".$id;

			if ($conn->query($sql) === TRUE) {
				$status['status'] = "OK";
			} else {
			    $status['status'] = "NOT OK";
			}			
		}else{
			$status['status'] = "Somebody's messing with the parameters, I see you";
		}
		print_r(json_encode($status));
	}
}
?>
