<?php
	if (!empty($_GET)){	
		$user_id = $_GET['user_id'];
		$mode = $_GET['mode'];
	}
	include_once('includes/db_conn.php');
	
	$sql = mysql_query("select lat, lng from users where id = '$user_id'");
	while ($row = mysql_fetch_array($sql))
	{
		$lng = $row['lng'];					
		$lat = $row['lat'];
	}
?>

<html>
<head>
	<title>Geotagging | Afb Loan Management Portal&#x2122;</title>
	<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	<style>

		* {
			padding: 0;
			margin: 0;
		}

		a {
			text-decoration: none;
			color: #4F85C5;
		}

		body {
			color: #595350;
			line-height: 2em;
			background: #F5F5F5;
			font-size: 9pt;
		}

		body,input {
			font-family: 'Oxygen', sans-serif;
		}

		br.clearfix {
			clear: both;
		}

		strong {
			color: #000;
		}

		h1,h2,h3,h4 {
			font-weight: normal;
		}

		h2,h3,h4 {
			color: #4F85C5;
			font-family: 'Oxygen', sans-serif;
			margin-bottom: 0.01em;
		}

		h2 {
			font-size: 1.6em;
		}

		h3 {
			font-size: 1.2em;
		}

		h4 {
			font-size: 1.0em;
		}

		img.alignleft {
			margin: 5px 20px 20px 0;
			float: left;
		}
		img.alignright {
			margin: 5px 20px 20px 0;
			float: right;
		}

		img.aligntop {
			margin: 5px 0 20px 0;
		}

		p {
			margin-bottom: 0.75em;
		}

		ul {
			margin-bottom: 0.75em;
		}

		ul h4 {
			margin-bottom: 0.4em;
		}

		.post {
			margin: 0 0 40px 0;
		}

		#content {
			display:block;
			background: #FFF;
			width: 1100px;
			padding: 25px;

			float: left;
			box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.25);
		}
	
		#footer {
			text-align: center;
			padding: 0px 0 10px 0;
			color: #000;
			text-shadow: 1px 1px 0px rgba(255,255,255,0.75);
			font-size: 10pt;
		}

		#footer a {
			color: #02662E;
			text-decoration: none;
		}

		#header {
			padding: 0px 40px 0px 40px;
			height: 102px;
			position: relative;
			width: 1100px;
		}

		#logo {
			position: absolute;
			line-height: 40px;
			top: 0;
			left: 0px;
			height: 45px;
			text-shadow: 1px 1px 0px rgba(255,255,255,0.9);
		}

		#logo a {
			text-decoration: none;
			color: #696969;
		}

		#logo h1 {
			font-family: 'Oxygen', sans-serif;
			font-size: 2em;
		}

		#menu{
			display:block;
			float:left;
			width: 1145px;
			margin: 0 0 0 0;
			padding: 0 5px 0 0px;
			list-style:none;
			bottom: 0;
			font-family: 'Oxygen', sans-serif;
			line-height: 45px;
			left: 0;
			position: absolute;
			height: 45px;
			background: #66B845;
			box-shadow: inset 0px 0px 5px 0px rgba(0,0,0,0.25), 0px 0px 6px 0px rgba(255,255,255,0.9);
			/*ext-shadow: 1px 1px 0px rgba(0,0,0,0.6);*/
			}

		#menu ul, #menu li{
			float:left;
			list-style:none;
			margin:0;
			padding:0;
			}

		#menu li a:link, #menu li a:visited, #menu li a:hover{
			display:block;
			margin:0 5px 0 0;
			padding:0 20px;
			font-size:15px;
			color:#FFFFFF;
			text-decoration: none;
			background: #66B845;
			}

		#menu ul ul li a:link, #menu ul ul li a:visited{
			border:none;
			}

		#menu li.last a{
			margin-right:0;
			}

		#menu li a:hover, #menu ul li.active a{
			color:#FFFFFF;
			background-color:#4F85C5;
			}
	
		#menu li li a:link, #menu li li a:visited{
			width:165px;
			float:none;
			margin:0;
			padding:0px 5px;
			font-size:12px;
			text-decoration: none;
			color:#FFFFFF;
			background-color:#66B845;
			}
	
		#menu li li a:hover{
			color:#FFFFFF;
			background-color:#4F85C5;
			}

		#menu li ul{
			z-index:9999;
			position:absolute;
			left:-999em;
			height:auto;
			width:170px;
			border-left:1px solid #059BD8;
			border-bottom:1px solid #059BD8;
			border-right:1px solid #059BD8;
			}

		#menu li ul a{width:140px;}

		#menu li ul ul{margin:-32px 0 0 0;}

		#menu li:hover ul ul{left:-999em;}

		#menu li:hover ul, #menu li li:hover ul{left:auto;}

		#menu li:hover{position:static;}

		#menu li.last a{margin-right:0;}

		#page {
			width: 1100px;
			padding: 0;
			margin: 10px 0 10px 0;
			position: relative;
		}

		#page .section-list {
			list-style: none;
			padding-left: 0;
		}

		#page .section-list li {
			clear: both;
			padding: 25px 0 25px 0;
		}

		#page ul {
	
		}

		#page ul li {
			padding: 5px 10px 5px 10px;
		}

		#page ul li.first {
			padding-top: 0;
			border-top: 0;
		}

		#col1 {
			float: left;
			width: 330px;
			overflow: hidden;
		}

		#col2 {
			width: 330px;
			overflow: hidden;
			margin: 0 0 0 365px;
		}

		#search {
			line-height: 125px;
			top: 0;
			position: absolute;
			right: 0;
			height: 125px;
		}

		#search input.form-submit {
			margin-left: 1em;
			background: #336784 url(../images/bg2.jpg);
			font-family: Kreon, sans-serif;
			border: 0;
			color: #FFF;
			padding: 7px;
			text-shadow: 1px 1px 0px rgba(0,0,0,0.5);
			box-shadow: inset 0px 0px 5px 0px rgba(0,0,0,0.25), 0px 0px 6px 0px rgba(255,255,255,0.9);
		}

		#search input.form-text {
			padding: 8px;
			border: 0;
			background: rgba(62,59,57,0.5);
			box-shadow: inset 0px 0px 5px 0px rgba(0,0,0,0.5), 0px 0px 6px 0px rgba(255,255,255,0.9);
			color: #fff;
			width: 290px;
		}

		#splash {
			padding: 40px;
			position: relative;
			background: #FFF;
			margin: 20px 0 0 0;
			height: 250px;
			width: 1100px;
			box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.25);
		}

		#wrapper {
			margin: 10px 200px 0 auto;
			width: 1000px;
			position: relative;
		}

		/*Graphs */

		.awesome .graph-header { width: 250px; }
		.sales   .graph-header { width: 250px; }

		.sales { margin-left: 20px; }
		li { margin-bottom: 3px; }

		.graph {
			  margin-bottom: 50px;
			  margin-top: 30px;
			  clear: both;
			  font-family: 'Oxygen', sans-serif;
			  font-size: 13px;
		}

		.graph .label {
		  text-align: center;
		  padding-top: 5px;
		  padding-bottom: 5px;
		}
		.graph-header h5 {
		  margin-bottom: 0px;
		}

		.graph-header {
		  text-align: right;
		}

		.graph-header p {
		  margin-top: 0px;
		}

		.graph .legend td, .graph .legend, .graph .legend tr {
		  padding: 0px;
		  margin: 0px;
		  border-collapse:collapse;
		}

		.graph .legend {
		  margin-left: 10px;
		}

		.graph .legend td {
		  padding-right: 5px;
		}

		.graph .legend .color-box {
		  width: 14px;
		  height: 10px;
		  overflow: hidden;
		}

		form, fieldset, legend{
			margin:0;
			padding:0;
			border:none;
			}

		legend{
			display:none;
			}

		input, textarea, select{
			font-size:14px;
			font-family: 'Oxygen', sans-serif;
		}
	
		#content input{
			width:250px;
			padding:2px;
			border:1px solid #CCCCCC;
			margin:5px 5px 0 0;
			}

		#content #submit.input2{
			width:450px;
			margin:5px;
			padding:5px;
			color:#FFFFFF;
			background-color:#66B845;
			border:1px solid #CCCCCC;
			cursor:pointer;
			}
	
		img { border: none; }
		img.alignleft {
			margin: 5px 20px 20px 0;
			float: left;
		}
		img.alignright {
			margin: 5px 20px 20px 0;
			float: right;
		}
		input, select, textarea, th, td { font-size: 1em; }
	</style>
