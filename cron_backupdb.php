<?php
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$transactiontime = date("Y-m-d G:i:s");
	
	//Database Backup
		include_once('includes/db_conn.php');
		$backupFile='/var/www/afb/backup/daily_'.$db_server.'.sql';
		$command = "mysqldump -u$user_server -p$pwd_server -h$host_server $db_server > $backupFile";
		system($command, $result);
		echo $result;
		
	//Database Optimization
		$connection = mysql_connect($host_server, $user_server, $pwd_server);
		$sql = "SHOW TABLES FROM $db";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_row($result))
		{
			if ( $db_server == "information_schema" )
			continue;

			$sql = "OPTIMIZE TABLE `".$row[0]."`";
			$erg = mysql_query($sql, $connection) or die(mysql_error());
			$data= mysql_fetch_array($erg, MYSQL_ASSOC);
		}
	
	//Database Analyze	
		$connection = mysql_connect($host_server, $user_server, $pwd_server);
		$sql = "SHOW TABLES FROM $db";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_row($result))
		{
			if ( $db_server == "information_schema" )
			continue;

			$sql = "ANALYZE TABLE `".$row[0]."`";
			$erg = mysql_query($sql, $connection) or die(mysql_error());
			$data= mysql_fetch_array($erg, MYSQL_ASSOC);

		}
	
	$sql14="insert into cron_jobs(cron_job, transactiontime)values('daily_backup', '$transactiontime')";
	$result = mysql_query($sql14);
?>