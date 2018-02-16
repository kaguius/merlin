<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$sql10 = mysql_query("select distinct customer_id, count(loan_id)counts, loan_code, loan_mobile from loan_application group by customer_id order by counts desc");
	while ($row = mysql_fetch_array($sql10))
	{
		$customer_id = $row['customer_id'];
		$counts = $row['counts'];
		$loan_code = $row['loan_code'];
		$loan_mobile = $row['loan_mobile'];
		
		$sql11 = mysql_query("select loan_date, loan_due_date, loan_code, loan_status, loan_mpesa_code, loan_mobile, loan_amount, loan_total_interest from loan_application where customer_id = '$customer_id' and loan_status = '2' order by loan_date desc LIMIT 1,$counts");
		while ($row = mysql_fetch_array($sql11))
		{
			$loan_date = $row['loan_date'];
			$loan_due_date = $row['loan_due_date'];
			$loan_code = $row['loan_code'];
			$loan_status = $row['loan_status'];
			$loan_mpesa_code = $row['loan_mpesa_code'];
			$loan_mobile = $row['loan_mobile'];
			$loan_amount = $row['loan_amount'];
			$loan_total_interest = $row['loan_total_interest'];
			echo "$customer_id | $loan_date | $loan_due_date | $loan_code | $loan_status | $loan_mpesa_code | $loan_mobile | $loan_amount | $loan_total_interest<br />";
		}
	}
	
?>
