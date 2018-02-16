<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$audit_date = '2014-12-31';
?>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>Customer</th>
				<th>Phone</th>
				<th>National ID</th>
				<th>Branch</th>
				<th>Loan Date</th>
				<!-- <th>Loan Due Date</th> -->
				<th>Loan Code</th>
				<th>Amount</th>
				<th>Due</th>
			    <th>Payments</th>
				<th>Balance</th>
				<th>Overdue</th>
				<!--<th>Vintage</th> -->
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select customer_id, loan_mobile, loan_date, loan_due_date, loan_code, initiation_fee, loan_amount, loan_extension, loan_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, customer_station from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '2014-01-01' and '2014-12-31' and loan_status != '13' and loan_status != '12' and loan_status != '11' and loan_status != '2' and loan_status != '14' and loan_status != '15' and loan_status != '8' order by loan_date asc");
	while ($row = mysql_fetch_array($sql))
	{
		$customer_id = $row['customer_id'];
		$loan_date = $row['loan_date'];
		$loan_due_date = $row['loan_due_date'];
		$loan_code = $row['loan_code'];
		$customer_station = $row['customer_station'];
		$loan_mobile = $row['loan_mobile'];
		
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
	
		$loan_total = $initiation_fee + $loan_amount + $loan_extension + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
	
		$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$loan_code' group by loan_rep_code");
		while ($row = mysql_fetch_array($sql2))
		{
			$repayments = $row['repayments'];
			if($repayments == ""){
				$repayments = 0;
			}
		}
		
		$sql2 = mysql_query("select first_name, last_name, national_id from users where id = '$customer_id'");
		while ($row = mysql_fetch_array($sql2))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$customer_name = $first_name.' '.$last_name;		
			$national_id = $row['national_id'];
		}
		
		$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
		while ($row = mysql_fetch_array($sql2))
		{
			$customer_stations = $row['stations'];
		}
	
		$loan_balance = $loan_total - $repayments;
	
		if($loan_due_date != "" || $current_date != ""){
			$date1 = strtotime($loan_due_date);
			$date2 = strtotime($audit_date);
			$dateDiff = $date2 - $date1;
			$days = floor($dateDiff/(60*60*24));

			$dateArrears = $date2 - $date1;
			$Arrearsdays = floor($dateArrears/(60*60*24));
		}
		
		if($Arrearsdays == ""){
			$Arrearsdays = 0;
		}
		
		if($Arrearsdays <= 0){
			$vintage = "CD 0";
		}
		else if($Arrearsdays <= 7){
			$vintage = "CD 1";
		}
		else if($Arrearsdays <= 14){
			$vintage = "CD 2";
		}
		else if($Arrearsdays <= 22){
			$vintage = "CD 3";
		}
		else if($Arrearsdays <= 30){
			$vintage = "CD 4";
		}
		else if($Arrearsdays <= 37){
			$vintage = "CD 5";
		}
		else if($Arrearsdays <= 44){
			$vintage = "CD 6";
		}
		else if($Arrearsdays <= 51){
			$vintage = "CD 7";
		}
		else if($Arrearsdays <= 58){
			$vintage = "CD 8";
		}
		else if($Arrearsdays <= 65){
			$vintage = "CD 9";
		}
		else if($Arrearsdays <= 72){
			$vintage = "CD 10";
		}
		else if($Arrearsdays <= 79){
			$vintage = "CD 11";
		}
		else if($Arrearsdays <= 86){
			$vintage = "CD 12";
		}
		else if($Arrearsdays <= 93){
			$vintage = "CD 13";
		}
		else if($Arrearsdays <= 100){
			$vintage = "CD 14";
		}
		else if($Arrearsdays <= 106){
			$vintage = "CD 15";
		}
		else if($Arrearsdays <= 113){
			$vintage = "CD 16";
		}
		else if($Arrearsdays <= 120){
			$vintage = "CD 17";
		}
		else if($Arrearsdays > 120){
			$vintage = "CD 18";
		}
		if($Arrearsdays >= 0 && $loan_balance > 0){
			if ($intcount % 2 == 0) {
				$display= '<tr bgcolor = #F0F0F6>';
			}
			else {
				$display= '<tr>';
			}
			echo $display;
			echo "<td valign='top'>$customer_name</td>";
			echo "<td valign='top'>$loan_mobile</td>";
			echo "<td valign='top'>$national_id</td>";
			echo "<td valign='top'>$customer_stations</td>";
			echo "<td valign='top'>$loan_date</td>";
			
			echo "<td valign='top'>$loan_code</td>";
			echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
			echo "<td align='right' valign='top'>".number_format($loan_total, 2)."</td>";
			echo "<td align='right' valign='top'>".number_format($repayments, 2)."</td>";
			echo "<td align='right' valign='top'>".number_format($loan_balance, 2)."</td>";
			echo "<td valign='top'>$vintage</td>";			
			echo "</tr>";
		}
		$loan_amount = 0;
		$loan_total = 0;
		$repayments = 0;
		$loan_balance = 0;
	}
?>