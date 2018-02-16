<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	//$sql = mysql_query("select distinct EXTRACT(day FROM loan_due_date)day, loan_due_date, sum(loan_total_interest)amount from loan_application where EXTRACT(month FROM loan_due_date) = '$filter_month' and EXTRACT(year FROM loan_due_date) = '$filter_year' group by EXTRACT(day FROM loan_due_date)");
	$sql = mysql_query("select distinct loan_code, loan_due_date, customer_id, sum(loan_total_interest)amount, loan_status from loan_application where EXTRACT(month FROM loan_due_date) = '12' and EXTRACT(year FROM loan_due_date) = '2014' and EXTRACT(day FROM loan_due_date) between '01' and '18' and customer_station = '2' group by loan_code");
	$total_repayments = 0;
	while ($row = mysql_fetch_array($sql))
	{
		$intcount++;
		//$day = $row['day'];
		$loan_code = $row['loan_code'];
		$loan_due_date = $row['loan_due_date'];
		$amount = $row['amount'];
		$customer_id = $row['customer_id'];
		$loan_status = $row['loan_status'];
		
		$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
		while ($row = mysql_fetch_array($sql2))
		{
			$status_name = $row['status'];
			$status_name = ucwords(strtolower($status_name));	
		}
	
		$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
		while ($row = mysql_fetch_array($sql3))
		{
			$repayments = $row['repayments'];
			if($repayments == ""){
				$repayments = 0;
			}
			$total_repayments = $total_repayments + $repayments;
		}
		
		$sql2 = mysql_query("select first_name, last_name, mobile_no, national_id, collections_officer from users where id = '$customer_id'");
		while ($row = mysql_fetch_array($sql2))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$name = $first_name.' '.$last_name;		
			$mobile_no = $row['mobile_no'];
			$national_id = $row['national_id'];
			$collections_officer = $row['collections_officer'];
			$sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_officer'");
			while ($row = mysql_fetch_array($sql3))
			{
				$collections_first_name = $row['first_name'];
				$collections_last_name = $row['last_name'];
				$collections_name = $collections_first_name.' '.$collections_last_name;		
			}
		}
		
		
		
		$percentage = ($total_repayments / $amount)*100;
		$amount = number_format($amount, 0);
		$total_repayments = number_format($total_repayments, 0);
		$percentage = number_format($percentage, 0);
		
		echo "$loan_due_date | $amount | $total_repayments | $first_name | $national_id | $last_name | $status_name | $mobile_no | $collections_name | $percentage% <br />";
		
		$amount = 0;
		$repayments = 0;
		$total_repayments = 0;
		//$collections_name = "";
	}

?>
