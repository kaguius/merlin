<?php
	//delete settled accounts from the dialer every hour
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$transactiontime = date("Y-m-d G:i:s");
	
	//Dormant customers: Force a store visit when activated
	$sql = mysql_query("select distinct id, affordability from users");
	while ($row = mysql_fetch_array($sql))
	{
		$customer_id = $row['id'];
		$affordability = $row['affordability'];
		
		$sql2 = mysql_query("select customer_id, loan_code, loan_date from loan_application where customer_id = '$customer_id' order by loan_date desc limit 1");
		while ($row = mysql_fetch_array($sql2))
		{
			$customer_id = $row['customer_id'];
			$loan_code = $row['loan_code'];
			$loan_date = $row['loan_date'];
			
			$date1 = strtotime($loan_date);
			$date2 = strtotime($current_date);
			$dateDiff = $date2 - $date1;
			$days = floor($dateDiff/(60*60*24));
			
			if($days > 120){
				echo $loan_date." - ".$loan_code." - ".$customer_id." - ".$days." - ".$affordability;
				echo "<br />";
				$sql3="update users set affordability = '0' where id = '$customer_id'";
				$result = mysql_query($sql3);

				$sql4 = "INSERT INTO dormant_customers(customer_id, customer_station, loan_code, dormant_date, dormant_transactiontime)
				VALUES('$customer_id', '$customer_station', '$loan_code', '$current_date', '$transactiontime')";
				$result = mysql_query($sql4);
			}
		}
	}

	//Delete all loans pending disbursement that have not been done by day's end.
	$sql = mysql_query("select loan_date, loan_code, loan_status from loan_application where loan_status = '10'");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_code = $row['loan_code'];
		
		$sql3="update loan_application set loan_status = '12' where loan_code = '$loan_code'";
		$result = mysql_query($sql3);
	}

?>
