<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$term = '1';
	$day_one = '01';
	$day_one = $filter_year.'-'.$filter_month.'-'.$day_one;
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$current_date = date('Y-m-d', strtotime($current_date) - (24 * 3600 * $term));
	

	$sql = mysql_query("select id, stations, daily_target, monthly_target from stations where open_order != '' order by open_order asc");
	while ($row = mysql_fetch_array($sql))
	{
		$branch_id = $row['id'];
		$stations = $row['stations'];
		$daily_target = $row['daily_target'];
		$monthly_target = $row['monthly_target'];	
		
		$sql2 = mysql_query("select sum(loan_amount)loan_amount from loan_application where loan_date = '$current_date' and customer_station = '$branch_id' and loan_status not in ('12', '14,', '11', '10', '0') and loan_failure_status = '0' group by customer_station");
		
		while ($row = mysql_fetch_array($sql2))
		{
			$daily_loan_amount = $row['loan_amount'];
			if($daily_loan_amount == '0'){
				$daily_variance = 0;
			}
			else{
				$daily_variance = ($daily_loan_amount / $daily_target) * 100;
			}
		}	
		
		$sql2 = mysql_query("select sum(loan_amount)loan_amount from loan_application where loan_date between '$day_one' and '$current_date' and customer_station = '$branch_id' and loan_status not in ('12', '14,', '11', '10', '0') and loan_failure_status = '0' group by customer_station");
		while ($row = mysql_fetch_array($sql2))
		{
			$monthly_loan_amount = $row['loan_amount'];
			if($monthly_loan_amount == '0'){
				$monthly_variance = 0;
			}
			else{
				$monthly_variance = ($monthly_loan_amount / $monthly_target) * 100;
			}
		}	
		
		//insert into the table
		$sql3 = "insert into daily_report_disbursements(report_date, branch, daily_target, daily_actual, daily_variance, monthly_target, monthly_actual, moanthly_variance)values('$current_date', '$stations', '$daily_target', '$daily_loan_amount', '$daily_variance', '$monthly_target', '$monthly_loan_amount', '$monthly_variance')";
        $result = mysql_query($sql3);
		
		//totals
		$total_daily_target = $total_daily_target + $daily_target;
		$total_daily_loan_amount = $total_daily_loan_amount + $daily_loan_amount;
		$total_monthly_target = $total_monthly_target + $monthly_target;
		$total_monthly_loan_amount = $total_monthly_loan_amount + $monthly_loan_amount;
		
		//Defaults
		$daily_target = 0;
		$daily_loan_amount = 0;
		$daily_variance = 0;
		$monthly_target = 0;
		$monthly_loan_amount = 0;
		$monthly_variance = 0;
	}
	
	$total_daily_variance = ($total_daily_loan_amount / $total_daily_target) * 100;
	$total_monthly_variance = ($total_monthly_loan_amount / $total_monthly_target) * 100;
	
	//insert into the table
	$sql4 = "insert into daily_report_disbursements(report_date, branch, daily_target, daily_actual, daily_variance, monthly_target, monthly_actual, moanthly_variance)values('$current_date', 'Business', '$total_daily_target', '$total_daily_loan_amount', '$total_daily_variance', '$total_monthly_target', '$total_monthly_loan_amount', '$total_monthly_variance')";
	echo $sql4;
    $result = mysql_query($sql4);
?>