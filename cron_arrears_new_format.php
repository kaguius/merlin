<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');

	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
	//$current_date = '2015-11-05';
	$transactiontime = date("Y-m-d G:i:s");

     	$sql = mysql_query("select customer_id, customer_station, initiation_fee, loan_amount, loan_extension, loan_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_date, loan_due_date, loan_code, loan_status, late_status, customer_state from loan_application where loan_status != '6' and loan_status != '8' and loan_status != '12' and loan_status != '11' and loan_status != '14' and loan_status != '13' and loan_status != '15' and loan_status != '10' and loan_date > '2015-09-30' order by loan_date desc");
     	while ($row = mysql_fetch_array($sql)) {
     		$customer_id = $row['customer_id'];
	     	$loan_date = $row['loan_date'];
	     	$loan_due_date = $row['loan_due_date'];
	     	$loan_code = $row['loan_code'];
	     	$loan_status = $row['loan_status'];
	     	$current_loan_status = $loan_status;
	     	$late_status = $row['late_status'];
	     	$customer_state = $row['customer_state'];
			$customer_station = $row['customer_station'];

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
     		while ($row = mysql_fetch_array($sql2)) {
     			$repayments = $row['repayments'];
     			if ($repayments == "") {
     				$repayments = 0;
     			}
     		}

     		$loan_balance = $loan_total - $repayments;

     		if ($loan_due_date != "" || $current_date != "") {
     			$date1 = strtotime($loan_due_date);
     			$date2 = strtotime($current_date);
    			$dateDiff = $date2 - $date1;
     			$days = floor($dateDiff / (60 * 60 * 24));

     			$dateArrears = $date2 - $date1;
     			$Arrearsdays = floor($dateArrears / (60 * 60 * 24));
     		}


	     	if ($Arrearsdays > 0 && $loan_balance > 0) {
			if ($Arrearsdays <= '14') {
				if ($Arrearsdays == '1') {
			     		$interest_levied = $loan_amount * (5 / 100);
			     		if($interest_levied > 0){
						$sql8="insert into penallty_charged(penalty_date, loan_code, customer_station, penalty_amount, description, transactiontime)values('$current_date', '$loan_code', '$customer_station', '$interest_levied', 'Penalty 1: 5%', '$transactiontime')";
					     	$result = mysql_query($sql8);
				     	}
				}
		     	} 
			else if ($Arrearsdays > '14' && $Arrearsdays <= '30') {
				if ($Arrearsdays == '15') {
			     		$interest_levied = $loan_amount * (13 / 100);
			     		if($interest_levied > 0){
						$sql8="insert into penallty_charged(penalty_date, loan_code, customer_station, penalty_amount, description, transactiontime)values('$current_date', '$loan_code', '$customer_station', '$interest_levied', 'Penalty 2: 8%', '$transactiontime')";
					     	$result = mysql_query($sql8);
				     	}
				}
		     	} 
			else if ($Arrearsdays > '30') {
				if ($Arrearsdays == '31') {
			     		$interest_levied = $loan_amount * (28 / 100);
			     		if($interest_levied > 0){
						$sql8="insert into penallty_charged(penalty_date, loan_code, customer_station, penalty_amount, description, transactiontime)values('$current_date', '$loan_code', '$customer_station', '$interest_levied', 'Penalty 3: 15%', '$transactiontime')";
					     	$result = mysql_query($sql8);
				     	}
				}
		     	}
		}
	     	else {
	     		$loan_late_interest = $loan_late_interest;
	     		$latest_loan = $loan_total + $loan_late_interest;
	     	}
  
     	}
	$sql14 = "insert into cron_jobs(cron_job, transactiontime)values('cron_arrears_penalty_monthly_update', '$transactiontime')";
	//echo $sql14."<br />";
	$result = mysql_query($sql14);
