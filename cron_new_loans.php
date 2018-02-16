<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$sql = mysql_query("select id, mobile_no, msg_text from out_msg_logs where new = '1' order by id asc");
	while ($row = mysql_fetch_array($sql))
	{
		$id = $row['id'];
		$mobile_no = $row['mobile_no'];
		$msg_text = $row['msg_text'];
		
		$sql2="update out_msg_logs set new='2', status = '1`' WHERE id  = '$id'";
		//echo $sql2."<br />";
		$result = mysql_query($sql2);
		
		$msg_api = "http://api.infobip.com/api/v3/sendsms/plain?user=".$msg_username."&password=".$msg_password."&sender=".$msg_sender."&SMSText=".$msg_text."&GSM=".$mobile_no."&type=longSMS";
		//echo $msg_api."<br />";
		
		header( "Location: $msg_api" );
		exit ;
	}
	
	
?>