</head>
	<body onLoad="initialize()">
		<div id="map_canvas" style="width:90%; height:70%"></div>
		<div id="latlong">
			<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id	 ?>" />	
					<tr>
						<td valign='top' width="15%">Latitude *</td>
						<td valign='top' width="35%">
							<input title="Enter Repyment Mobile" value="<?php echo $loan_rep_mobile ?>" id="latbox" name="lat" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign="top" width="15%">Longitude *</td>
						<td valign="top" width="35%">
							<input title="Enter Repayment Amount" value="<?php echo $loan_rep_amount ?>" id="lngbox" name="lng" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<button name="btnNewCard" id="button">Save</button>
						</td>
						<td align="right">
							<button name="reset" id="button2" type="reset">Reset</button>
						</td>		
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>

<cfoutput>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=#YOUR-GOOGLE-API-KEY#&sensor=false"></script>
</cfoutput>

<script type="text/javascript">
//<![CDATA[

    // global "map" variable
    var map = null;
    var marker = null;

    // popup window for pin, if in use
    var infowindow = new google.maps.InfoWindow({ 
        size: new google.maps.Size(150,50)
        });

    // A function to create the marker and set up the event window function 
    function createMarker(latlng, name, html) {

    var contentString = html;

    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        });

    google.maps.event.trigger(marker, 'click');    
    return marker;

}

