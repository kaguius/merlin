<?php
	//include_once('includes/header.php');
	//cron job runs every 30 seconds
	//quesries for mpesa statuses based on requests sent
	include_once('includes/db_conn.php');
	
	$mpesa_user_name = "afb_client";
	$mpesa_password = "F@.5671hD573";
	
	$sql = mysql_query("select loan_code from loan_application where loan_failure_status = '2'");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_code = $row['loan_code'];
		
		$mobile_money_query = "https://197.232.2.170:8230/afb/query?transaction_id=".$loan_code."&username=".$mpesa_user_name."&password=".$mpesa_password."";
		echo $mobile_money_query."<br />";
		
        header( "Location: $mobile_money_request" );
		exit ;
	}
	
	
?>
