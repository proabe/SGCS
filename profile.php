<?php include 'connection.php'; ?>
<?php
session_start();
if (isset($_SESSION['is_signed_in']) && $_SESSION['is_signed_in'] !== true) {
	header("Location: ./verify.php");
}
if ( isset($_POST['addbtn']) ) {
	$addr_1 = trim($_POST['addr_1']);
	$addr_2 = trim($_POST['addr_2']);
	$l_mark = trim($_POST['l_mark']);
	$city = trim($_POST['city']);
	$state = trim($_POST['state']);
	$pincode = trim($_POST['pin']);
	$address = "";
	if ($addr_1 !== '') {
		$address .= $addr_1.","; 
	}
	if ($addr_2 !== '') {
		$address .= $addr_2.","; 
	}
	if ($l_mark !== '') {
		$address .= $l_mark.","; 
	}
	if ($city !== '') {
		$address .= $city.","; 
	}
	if ($state !== '') {
		$address .= $state.","; 
	}
	if ($pincode !== '') {
		$address .= $pincode.","; 
	}
	if ($address !== '') {
		$address = rtrim($address,",");
	}
	$user_name = $_SESSION['user'];
	$sql = "SELECT user_id,user_type FROM users WHERE user = '".$user_name."' LIMIT 1";
	$result=mysqli_query($conn,$sql);				
	$user_id = "";
	$user_type = "";
	while ($row=mysqli_fetch_assoc($result)) {
		$user_id = $row['user_id'];
		$user_type = $row['user_type'];
	}
	if ($address !== "" && $user_id !== "") {
		$url = "https://api.tomtom.com/search/2/geocode/".rawurlencode($address).".json?countrySet=IN&view=IN&key=Enter_Your_Key";

		$j = file_get_contents($url);
		if ($j != false) {
			$json = json_decode($j,TRUE);
			$lat=$json['results'][0]['position']['lat'];
			$lng=$json['results'][0]['position']['lon'];
			if ($user_id !== "") {
				if ($user_type === '1') {
					$sql2 = "INSERT INTO taker_table (user_id,address,lat,lng) VALUES('$user_id','$address','$lat','$lng')";
					if (!mysqli_query($conn,$sql2)) {
						echo "Nai hua";
					}
					else{
						echo "badhai ho";
					}
				}
				if ($user_type === '0'){
					$sql2 = "INSERT INTO giver_table (user_id,address,lat,lng,status) VALUES('$user_id','$address','$lat','$lng','N')";
					if (!mysqli_query($conn,$sql2)) {
						echo "Nai hua";
					}
					else{
						echo "badhai ho";
					}
				}
			}	
		}	
	}
		
} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
       <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>
   <link href="pro_main.css" rel="stylesheet">
    <style type="text/css">
	    body{
				/*height: 100%;
			    margin: 0;
			    width: 100%;
			    background: url(images/road.jpg); 
			    background-repeat: no-repeat;
			    background-size: cover;
			    background-position: center center;
			    background-attachment: fixed;*/
			    font-family: sans-serif;
		}
		
    	
    </style>
	<script type="text/javascript" charset="utf-8">
		document.addEventListener("DOMContentLoaded",function(){
			var map = L.map('mapid').setView([24.112349, 77.967741], 4);
			L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
			    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			    maxZoom: 18,
			    id: 'mapbox.streets',
			    accessToken: 'pk.eyJ1IjoiaGFycnlwb3R0ZXJ3aXphcmRvZmhvZ3dhcnR6IiwiYSI6ImNqOXA0ajFreDJ0bWwzMm12MThnMGRnOTQifQ.ujOZQsf0_kFYBpjKnRKAHw'
			}).addTo(map);

			$.ajax({
			url: 'user_feed.php',
			type: 'POST',
			data: {user: '<?php echo($_SESSION['user']) ?>'},
			success : function(result){
						r = JSON.parse(result);
						create_data(r);
					}
			});

			function scroll_down(){
				$("html, body").animate({ scrollTop: document.body.scrollHeight }, "slow");
			}

			function set_it(dta,state){
				$.ajax({
				url: 'update.php',
				type: 'POST',
				data: {data: dta,state:state}/*,
				success : function(result){
							r = JSON.parse(result);
						}*/
				});
			}

			function route_it(starting,ending,r_type){
				$.ajax({
				url: 'route.php',
				type: 'POST',
				data: {sp: starting,ep:ending,type:r_type},
				success : function(result2){
							r2 = JSON.parse(result2);
							map_it(r2,starting,ending);							
						}
				});
			}

			function remove_feature(){				
		        map.eachLayer(function(layer) {
		          if (layer instanceof L.GeoJSON){
		            map.removeLayer(layer);
		          }
		          if (layer instanceof L.marker){
		            map.removeLayer(layer);
		          }          
		        });				
			}

			function create_marker(lt,lg,pop){
				remove_feature();
				if (lt !== '' && lg !== '') {
					L.marker([lt,lg]).addTo(map).bindPopup(pop);
				}
			}

			function map_it(res_obj,sp,ep){
				remove_feature();
				console.log(res_obj);
				console.log(res_obj['popupcontent']);
				var geojson = res_obj['geojson'];
				var popupcon = res_obj['popupcontent'];


				var myStyle = {
				    "color": "#ff7800",
				    "weight": 5,
				    "opacity": 0.65
				};

				L.geoJSON(geojson,{style: myStyle}).addTo(map).bindPopup(popupcon);
				var coor1 = sp.split(",");
				var coor2 = ep.split(",");
				var s_lat = coor1[0];
				var s_lng = coor1[1];
				var e_lat = coor2[0];
				var e_lng = coor2[1];
				L.marker([s_lat,s_lng]).addTo(map).bindPopup("Start Point");
				L.marker([e_lat,e_lng]).addTo(map).bindPopup("End Point");
				map.flyTo([s_lat,s_lng],12);
			}

			$('#m_addresses').on("change","input[type='checkbox']",function() {	
				var dd = $(this).attr('s_data');
			    if(this.checked) {
			        console.log("checked");
			        var st = "Y";
			        set_it(dd,st);
			    }
			    else{
			    	console.log("not checked");
			    	var st = "N";
			    	set_it(dd,st);
			    }
			});

			$('body').on("click","button",function(){
				var u_type = $(this).attr('u_type');				
				var lat_lng = $(this).attr('latlng');
				if(u_type === 'yours'){
					$("input[name='taker_lat_lng']").val(lat_lng);
				}
				if(u_type === 'theirs'){
					$("input[name='giver_lat_lng']").val(lat_lng);
				}
			});

			$("#route_submit").click(function(){
				var start = $("input[name='taker_lat_lng']").val();
				var end = $("input[name='giver_lat_lng']").val();
				var route_type = $('select').find(":selected").val();				
				if (start.length !== 0 && end.length !== 0  && route_type.length !== 0) {
					route_it(start,end,route_type);
				}
				scroll_down();
			});

			function create_data(data){
				console.log(data);
				var type = data['user_type'];
				if (type === "taker") {
					$('#router').css("display","block");
					var numb = Object.keys(data['yours']).length - 1;
					var m_add = document.getElementById("m_addresses");
					if ( numb !== 0) {
						var h4 = document.createElement("h4");
						h4.textContent = "Your Saved Addresses";
						m_add.appendChild(h4);
						for (var i = 0; i < numb; i++) {
							var div = document.createElement("div");
							var p = document.createElement("p");
							var btn = document.createElement("button");
							btn.setAttribute('latlng',data['yours'][i]['lat']+","+data['yours'][i]['lng']);
							btn.setAttribute('u_type',"yours");
							btn.setAttribute('type',"button");
							btn.innerHTML="Select Address";
							p.textContent = data['yours'][i]['address'];
							p.setAttribute('latlng',data['yours'][i]['lat']+","+data['yours'][i]['lng']);
							div.appendChild(p);
							div.appendChild(btn);
							m_add.appendChild(div);
						}
					}

					/*-------------------------------------------------------*/
					var numb1 = Object.keys(data['theirs']).length - 1;
					var t_add = document.getElementById("t_addresses");
					if ( numb1 !== 0) {
						var h4 = document.createElement("h4");
						h4.textContent = "Their Saved Addresses";
						t_add.appendChild(h4);
						for (var i = 0; i < numb1; i++) {
							var div = document.createElement("div");
							var p = document.createElement("p");
							var btn = document.createElement("button");
							btn.setAttribute('latlng',data['theirs'][i]['lat']+","+data['theirs'][i]['lng']);
							btn.setAttribute('u_type',"theirs");
							btn.setAttribute('type',"button");
							btn.innerHTML="Select Address";
							p.textContent = data['theirs'][i]['address'];
							p.setAttribute('latlng',data['theirs'][i]['lat']+","+data['theirs'][i]['lng']);
							div.appendChild(p);
							div.appendChild(btn);
							t_add.appendChild(div);
						}
					}										
				}
				/*=======================================================*/
				if (type === "giver") {
					$('#t_addresses').css("display","none");
					var numb = Object.keys(data).length - 2;
					var m_add = document.getElementById("m_addresses");
					if ( numb !== 0) {
						var h4 = document.createElement("h4");
						h4.textContent = "Your Saved Addresses";
						m_add.appendChild(h4);
						for (var i = 0; i < numb; i++) {
							if (data[i]['Latitude'] !== '' && data[i]['Longitude'] !== '') {
								create_marker(data[i]['Latitude'],data[i]['Longitude'],data[i]['Address']);
							}
							var div = document.createElement("div");
							var p = document.createElement("p");
							var label = document.createElement("label");
							label.setAttribute("class","switch");
							var input = document.createElement("input");
							input.setAttribute("type","checkbox");
							input.setAttribute("s_data",data[i]['Code']);
							if (data[i]['Pick Status'] == 'Y') {
								input.checked = true;
							}
							var span = document.createElement("span");
							span.setAttribute("class","slider round");
							label.appendChild(input);
							label.appendChild(span);

							p.textContent = data[i]['Address'];
							p.setAttribute('latlng',data[i]['Latitude']+","+data[i]['Longitude']);	
							div.appendChild(p);
							div.appendChild(label);
							m_add.appendChild(div);
						}
					}
					/*-------------------------------------------------------*/
															
				}
			}
			var cross = document.getElementById("crosscon");
	        var count=1;
	        cross.addEventListener("click",function(){
	           if (count%2==0) {
	            $("#cross").removeClass("crossed");
	            $(".btncon1").animate({top: '-4px'}); 
	           }
	           else{
	            $("#cross").addClass("crossed");            
	            $(".btncon1").animate({top: '32px'}); 
	           } 
	           
	           count++;
	        });
	        document.getElementById("logout").addEventListener("click",function(){
           		window.location.assign("logout.php");
        	});
		});		
		
	</script>
