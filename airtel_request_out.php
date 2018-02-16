<?php
	//include_once('includes/header.php');
	//Recieve updates from the mpesa system based on the loan code
	//Statuses: 0=suucessful, 1=queued, 2=failed
	include_once('includes/db_conn.php');
	
	$transactiontime = date("Y-m-d G:i:s");
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;

	if (!empty($_GET)){	
		$loan_code = $_GET['loan_code'];
		$status_code = $_GET['status_code'];
		$ref_id = $_GET['ref_id'];
		$server_result = $_GET['result'];
		$loan_date = $_GET['date'];
		$loan_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_date)));
	}
	
	$days = 30;
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
	else{
		$loan_term = $days;
		$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
	}
	
	echo $loan_code." - ".$status_code." - ".$ref_id." - ".$loan_date." - ".$loan_due_date;
	echo "<br />";
	
	$sql = mysql_query("select loan_code from incoming_airtel_response where loan_code = 'loan_code'");
	while ($row = mysql_fetch_array($sql))
	{
		$exists_loan_code = $row['loan_code'];
	}
	
	if($exists_loan_code == $loan_code){
		$sql3="update incoming_airtel_response set loan_date = '$loan_date', status_code='$status_code', ref_id='$ref_id', transactiontime = '$transactiontime', result= '$server_result' WHERE loan_code  = '$loan_code'";
		//echo $sql3."<br />";
		$result = mysql_query($sql3);
	}
	else{
		$sql3="INSERT INTO incoming_airtel_response (loan_date, loan_code, status_code, ref_id, result, transactiontime) VALUES ('$loan_date', '$loan_code', '$status_code', '$ref_id', '$server_result', '$transactiontime')";
		//echo $sql3."<br />";
		$result = mysql_query($sql3);
	}
	
	if($status_code == '0'){
		$sql5="update mobile_money_requests set payment_status='$status_code', result = '$server_result' WHERE loan_code  = '$loan_code'";
		$result = mysql_query($sql5);
		
		$sql6="update loan_application set loan_date = '$loan_date', loan_due_date='$loan_due_date', loan_mpesa_code='$ref_id', loan_status = '2', loan_failure_status = '$status_code' WHERE loan_code  = '$loan_code'";
		$result = mysql_query($sql6);
		
		$sql = mysql_query("select loan_amount, loan_due_date, loan_total_interest, loan_code, customer_id, customer_station, msg from loan_application where loan_code = '$loan_code'");
		while ($row = mysql_fetch_array($sql))
		{
			$loan_code_latest = $row['loan_code'];
			$msg = $row['msg'];
			$customer_station = $row['customer_station'];
			$customer_id = $row['customer_id'];
			$loan_amount = $row['loan_amount'];
			$loan_due_date = $row['loan_due_date'];
			$loan_total_interest = $row['loan_total_interest'];
		}
		
		$sql = mysql_query("select dis_phone, mobile_no from users where id = '$customer_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$dis_phone = $row['dis_phone'];
			$mobile_no = $row['mobile_no'];
		}
		
		if($msg == '0'){				
			$message_text = "Thanks for choosing UPIA. We have disbursed ".number_format($loan_amount, 0).". Your due date is ".$loan_due_date." and the amount due is ".number_format($loan_total_interest, 0).". Your loan ref: ".$loan_code.". Kopa UPIA, kuza biashara.";
			
			if($dis_phone == $mobile_no){
				$sql5="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code_latest', '$customer_id', '$dis_phone', '$message_text', '0', '1', '$transactiontime')";
				//echo $sql5."<br />";
				$result = mysql_query($sql5);
			}
			else{
				$sql5="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code_latest', '$customer_id', '$dis_phone', '$message_text', '0', '1', '$transactiontime')";
				$result = mysql_query($sql5);
				//echo $sql5."<br />";
		
				$sql7="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code_latest', '$customer_id', '$mobile_no', '$message_text', '0', '1', '$transactiontime')";	
				$result = mysql_query($sql7);	
				//echo $sql7."<br />";	
			}
	
			$sql6="update loan_application set msg='1' WHERE loan_code  = '$loan_code'";
			$result = mysql_query($sql6);
			//echo $sql6."<br />";	
		}
	}
	else{
		$sql5="update mobile_money_requests set payment_status='$status_code', result = '$server_result' WHERE loan_code  = '$loan_code'";
		$result = mysql_query($sql5);
		$sql6="update loan_application set loan_failure_status = '$status_code' WHERE loan_code  = '$loan_code'";
		$result = mysql_query($sql6);
	}
	
?>