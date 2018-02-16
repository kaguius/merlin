<?php
	//delete settled accounts from the dialer every hour
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = '2015-01-31';
	$transactiontime = date("Y-m-d G:i:s");

	$sql2 = mysql_query("select distinct customer_id, customer_station, loan_code, loan_date from loan_application where loan_date between '2014-12-01' and '2015-03-31' order by loan_date desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$customer_id = $row['customer_id'];
		$customer_station = $row['customer_station'];
		$loan_code = $row['loan_code'];
		$loan_date = $row['loan_date'];
		
		$sql = mysql_query("select affordability from users where id = '$customer_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$affordability = $row['affordability'];
		}
			
		$date1 = strtotime($loan_date);
		$date2 = strtotime($current_date);
		$dateDiff = $date2 - $date1;
		$days = floor($dateDiff/(60*60*24));
			
		if($days > 120){
			echo $customer_station." - ".$loan_date." - ".$loan_code." - ".$customer_id." - ".$days." - ".$affordability;
			echo "<br />";
		}
	}
?>
