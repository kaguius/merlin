<?php
	//delete settled accounts from the dialer every hour
	include_once('includes/db_conn.php');
	//include_once('includes/db_conn_dialer.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$transactiontime = date("Y-m-d G:i:s");
	
	//Updates the customer_id and customer_station for repayments from the loans table
	$sql = mysql_query("select loan_code from update_collector order by id asc", $dbh1);
	while ($row = mysql_fetch_array($sql))
	{
		$loan_code = $row['loan_code'];
		
		$sql2 = mysql_query("select loan_code, current_collector from loan_application where loan_code = '$loan_code'", $dbh1);
		while ($row = mysql_fetch_array($sql2))
		{
			$loan_code_2 = $row['loan_code'];
			$current_collector = $row['current_collector'];
		
			$sql3="update loan_repayments set current_collector = '$current_collector' where loan_rep_code = '$loan_code' and loan_rep_date > '2015-12-16'";
			$result = mysql_query($sql3, $dbh1);
			echo $loan_code_2." ".$current_collector."<br />";
		}
	}

?>
