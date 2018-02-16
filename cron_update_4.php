<?php
	//update customer_id and customer_station for loan_repayments
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$sql = mysql_query("select loan_rep_code from loan_repayments where customer_station = '3'");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_rep_code = $row['loan_rep_code'];
		
		$sql2 = mysql_query("select customer_id, customer_station from loan_application where loan_code = '$loan_rep_code'");
		while ($row = mysql_fetch_array($sql2))
		{
			$customer_id = $row['customer_id'];
			$customer_station = $row['customer_station'];
		
			$sql3="update loan_repayments set customer_id='$customer_id', customer_station='$customer_station' WHERE loan_rep_code  = '$loan_rep_code'";
			echo $sql3."<br />";
			$result = mysql_query($sql3);
		}
	}
?>
