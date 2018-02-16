<?php
	//Update loan balances on the system
	
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;

	$sql = mysql_query("select loan_id, customer_id, loan_code, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, waiver, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_total_interest from loan_application order by loan_id asc");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_id = $row['loan_id'];
		$customer_id = $row['customer_id'];
		$loan_code = $row['loan_code'];
		$initiation_fee = $row['initiation_fee'];
		$loan_amount = $row['loan_amount'];
		$loan_extension = $row['loan_extension'];
		$loan_interest = $row['loan_interest'];
		$loan_late_interest = $row['loan_late_interest'];
		$waiver = $row['waiver'];
		$admin_fee = $row['admin_fee'];
		$appointment_fee = $row['appointment_fee'];
		$early_settlement = $row['early_settlement'];
		$early_settlement_surplus = $row['early_settlement_surplus'];
		$fix = $row['fix'];
		$joining_fee = $row['joining_fee'];
		$loan_total_interest = $row['loan_total_interest'];
		
		$loan_total_interest_calculated = $initiation_fee + $loan_amount + $loan_extension + $loan_interest + $loan_late_interest + $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
		
		$sql3="update loan_application set loan_total_interest='$loan_total_interest_calculated' WHERE loan_id  = '$loan_id'";
		echo $sql3."<br />";
		$result = mysql_query($sql3);
	}
?>
