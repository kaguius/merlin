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
?>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>Branch</th>
				<th>Due</th>
				<th>Collected</th>
				<th>Rate</th>
				<th>Variance</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select id, stations, target_customers from stations where open_order != '' order by open_order asc");
	while ($row = mysql_fetch_array($sql))
	{
		$branch_id = $row['id'];
		$stations = $row['stations'];
		$target_customers = $row['target_customers'];	
		
		$sql2 = mysql_query("select sum(loan_total_interest)due from loan_application where loan_due_date between '$day_one' and '$current_date' and customer_station = '$branch_id' and loan_status not in ('12', '14,', '11', '10','15', '9') and loan_failure_status = '0' group by customer_station");
		while ($row = mysql_fetch_array($sql2))
		{
			$due = $row['due'];
			if($due == ""){
				$due = 0;
			}
			
			$sql3 = mysql_query("select distinct loan_code from loan_application where loan_due_date between '$day_one' and '$current_date' and customer_station = '$branch_id' and loan_status not in ('12', '14,', '11', '10', '15', '9') and loan_failure_status = '0'");
			while ($row = mysql_fetch_array($sql3))
			{
				$loan_code = $row['loan_code'];
				$sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
				while ($row = mysql_fetch_array($sql4))
				{
					$repayments = $row['repayments'];
					if($repayments == ""){
						$repayments = 0;
					}
					$total_repayments = $total_repayments + $repayments;
				}
			}
			
			$ratio = ($total_repayments / $due)*100;
			$variance = $due - $total_repayments;
			
			if ($intcount % 2 == 0) {
				$display= '<tr bgcolor = #F0F0F6>';
			}
			else {
				$display= '<tr>';
			}
			echo $display;
			echo "<td valign='top'>$stations</td>";
			echo "<td align='right' valign='top'>".number_format($due, 2)."</td>";
			echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";
			echo "<td align='right' valign='top'>".number_format($ratio, 0)."%</td>";	
			echo "<td align='right' valign='top'>".number_format($variance, 2)."</td>";
			
			//insert into the table
			$sql5 = "insert into daily_report_amount_due(report_date, branch, amount_due, amount_collected, amount_rate, amount_variance)values('$current_date', '$stations', '$due', '$total_repayments', '$ratio', '$variance')";
        	$result = mysql_query($sql5);
			
			$total_due = $total_due + $due;
			$total_total_repayments = $total_total_repayments + $total_repayments;
			
			$due = 0;
			$total_repayments = 0;
			$ratio = 0;
			$variance = 0;
		}	
	}
	$total_variance = $total_due - $total_total_repayments;;
	$total_ratio = ($total_total_repayments / $total_due)*100;
	
	echo $display;
	echo "<td valign='top'>Business</td>";
	echo "<td align='right' valign='top'>".number_format($total_due, 2)."</td>";
	echo "<td align='right' valign='top'>".number_format($total_total_repayments, 2)."</td>";
	echo "<td align='right' valign='top'>".number_format($total_ratio, 0)."%</td>";	
	echo "<td align='right' valign='top'>".number_format($total_variance, 2)."</td>";
	//insert into the table
	$sql5 = "insert into daily_report_amount_due(report_date, branch, amount_due, amount_collected, amount_rate, amount_variance)values('$current_date', 'Business', '$total_due', '$total_total_repayments', '$total_ratio', '$total_variance')";
    $result = mysql_query($sql5);

?>