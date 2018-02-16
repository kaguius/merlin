<?php
	//delete settled accounts from the dialer every hour
	include_once('includes/db_conn.php');
	include_once('includes/db_conn_dialer.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$transactiontime = date("Y-m-d G:i:s");
	
	$sql = mysql_query("select substring(loan_mobile,4) as loan_mobile from loan_application where loan_status in('13','12','14','11')", $dbh1);
	while ($row = mysql_fetch_array($sql))
	{
		$loan_mobile = $row['loan_mobile'];
		
		$sql2 = mysql_query("delete from vicidial_list where phone_number = '$loan_mobile'");
		$result = mysql_query($sql2, $dbh2);
	}
	
	//Updates the customer_id and customer_station for repayments from the loans table
	$sql = mysql_query("select loan_rep_code from loan_repayments where customer_id = '0'", $dbh1);
	while ($row = mysql_fetch_array($sql))
	{
		$loan_rep_code = $row['loan_rep_code'];
		
		$sql2 = mysql_query("select customer_id, customer_station from loan_application where loan_code = '$loan_rep_code'", $dbh1);
		while ($row = mysql_fetch_array($sql2))
		{
			$customer_id = $row['customer_id'];
			$customer_station = $row['customer_station'];
		
			$sql3="update loan_repayments set customer_id='$customer_id', customer_station='$customer_station' WHERE loan_rep_code  = '$loan_rep_code'";
			$result = mysql_query($sql3, $dbh1);
		}
	}

	$sql = mysql_query("select loan_rep_code from loan_repayments where customer_station in ('3', '4', '10')", $dbh1);
	while ($row = mysql_fetch_array($sql))
	{
		$loan_rep_code = $row['loan_rep_code'];
		
		$sql2 = mysql_query("select customer_id, customer_station from loan_application where loan_code = '$loan_rep_code'", $dbh1);
		while ($row = mysql_fetch_array($sql2))
		{
			$customer_id = $row['customer_id'];
			$customer_station = $row['customer_station'];
		
			$sql3="update loan_repayments set customer_id='$customer_id', customer_station='$customer_station' WHERE loan_rep_code  = '$loan_rep_code'";
			//echo $sql3."<br />";
			$result = mysql_query($sql3, $dbh1);
		}
	}
	
	//Updates loans for Dagoretti and confirm they have been sorted by the API
	//$sql3="update loan_application set loan_failure_status = '0' where loan_failure_status = '1' and customer_station = '5'";
	//$result = mysql_query($sql3, $dbh1);

?>
