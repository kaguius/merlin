<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$transactiontime = date("Y-m-d G:i:s");
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$loan_date = $current_date;
	//ini_set('date.timezone', 'Africa/Nairobi');
	$time1 = date('H:i:s', gmdate('U'));
	$actual_day = date("l", strtotime($current_date));
	$sql = mysql_query("select holiday_name from holiday_names where holiday_date = '$current_date'");
	while ($row = mysql_fetch_array($sql))
	{
		$holiday_name = $row['holiday_name'];
		if($holiday_name !=""){
			$comments = 'holiday_exists';
		}
	}

	if (!empty($_GET)){	
		$who = $_GET['who'];
		$what = $_GET['what'];
		$time = $_GET['time'];
	}
	
	echo $who." - ".$what." - ".$time;
	echo "<br />";
	echo $time1;
	echo "<br />";
	
	$sql3="INSERT INTO incoming_messages (sender, msg, transactiontime) VALUES ('$who', '$what', '$transactiontime')";
	//echo $sql3."<br />";
	$result = mysql_query($sql3);
	
	$what = trim($what);
	$what = str_replace( ',', '', $what);
	$what = str_replace( ';', '', $what);
	$what = str_replace( ':', '', $what);
	$what = ucwords(strtolower($what));
	//echo $what."<br />";
	
	$sql = mysql_query("select id, first_name, last_name, mobile_no, dis_phone, affordability, loan_officer, collections_officer, stations from users where mobile_no = '$who' or dis_phone = '$who' or alt_phone = '$who'");
	while ($row = mysql_fetch_array($sql))
	{
		$user_id = $row['id'];
		$first_name = $row['first_name'];
		$first_name = strtoupper($first_name);
		$last_name = $row['last_name'];
		$name = $first_name.' '.$last_name;		
		$mobile_no = $row['mobile_no'];
		$dis_phone = $row['dis_phone'];
		$affordability = $row['affordability'];
		$customer_station = $row['stations'];
		$loan_officer = $row['loan_officer'];
		$collections_officer = $row['collections_officer'];
	}

	$who_prefix = substr($dis_phone ,0,5);
	if($who_prefix == 25470 || $who_prefix == 25471 || $who_prefix == 25472 || $who_prefix == 25479){
		$mobile_carrier = 'Safaricom';
	}
	else if($who_prefix == 25473 || $who_prefix == 25478){
		$mobile_carrier = 'Airtel';
	}
	else if($who_prefix == 25477){
		$mobile_carrier = 'Orange Mobile';
	}
	else if($who_prefix == 25475){
		$mobile_carrier = 'Essar Yu';
	}
	else if($who_prefix == 25476){
		$mobile_carrier = 'Equitel';
	}
	
	include_once('cron_limit_loans.php');

	$sql = mysql_query("select sum(loan_late_interest)penalty from loan_application where customer_id = '$user_id' group by loan_late_interest");
	while ($row = mysql_fetch_array($sql))
	{
		$penalty_charged = $row['penalty'];
		//$penalty_charged = 10000;
		$sql2 = mysql_query("select freeze from stations where id = '$customer_station'");
		while ($row = mysql_fetch_array($sql2))
		{
			$freeze = $row['freeze'];
		}
		//if on freeze enforce the penalty charged, if not penanlty charged = 0
		if($freeze == '0'){
			$penalty_charged = $penalty_charged;
		}
		else{
			$penalty_charged = 0;
		}
	}

	if($time1 > '08:30:00' && $time1 > '06:30:00' && $actual_day != 'Saturday' && $actual_day != 'Sunday' && $comments != 'holiday_exists'){
		if($customer_station == '9' || $customer_station == '5' || $customer_station == '11' || $customer_station == '12' || $customer_station == '13' || $customer_station == '14' || $customer_station == '17'){
			if($penalty_charged > '0'){
				$message_text = "Dear $first_name, Kindly note that repeat loans via SMS havent been activated for your branch. Kindly visit the branch for disbursement.";

				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$user_id', '$who', '$message_text', '0', '1', '$transactiontime')";
				//$result = mysql_query($sql6);
		
				//echo $sql6."<br />";	
				echo $message_text."<br />";
			}
			else{
				$sql = mysql_query("select loan_id, loan_date, loan_due_date, loan_mobile, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, waiver, loan_total_interest, loan_code, loan_mpesa_code, loan_failure_status, loan_status, loan_status, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee from loan_application where customer_id = '$user_id' and loan_status != '8' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' order by loan_date desc limit 1");
				while ($row = mysql_fetch_array($sql))
				{
					$initiation_fee = $row['initiation_fee'];
					$loan_amount = $row['loan_amount'];
					$loan_extension = $row['loan_extension'];
					$loan_interest = $row['loan_interest'];
					$loan_total_interest = $row['loan_total_interest'];
					$loan_status = $row['loan_status'];
					$loan_code = $row['loan_code'];
					$loan_status = $row['loan_status'];
					$loan_late_interest = $row['loan_late_interest'];
					$waiver = $row['waiver'];
					$loan_failure_status = $row['loan_failure_status'];
		
					$admin_fee = $row['admin_fee'];
					$appointment_fee = $row['appointment_fee'];
					$early_settlement = $row['early_settlement'];
					$early_settlement_surplus = $row['early_settlement_surplus'];
					$fix = $row['fix'];
					$joining_fee = $row['joining_fee'];
		
					$allocation_fees = $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
		
					$fees = $loan_extension + $initiation_fee + $loan_late_interest + $allocation_fees + $loan_interest;
		
					$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
					while ($row = mysql_fetch_array($sql2))
					{
						$loan_status_name = $row['status'];
					}
		
					$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
					while ($row = mysql_fetch_array($sql2))
					{
						$repayments = $row['repayments'];
						if($repayments == ''){
							$repayments = 0;
						}
					}
		
					$loan_balance = $loan_total_interest - $repayments;
		
					if($loan_status == '8' || $loan_status == '11' || $loan_status == '12' || $loan_status == '14' || $loan_status == '15' || $loan_status == '16'){
						$loan_balance = 0;
					}
				}

				if($what <= $loan_amount && $what >= '5000' && $affordability >= '5000'){
					$sql2 = mysql_query("select loan_band from loan_bands where loan_band = '$what'");
					while ($row = mysql_fetch_array($sql2))
					{
						$loan_band = $row['loan_band'];
						if(empty($loan_band)){
							$loan_band = 0;
						}
					}
					echo $loan_band."<br />";

					if($user_id == ""){
						$message_text = "Dear customer, you are not a registered member. Kindly visit any of our branches for registration.";
					}
					else if($loan_band != $what){
						$message_text = "Dear $first_name, the loan value entered is invalid, please enter a value in multiples of 2500 eg 5000; 7500; 10000 etc.";
					}
					else if($calculated_loan_balance != '0'){
						if($loan_status == '10'){
							$message_text = "Dear $first_name, the loan you applied for is being processed. Please be patient.";
						}
						else{
							$message_text = "Dear $first_name, you do not qualify for a new loan until you pay back your current loan.";
						}
					}
					else if($calculated_loan_balance == '0'){
						$message_text = "Dear $first_name, you have selected KES $what. The loan will be processed shortly. Kopa UPIA, kuza biashara.";
			
						$days = 30;
						$loan_amount = $what;
						$loan_interest = $loan_amount * ($days/100);
						$loan_total_interest = $loan_interest + $loan_amount;
						$loan_term = $days;
						$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
						$loan_due_date_day = date("l", strtotime($loan_due_date));
		
						if($loan_due_date_day == 'Saturday'){
							$days = 2;
							$loan_term = $days;
							$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
						}
						else if($loan_due_date_day == 'Sunday'){
							$days = 1;
							$loan_term = $days;
							$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
						}
						else if($comments == 'holiday_exists'){
							$days = 1;
							$loan_term = $days;
							$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
						}
						else{
							$loan_term = $days;
							$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
						}
						$sql = mysql_query("select id from loan_code");
						while ($row = mysql_fetch_array($sql))
						{
							$loan_code_latest = $row['id'];	
						}
						$sql3="INSERT INTO loan_application (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_officer, collections_officer, msg, UID, customer_station, loan_failure_status, loan_mpesa_code)
						VALUES('$loan_date', '$loan_term', '$loan_due_date', '$user_id', '$dis_phone', '$loan_amount', '$loan_interest', '$loan_total_interest', '2', '$loan_code_latest', '$loan_officer', '$collections_officer', '0', '94', '$customer_station', '1', '$loan_mpesa_code')";	
						//echo $sql3."<br />";
						//$result = mysql_query($sql3);
			
						$sql4="INSERT INTO call_center (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_officer, collections_officer, msg, UID, customer_station, loan_failure_status, loan_mpesa_code)
						VALUES('$loan_date', '$loan_term', '$loan_due_date', '$user_id', '$dis_phone', '$loan_amount', '$loan_interest', '$loan_total_interest', '2', '$loan_code_latest', '$loan_officer', '$collections_officer', '0', '94', '$customer_station', '1', '$loan_mpesa_code')";
						//echo $sql4."<br />";
						//$result = mysql_query($sql4);
			
						$sql = mysql_query("select loan_balance from overpayments_schedule where customer_id = '$user_id' order by id desc limit 1");
						while ($row = mysql_fetch_array($sql))
						{
							$loan_balance = $row['loan_balance'];	
						}
			
						$sql = mysql_query("select customer_station, loan_status, loan_failure_status from loan_application where loan_code = '$loan_code_latest'");
						while ($row = mysql_fetch_array($sql))
						{
							$loan_failure_status = $row['loan_failure_status'];	
							$loan_status_current_loan = $row['loan_status'];	
							$customer_station = $row['customer_station'];	
						}

						if($loan_failure_status == '1' && $loan_status_current_loan == '2'){		
							if($mobile_carrier == 'Safaricom'){
								$sql8="INSERT INTO mobile_money_requests (loan_code, msisdn, amount, carrier, new, customer_station, transactiontime) 
								VALUES('$loan_code_latest', '$dis_phone', '$loan_amount', '1', '1', '$customer_station', '$transactiontime')";	
							}
							else if($mobile_carrier == 'Airtel'){
								$sql8="INSERT INTO mobile_money_requests (loan_code, msisdn, amount, carrier, new, customer_station, transactiontime) 
								VALUES('$loan_code_latest', '$dis_phone', '$loan_amount', '2', '1', '$customer_station', '$transactiontime')";	
							}
							echo $sql8."<br />";
							//$result = mysql_query($sql8);
						}
			
						if($loan_balance > 0){
							$loan_balance = -$loan_balance;
							$loan_total_interest = $loan_total_interest + $loan_balance;
							$sql15="update loan_application set early_settlement_surplus='$loan_balance', loan_total_interest='$loan_total_interest' where loan_code = '$loan_code_latest'";
							//$result = mysql_query($sql15);
						}
			
						$sql = mysql_query("select distinct msg from loan_application where loan_code = '$loan_code_latest'");
						//echo "select distinct msg from loan_application where loan_code = '$loan_code_latest'";
						while ($row = mysql_fetch_array($sql))
						{
							$msg = $row['msg'];
						}
			
						//echo $msg."<br />";
			
						if($msg == '0'){				
							$message_text_loan_sent = "We have disbursed ".number_format($loan_amount, 0).". Your due date is ".$loan_due_date." and the amount due is ".number_format($loan_total_interest, 0).". Your loan ref: ".$loan_code_latest.". Kopa UPIA, kuza biashara.";
				
							$sql8="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
							VALUES('$loan_code_latest', '$user_id', '$dis_phone', '$message_text_loan_sent', '0', '1', '$transactiontime')";
							//$result = mysql_query($sql8);

							$sql9="update loan_application set msg='1' WHERE loan_code  = '$loan_code_latest'";
							//$result = mysql_query($sql9);
				
						}
						$loan_code_latest = $loan_code_latest + 1;
						$sql20="update loan_code set id='$loan_code_latest'";
						//$result = mysql_query($sql20);
					}
					else{
						$message_text = "Dear Member, you do not qualify for the specified loan amount of KES $what.";
					}
				}
				else if($what == 'Upia'){
					if($user_id == ''){
						$message_text = "Dear customer, you are not a registered member. Kindly visit any of our branches for registration.";
					}
					else if($calculated_loan_balance != '0'){
						if($loan_status == '10'){
							$message_text = "Dear $first_name, the loan you applied for is being processed. Please be patient.";
						}
						else{
							$message_text = "Dear $first_name, you do not qualify for a new loan until you pay back your current loan.";
						}
					}
					else if($calculated_loan_balance == '0'){
						$sql2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$user_id'");
						while ($row = mysql_fetch_array($sql2))
						{
							$loan_count = $row['loan_count'];
						}
			
						if($affordability >= 5000){
							if($loan_count == 0){
								$message_text = "Dear $first_name, kindly visit any of our branches for so that your loan can be processed.";
							}
							else{
								if($loan_amount == '5000'){
									$message_text = "Dear $first_name, you qualify for a new loan. Please enter a 5000 for your loan to be processed.";
								}
								else{
									$message_text = "Dear $first_name, you qualify for a new loan. Please enter a loan value between 5000 and $loan_amount in multiples of 2500 eg 5000; 7500; 10000 etc";
								}
							}	
						}
						else{
							$message_text = "Dear $first_name, there is a problem with your application, please visit the branch for your loan to be processed.";
						}
					}
				}
				else if($what == 'Subscribe'){
					$message_text = "Dear customer, thank you for subscribing to 4G CAPITAL. Please SMS the words UPIA to 20142 to get your loan processed.";
				}
				else if($what == 'Unsubscribe'){
					$message_text = "Dear customer, we are sorry to see you go. Please send the words UPIA to 20142 to get repeat loans processed faster.";
				}
				else{
					$sql = mysql_query("select mobile_no, dis_phone, alt_phone from users where mobile_no = '$who' or dis_phone = '$who' or alt_phone = '$who'");
					while ($row = mysql_fetch_array($sql))
					{
						$mobile_no = $row['mobile_no'];
						$dis_phone = $row['dis_phone'];
						$alt_phone = $row['alt_phone'];
					}
					if($mobile_no == $who || $dis_phone == $who || $alt_phone == $who){
						$message_text = "Dear customer, you sent the wrong keyword/amount, please send the words UPIA to 20142.";
					}
					else{
						$message_text = "Dear customer, you are not a registered member. Kindly visit any of our branches for registration.";
					}
		
				}
	
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$user_id', '$who', '$message_text', '0', '1', '$transactiontime')";
				echo $message_text."<br />";
	
				//$result = mysql_query($sql6);
			}
		}
		else{
			$message_text = "Dear customer, Kindly note that repeat loans via SMS havent been activated for your branch. Kindly visit the branch for disbursement.";
	
			$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
			VALUES('$loan_code', '$user_id', '$who', '$message_text', '0', '1', '$transactiontime')";
			//$result = mysql_query($sql6);
		
			//echo $sql6."<br />";	
			echo $message_text."<br />";
		}
	}
	else{
		$message_text = "Dear customer, please note we do not disburse before and after normal hours, during weekends and on holidays.";
	
		$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
		VALUES('$loan_code', '$user_id', '$who', '$message_text', '0', '1', '$transactiontime')";
		//$result = mysql_query($sql6);
		
		//echo $sql6."<br />";	
		echo $message_text."<br />";
	}

	
?>
