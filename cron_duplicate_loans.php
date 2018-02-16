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
				<th>Phone</th>
				<th>Loan Date</th>
				<th>Loan Due Date</th>
				<th>Loan Code</th>
				<th>Loan MPESA Code</th>
				<th>Loan Amount</th>
				<th>Loan Total</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select distinct loan_mpesa_code, count(loan_id)loans from loan_application group by loan_mpesa_code order by loans desc");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_mpesa_code = $row['loan_mpesa_code'];
		$loans = $row['loans'];
		
		if($loans > 1){
			$sql2 = mysql_query("select loan_id, loan_mpesa_code, customer_id, loan_mobile, loan_date, loan_due_date, loan_code, initiation_fee, loan_amount, loan_extension, loan_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_total_interest, customer_station from loan_application where loan_mpesa_code = '$loan_mpesa_code' order by loan_date asc");
			while ($row = mysql_fetch_array($sql2))
			{
				$loan_id = $row['loan_id'];
				$customer_id = $row['customer_id'];
				$loan_date = $row['loan_date'];
				$loan_due_date = $row['loan_due_date'];
				$loan_code = $row['loan_code'];
				$customer_station = $row['customer_station'];
				$loan_mobile = $row['loan_mobile'];
				$loan_mpesa_code = $row['loan_mpesa_code'];
		
				$initiation_fee = $row['initiation_fee'];
				$loan_amount = $row['loan_amount'];
				$loan_extension = $row['loan_extension'];
				$loan_interest = $row['loan_interest'];
				$admin_fee = $row['admin_fee'];
				$appointment_fee = $row['appointment_fee'];
				$early_settlement = $row['early_settlement'];
				$early_settlement_surplus = $row['early_settlement_surplus'];
				$fix = $row['fix'];
				$joining_fee = $row['joining_fee'];
				$loan_total_interest = $row['loan_total_interest'];
	
				$loan_total = $initiation_fee + $loan_amount + $loan_extension + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
		
				$sql3 = mysql_query("select first_name, last_name, national_id from users where id = '$customer_id'");
				while ($row = mysql_fetch_array($sql3))
				{
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$first_name = ucwords(strtolower($first_name));	
					$last_name = ucwords(strtolower($last_name));
					$customer_name = $first_name.' '.$last_name;		
					$national_id = $row['national_id'];
				}
				$sql4 = mysql_query("select stations from stations where id = '$customer_station'");
				while ($row = mysql_fetch_array($sql3))
				{
					$customer_stations = $row['stations'];
				}
	
				
				if ($intcount % 2 == 0) {
					$display= '<tr bgcolor = #F0F0F6>';
				}
				else {
					$display= '<tr>';
				}
				echo $display;
				echo "<td valign='top'>$customer_name</td>";
				echo "<td valign='top'>$loan_mobile</td>";
				
				echo "<td valign='top'>$loan_date</td>";
				echo "<td valign='top'>$loan_due_date</td>";
				echo "<td valign='top'>$loan_code</td>";
				echo "<td valign='top'>$loan_mpesa_code</td>";
				echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
				echo "<td align='right' valign='top'>".number_format($loan_total_interest, 2)."</td>";		
				echo "</tr>";
			}
			$loan_mpesa_code = "";
			$loans = 0;
		}
	}
?>