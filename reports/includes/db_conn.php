<?php
	
	$msg_username = 'afbloans';
	$msg_password = 'xlbCN1jq';
	$msg_sender = 'AFBLOANS';
	
	$host_server='127.0.0.1';
	$db_server='merlin';
	$user_server='root';
	$pwd_server='root';
	$port = 8889;
	
	//$dbh1 = mysql_connect("$host:$port", $host_server, $user_server, $pwd_server); 	
	//mysql_select_db($db_server, $dbh1);
	
	$dbh1 = mysql_connect(
	   "$host:$port", 
	   $user_server, 
	   $pwd_server
	);
	$db_selected = mysql_select_db(
	   $db_server, 
	   $dbh1
	);
?>