function initialize() {

    // the location of the initial pin
    
    <?php
    	if($lng != "" || $lat != ""){
    	?>
    		var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
    	<?php
    	}
    	else{
		?>
    		var myLatlng = new google.maps.LatLng(-1.290245, 36.779415);
		<?php
    	}
    ?>

    // create the map
    var myOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeControl: true,
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    // establish the initial marker/pin
    var image = 'images/delete.png';  
    marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      icon: image,
      title:"Property Location"
    });

    // establish the initial div form fields
    formlat = document.getElementById("latbox").value = myLatlng.lat();
    formlng = document.getElementById("lngbox").value = myLatlng.lng();

    // close popup window
    google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
        });

    // removing old markers/pins
    google.maps.event.addListener(map, 'click', function(event) {
        //call function to create marker
         if (marker) {
            marker.setMap(null);
            marker = null;
         }

        // Information for popup window if you so chose to have one
        /*
         marker = createMarker(event.latLng, "name", "<b>Location</b><br>"+event.latLng);
        */

        var image = 'images/delete.png';
        var myLatLng = event.latLng ;
        /*  
        var marker = new google.maps.Marker({
            by removing the 'var' subsquent pin placement removes the old pin icon
        */
        marker = new google.maps.Marker({   
            position: myLatLng,
            map: map,
            icon: image,
            title:"Property Location"
        });

        // populate the form fields with lat & lng 
        formlat = document.getElementById("latbox").value = event.latLng.lat();
        formlng = document.getElementById("lngbox").value = event.latLng.lng();

    });

}
//]]>

</script> 
<?php
	if (!empty($_POST)) {
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		
		$user_id = $_POST['user_id'];
		$page_status = $_POST['page_status'];
		
		//if($page_status == 'edit'){
			$sql3="update users set lat='$lat', lng='$lng' WHERE id  = '$user_id'";
			
			//echo $sql3."<br />";
			$result = mysql_query($sql3);
		//}
		
		$query = "customers.php";
		?>
		<script type="text/javascript">
			<!--
			/*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
			document.location = "<?php echo $query ?>";
			//-->
		</script>
		<?php	

	}
?>
