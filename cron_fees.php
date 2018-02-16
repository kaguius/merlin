<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
?>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>Customer</th>
				<th>Loan Date</th>
				<th>Loan Due Date</th>
				<th>Mobile</th>
				<th>Branch</th>
				<th>Loan Code</th>
				<th>Disbursed</th>
				<th>Interest</th>
				<th>Late Fees</th>
				<th>Fees</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select loan_id, loan_date, loan_due_date, loan_mobile, customer_id, customer_station, loan_status, loan_mpesa_code, loan_code, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, early_settlement_surplus, loan_total_interest from loan_application where loan_date between '2014-01-01' and '2014-12-31' order by loan_date asc");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_id = $row['loan_id'];
		$loan_date = $row['loan_date'];
		$loan_due_date = $row['loan_due_date'];
		$loan_mobile = $row['loan_mobile'];
		$customer_id = $row['customer_id'];
		$customer_station = $row['customer_station'];
		$loan_status = $row['loan_status'];
		$loan_mpesa_code = $row['loan_mpesa_code'];
		$loan_code = $row['loan_code'];
		$initiation_fee = $row['initiation_fee'];
		$loan_amount = $row['loan_amount'];
		$loan_extension = $row['loan_extension'];
		$loan_interest = $row['loan_interest'];
		$loan_late_interest = $row['loan_late_interest'];
		$admin_fee = $row['admin_fee'];
		$appointment_fee = $row['appointment_fee'];
		$early_settlement = $row['early_settlement'];
		$early_settlement_surplus = $row['early_settlement_surplus'];
		$fix = $row['fix'];
		$early_settlement_surplus = $row['early_settlement_surplus'];
		$loan_total_interest = $row['loan_total_interest'];
	
		$loan_total_interest_calculated = $loan_amount + $initiation_fee + $loan_late_interest + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
	
		$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
		while ($row = mysql_fetch_array($sql2))
		{
			$repayments = $row['repayments'];
			if($repayments == ''){
				$repayments = 0;
			}
		}
		
		$sql2 = mysql_query("select first_name, last_name from users where id = '$customer_id'");
		while ($row = mysql_fetch_array($sql2))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$customer_name = $first_name.' '.$last_name;		
		}
		
		$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
		while ($row = mysql_fetch_array($sql2))
		{
			$stations_name = $row['stations'];	
		}
		
		$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
		while ($row = mysql_fetch_array($sql2))
		{
			$status_name = $row['status'];	
		}
		
		//$loan_total_interest_calculated = $loan_amount + $initiation_fee + $loan_late_interest + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
		
		$fees = $initiation_fee + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;		
			
		if ($intcount % 2 == 0) {
			$display= '<tr bgcolor = #F0F0F6>';
		}
		else {
			$display= '<tr>';
		}
		echo $display;
		echo "<td valign='top'>$customer_name</td>";
		echo "<td valign='top'>$loan_date</td>";
		echo "<td valign='top'>$loan_due_date</td>";
		echo "<td valign='top'>$loan_mobile</td>";
		echo "<td valign='top'>$stations_name</td>";
		echo "<td valign='top'>$loan_code</td>";
		echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
		echo "<td align='right' valign='top'>".number_format($loan_interest, 2)."</td>";
		echo "<td align='right' valign='top'>".number_format($loan_late_interest, 2)."</td>";	
		echo "<td align='right' valign='top'>".number_format($fees, 2)."</td>";		
		echo "<td align='right' valign='top'>".number_format($loan_total_interest_calculated, 2)."</td>";				
		echo "</tr>";
		$loan_amount = 0;
		$fees = 0;
		$loan_total_interest = 0;
		$repayments = 0;
		$balance = 0;
	}
?>