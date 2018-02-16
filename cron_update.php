<?php
	//Updates the customer_id and customer_station on the db once loan_repayments are done

	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;

	$sql = mysql_query("select loan_code from branch_establish order by id asc");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_code = $row['loan_code'];
		//$loan_rep_code = $row['loan_rep_code'];
		
		$sql2 = mysql_query("select stations.stations, customer_station from loan_application inner join stations on stations.id = loan_application.customer_station where loan_code = '$loan_code'");
		//$sql2 = mysql_query("select customer_id, customer_station from loan_application where loan_code = '$loan_rep_code' limit 1");
		while ($row = mysql_fetch_array($sql2))
		{
			$stations = $row['stations'];
			$customer_station = $row['customer_station'];
		
			echo $loan_code." | ".$stations." | ".$customer_station."<br />";
		}
	}
?>