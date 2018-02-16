<?php
	
	$host_dialer='172.16.16.2';
	//$host_dialer='197.248.141.94';
	$db_dialer='asterisk';
	$user_dialer='kaguius';
	$pwd_dialer='48Kaguius92%';
	
	$dbh2 = mysql_connect($host_dialer, $user_dialer, $pwd_dialer, true); 
	mysql_select_db($db_dialer, $dbh2);

	//date_default_timezone_set("Africa/Khartoum");
?>
