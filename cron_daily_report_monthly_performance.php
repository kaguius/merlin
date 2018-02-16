<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$term = '1';
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$current_date = date('Y-m-d', strtotime($current_date) - (24 * 3600 * $term));
	
?>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>Branch</th>
				<th>Target</th>
				<th>Actual</th>
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
		
		$sql2 = mysql_query("select count)loan_id)new_customers from loan_application where initiation_fee != '0' and extract(month from loan_date) = '$filter_month' and extract(year from loan_date) = '$filter_year' and customer_station = '$branch_id' and loan_status not in ('12', '14,', '11', '10', '0') and loan_failure_status = '0' group by customer_station");
		while ($row = mysql_fetch_array($sql2))
		{
			$new_customers = $row['new_customers'];
			if($new_customers == '0'){
				$daily_variance = 0;
			}
			else{
				$daily_variance = ($new_customers / $target_customers) * 100;
			}
		}	
		
		if ($intcount % 2 == 0) {
			$display= '<tr bgcolor = #F0F0F6>';
		}
		else {
			$display= '<tr>';
		}
		echo $display;
		echo "<td valign='top'>$stations</td>";
		echo "<td align='right' valign='top'>".number_format($target_customers, 2)."</td>";
		echo "<td align='right' valign='top'>".number_format($new_customers, 2)."</td>";
		echo "<td align='right' valign='top'>".number_format($daily_variance, 2)."%</td>";	
		
		//insert into the table
		$sql3 = "insert into daily_report_customers(report_date, branch, target_customers, actual_customers, daily_variance)values('$current_date', '$stations', '$target_customers', '$new_customers', '$daily_variance')";
        $result = mysql_query($sql3);
		
		$total_target_customers = $total_target_customers + $target_customers;
		$total_new_customers = $total_new_customers + $new_customers;
		
		$target_customers = 0;
		$new_customers = 0;
		$daily_variance = 0;
	}
	$total_daily_variance = ($total_new_customers / $total_target_customers) * 100;
	
	echo $display;
	echo "<td valign='top'>Business</td>";
	echo "<td align='right' valign='top'>".number_format($total_target_customers, 2)."</td>";
	echo "<td align='right' valign='top'>".number_format($total_new_customers, 2)."</td>";
	echo "<td align='right' valign='top'>".number_format($total_daily_variance, 2)."%</td>";
	
	//insert into the table
	$sql4 = "insert into daily_report_customers(report_date, branch, target_customers, actual_customers, daily_variance)values('$current_date', 'Business', '$total_target_customers', '$total_new_customers', '$total_daily_variance')";
    $result = mysql_query($sql4);
?>