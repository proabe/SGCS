<?php include 'connection.php'; ?>
<?php
session_start();
if (isset($_SESSION['is_signed_in'])) {
	if ($_SESSION['is_signed_in'] === true) {
		$user_name = $_POST['user']; /*change get to post later*/		
		$sql = "SELECT user_id,user_type FROM users WHERE user = '".$user_name."' LIMIT 1";
		$result=mysqli_query($conn,$sql);				
		$user_id = "";
		$user_type = "";
		while ($row=mysqli_fetch_assoc($result)) {			
			$user_id = $row['user_id'];
			$user_type = $row['user_type'];
		}
		$address = array();		
		if ($user_id !== "") {
			if ($user_type === '1') {
				$address['user_type'] = "taker";
				$address['yours'] = array();
				$address['theirs'] =array();
				$sql2 = "SELECT * FROM taker_table WHERE user_id = ".$user_id."";
				$result2=mysqli_query($conn,$sql2);			
				if ($result2->num_rows > 0) {
					$address['yours']['status'] = "1 OK";
					$count = 0;
					while ($row2 = mysqli_fetch_assoc($result2)) {
						$address['yours'][$count]['address'] = $row2['address'];
						$address['yours'][$count]['lat'] = $row2['lat'];
						$address['yours'][$count]['lng'] = $row2['lng'];
						$count++;
					}							
				}
				elseif($result2->num_rows === 0){
					$address['yours']['status'] = "NO DATA";
				}
				else{
					$address['yours']['status'] = "NOT OK";
				}
				/*---------------------------------------------------------------*/
				$sql3 = "SELECT * FROM giver_table WHERE status = 'Y'";
				$result3=mysqli_query($conn,$sql3);			
				if ($result3->num_rows > 0) {
					$address['theirs']['status'] = "2 OK";
					$count = 0;
					while ($row2 = mysqli_fetch_assoc($result3)) {
						$address['theirs'][$count]['address'] = $row2['address'];
						$address['theirs'][$count]['lat'] = $row2['lat'];
						$address['theirs'][$count]['lng'] = $row2['lng'];
						$count++;
					}							
				}
				elseif($result3->num_rows === 0){
					$address['theirs']['status'] = "NO DATA";
				}
				else{
					$address['theirs']['status'] = "NOT OK";
				}

			}


			if ($user_type === '0'){
				$address['user_type'] = "giver";
				$sql2 = "SELECT * FROM giver_table WHERE user_id = ".$user_id."";	
				$result2=mysqli_query($conn,$sql2);			
				if ($result2->num_rows > 0) {
					$address['status'] = "OK";
					$count = 0;
					while ($row2 = mysqli_fetch_assoc($result2)) {
						$address[$count]['Address'] = $row2['address'];
						$address[$count]['Latitude'] = $row2['lat'];
						$address[$count]['Longitude'] = $row2['lng'];
						$address[$count]['Code'] = $row2['id'];
						$address[$count]['Pick Status'] = $row2['status'];
						$count++;
					}
				}
				elseif($result2->num_rows === 0){
					$address['status'] = "NO DATA";
				}
				else{
					$address['status'] = "NOT OK";
				}				
			}
			
		}
		else{
			$address['status'] = "NO USER RETURNED";
		}
		print(json_encode($address));
	}	
}

?>
