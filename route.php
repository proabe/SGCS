<?php include 'connection.php'; ?>
<?php
session_start();
if (isset($_SESSION['is_signed_in'])) {
	if ($_SESSION['is_signed_in'] === true) {
		$start_point = trim($_POST['sp']);/*GET is for R&D change it to POST later*/
		$end_point = trim($_POST['ep']);/*GET is for R&D change it to POST later*/
		$type = trim($_POST['type']);
		$data =array();
		if ($start_point !== "" and $end_point !== "") {
			if ($type === 'eco') {
				$url = "https://api.tomtom.com/routing/1/calculateRoute/".$start_point.":".$end_point."/json?routeType=eco&avoid=unpavedRoads&key=Enter_Your_Key";
			}
			if ($type === 'fastest') {
				$url = "https://api.tomtom.com/routing/1/calculateRoute/".$start_point.":".$end_point."/json?routeType=fastest&avoid=unpavedRoads&key=Enter_Your_Key";
			}
			if ($type === 'shortest') {
				$url = "https://api.tomtom.com/routing/1/calculateRoute/".$start_point.":".$end_point."/json?routeType=shortest&avoid=unpavedRoads&key=Enter_Your_Key";
			}
			/*echo $url;*/
			$j = file_get_contents($url);
			$json = json_decode($j,TRUE);
			$content = "<p>Travel Mode: ".$type."<br>Distance (in Kms): ".($json['routes'][0]['summary']['lengthInMeters']/1000)."<br>Travel Time (in minutes): ".($json['routes'][0]['summary']['travelTimeInSeconds']/60)."<br>Traffic Delay (in seconds): ".$json['routes'][0]['summary']['trafficDelayInSeconds']."</p>";
			$feature_collection = array();
			$feature_collection['type']="Feature";
			$feature_collection['geometry']=array();
			$feature_collection['geometry']['type']="MultiLineString";
			$feature_collection['geometry']['coordinates']=array();
			for ($i=0; $i < count($json['routes'][0]['legs']); $i++) { 
				$feature_collection['geometry']['coordinates'][$i]=array();
				foreach ($json['routes'][0]['legs'][$i]['points'] as $value) {
					array_push($feature_collection['geometry']['coordinates'][$i], array($value['longitude'],$value['latitude']));
				}
			}
			$data['popupcontent'] = $content;
			$data['geojson'] = $feature_collection;
			print_r(json_encode($data));

		}
	}
}
?>
