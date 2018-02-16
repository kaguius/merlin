<?php
	//delete settled accounts from the dialer every hour
	include_once('includes/db_conn.php');
	//include_once('includes/db_conn_dialer.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$transactiontime = date("Y-m-d G:i:s");
	
	$sql="truncate table user_logs";
	$result = mysql_query($sql);
	
	$sql2="insert into cron_jobs(cron_job, transactiontime)values('password_reset', '$transactiontime')";
	$result = mysql_query($sql2);

?>
