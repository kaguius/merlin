<?php
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
	}
	require("phpsqlajax_dbinfo.php");

	function parseToXML($htmlStr) 
	{ 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&#39;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr); 
		return $xmlStr; 
	} 

	// Opens a connection to a MySQL server
	$connection=mysql_connect (localhost, $username, $password);
	if (!$connection) {
		die('Not connected : ' . mysql_error());
	}

	// Set the active MySQL database
	$db_selected = mysql_select_db($database, $connection);
	if (!$db_selected) {
		die ('Can\'t use db : ' . mysql_error());
	}

	// Select all the rows in the markers table
	$query = "select first_name, last_name, mobile_no, purchase, current_loan, description, branch, lat, lng from unilever_geo_tag order by first_name asc";
	$result = mysql_query($query);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	header("Content-type: text/xml");

	// Start XML file, echo parent node
	echo '<markers>';

	// Iterate through the rows, printing XML nodes for each
	while ($row = @mysql_fetch_assoc($result)){
	  // ADD TO XML DOCUMENT NODE
		echo '<marker ';
		echo 'first_name="' . parseToXML($row['first_name']) . '" ';
		echo 'mobile_no="' . parseToXML($row['mobile_no']) . '" ';
		echo 'branch="' . parseToXML($row['branch']) . '" ';
		echo 'lat="' . $row['lat'] . '" ';
		echo 'lng="' . $row['lng'] . '" ';
		echo '/>';
	}

	// End XML file
	echo '</markers>';

?>

