<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Branch Customer Mapping";
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
		    //<![CDATA[

		    var customIcons = {
		      restaurant: {
		    	icon: 'https://labs.google.com/ridefinder/images/mm_20_blue.png',
		    	shadow: 'https://labs.google.com/ridefinder/images/mm_20_shadow.png'
		      },
		      bar: {
			icon: 'https://labs.google.com/ridefinder/images/mm_20_red.png',
			//icon: 'https://simpleicon.com/wp-content/uploads/map-marker-13.png',
			shadow: 'https://labs.google.com/ridefinder/images/mm_20_shadow.png'
			
		      }
		   }


		    function load() {
		      var map = new google.maps.Map(document.getElementById("map"), {
			center: new google.maps.LatLng(-1.29206, 36.82194),
			zoom: 11,
			mapTypeId: 'roadmap'
		      });
		      var infoWindow = new google.maps.InfoWindow;

		      // Change this depending on the name of your PHP file
		      downloadUrl("includes/phpsqlajax_genxml4.php", function(data) {
			var xml = data.responseXML;
			var markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
			  var name = markers[i].getAttribute("first_name");
			  var address = markers[i].getAttribute("mobile_no");
			  var type = markers[i].getAttribute("stations");
			  var point = new google.maps.LatLng(
			      parseFloat(markers[i].getAttribute("lat")),
			      parseFloat(markers[i].getAttribute("lng")));
			  var html = "<b>Name: " + name + "</b> <br/> Mobile: " + address + " " + type + " Branch";
			  var icon = customIcons[type] || {};
			  var marker = new google.maps.Marker({
			    map: map,
			    position: point,
			    icon: icon.icon,
			    shadow: icon.shadow
			  });
			  bindInfoWindow(marker, map, infoWindow, html);
			}
		      });
		    }

		    function bindInfoWindow(marker, map, infoWindow, html) {
		      google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		      });
		    }

		    function downloadUrl(url, callback) {
		      var request = window.ActiveXObject ?
			  new ActiveXObject('Microsoft.XMLhttps') :
			  new XMLhttpsRequest;

		      request.onreadystatechange = function() {
			if (request.readyState == 4) {
			  request.onreadystatechange = doNothing;
			  callback(request, request.status);
			}
		      };

		      request.open('GET', url, true);
		      request.send(null);
		    }

		    function doNothing() {}

		    //]]>
		  </script>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
						<br />
						<div id="map" style="width: 1100px; height: 750px"></div>
						<br />
						<h2>Data Table</h2>
						<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Phone Number</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Branch</th>
							</tr>
						</thead>
						<tbody>
						<?php
						 $sql = mysql_query("select distinct lat, first_name, last_name, mobile_no, lng, stations.stations from users inner join stations on stations.id = users.stations where lat != '' order by first_name asc");
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$id = $row['id'];
							$first_name = $row['first_name'];
							$last_name = $row['last_name'];
							$first_name = ucwords(strtolower($first_name));
							$last_name = ucwords(strtolower($last_name));
							$customer_name = $first_name.' '.$last_name;
							$mobile_no = $row['mobile_no'];
							$lat = $row['lat'];
							$lng = $row['lng'];
							$stations = $row['stations'];
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$mobile_no</td>";
							echo "<td valign='top'>$lat</td>";
							echo "<td valign='top'>$lng</td>";
							echo "<td valign='top'>$stations</td>";
							echo "</tr>";	
						}
						?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Phone Number</th>
								<th>Latitude</th>
								<th>Longitude</th>
								<th>Branch</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