</head>
<body>
	<div id="header">
        <span id="cross">
        	<i class="material-icons md-18" id="crosscon">close</i>
        </span> 
    </div>
    <div class="btncon1">
             <button id="logout">Logout</button>
    </div>
	<div id="add_address" class="col-lg-8 col-md-6 col-sm-4 col-xs-4">
    	<div><h3>Add Address</h3>
    		<form action="" method="POST">            	
		    	<label>
		    		<i class="material-icons md-18">location_city</i>
		    		<input class="in" type="text" name="addr_1" placeholder="Address Line 1">
		    	</label>
		    	<label>
		    		<i class="material-icons md-18">location_city</i>
		    		<input class="in" type="text" name="addr_2" placeholder="Address Line 2">
		    	</label>
		    	<label>
		    		<i class="material-icons md-18">location_city</i>
		    		<input class="in" type="text" name="l_mark" placeholder="Landmark">
		    	</label>
		    	<label>
		    		<i class="material-icons md-18">location_city</i>
		    		<input class="in" type="text" name="city" placeholder="City / Town">
		    	</label>
		    	<label>
		    		<i class="material-icons md-18">location_city</i>
		    		<input class="in" type="text" name="state" placeholder="State">
		    	</label>
		    	<label>
		    		<i class="material-icons md-18">pin_drop</i>
		    		<input class="in" type="text" name="pin" placeholder="Pincode">
		    	</label>            	
		    	<input type="submit" value="+" id="sub3" class="sub" name="addbtn">
	    	</form>
    	</div>
	</div>
	<div class="main_con col-lg-8 col-md-6 col-sm-4 col-xs-4">
		<div id="m_addresses"></div>
		<div id="t_addresses"></div>
		<div id="router">
			<input type="text" name="taker_lat_lng" placeholder="Taker Location">
			<input type="text" name="giver_lat_lng" placeholder="Giver Location">
			<select>
				<option value="fastest">Fastest Route</option>				
				<option value="shortest">Shortest Route</option>
				<option value="eco">Economical Route</option>
			</select>
			<input type="submit" value=">" id="route_submit" name="addbtn">
		</div>
	</div>
	<div id="mapid"></div>
</body>
</html>