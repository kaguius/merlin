<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$transactiontime = date("Y-m-d G:i:s");
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$sql = mysql_query("select id, first_name, last_name, mobile_no, dis_phone, affordability from users where affordability > 0 order by id asc");
	while ($row = mysql_fetch_array($sql))
	{
		$user_id = $row['id'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$first_name = ucwords(strtolower($first_name));	
		$last_name = ucwords(strtolower($last_name));
		$name = $first_name.' '.$last_name;		
		$mobile_no = $row['mobile_no'];
		$dis_phone = $row['dis_phone'];
		$affordability = $row['affordability'];
		
		$sql3 = mysql_query("select loan_amount, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status, loan_status from loan_application where customer_id = '$user_id' order by loan_date desc limit 1");
		while ($row = mysql_fetch_array($sql3))
		{
			$current_loan = $row['loan_amount'];
			$latest_loan = $row['loan_total_interest'];
			$latest_loan_code = $row['loan_code'];
			$loan_due_date = $row['loan_due_date'];
			$loan_late_interest = $row['loan_late_interest'];
			$late_status = $row['late_status'];
			$last_loan_status = $row['loan_status'];
		}
		
		if($loan_due_date != ""){
			$loan_due_date = date('d/m/y', strtotime(str_replace('-', '/', $loan_due_date)));
			//echo $loan_due_date;
		}
		
		$sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where customer_id = '$user_id' and loan_rep_code = '$latest_loan_code' group by loan_rep_code");
		while ($row = mysql_fetch_array($sql4))
		{
			$repayments = $row['repayments'];
			if($repayments == ""){
				$repayments = 0;
			}
		}
	
		$balance = $latest_loan - $repayments;
	
		$date1 = strtotime($loan_due_date);
		$date2 = strtotime($current_date);
		$dateDiff = $date1 - $date2;
		$days = floor($dateDiff/(60*60*24));
		
		if($loan_due_date == ""){
			$days = 0;
		}
		
		if($last_loan_status == 13){
			$sql7 = mysql_query("select final_status from loan_application where loan_code = '$latest_loan_code'");
			while ($row = mysql_fetch_array($sql7))
			{
				$final_status = $row['final_status'];
			}
			if($final_status == '0'){
				$message_text = "Dear UPIA Member, thank you for the successful repayment. Kuza Upia, Kuza Biashara!!";
			
				$sql5="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$latest_loan_code', '$user_id', '$mobile_no', '$message_text', '0', '1', '$transactiontime')";
				//echo $sql5."<br />";
				//$result = mysql_query($sql5);
				
				$sql6="update loan_application set final_status='1' WHERE loan_code  = '$latest_loan_code'";
				//echo $sql6."<br />";
				//$result = mysql_query($sql6);
			}
		}
		else if($days == 2 && $last_loan_status == 2){
			$sql7 = mysql_query("select fortyeight_hr_status from loan_application where loan_code = '$latest_loan_code'");
			while ($row = mysql_fetch_array($sql7))
			{
				$fortyeight_hr_status = $row['fortyeight_hr_status'];
			}
			if($fortyeight_hr_status == '0'){
				$message_text = "Dear Member, this is to remind you that your loan falls due on ".$loan_due_date.". Please pay KES. ".number_format($balance, 0)." through Paybill number 992701. Your account number is ".$latest_loan_code."";
			
				$sql5="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$latest_loan_code', '$user_id', '$mobile_no', '$message_text', '0', '1', '$transactiontime')";
				//echo $sql5."<br />";
				//$result = mysql_query($sql5);
				
				$sql6="update loan_application set fortyeight_hr_status='1' WHERE loan_code  = '$latest_loan_code'";
				//echo $sql6."<br />";
				//$result = mysql_query($sql6);
			}
		}
		else if($days == 7 && $last_loan_status == 2){
			$sql7 = mysql_query("select seven_day_status from loan_application where loan_code = '$latest_loan_code'");
			while ($row = mysql_fetch_array($sql7))
			{
				$seven_day_status = $row['seven_day_status'];
			}
			if($seven_day_status == '0'){
				
				$message_text = "Dear Member, this is to remind you that your loan falls due on ".$loan_due_date.". Please pay KES. ".number_format($balance, 0)." through Paybill number 992701. Your account number is ".$latest_loan_code."";
			
				$sql5="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$latest_loan_code', '$user_id', '$mobile_no', '$message_text', '0', '1', '$transactiontime')";
				//echo $sql5."<br />";
				//$result = mysql_query($sql5);
				
				$sql6="update loan_application set seven_day_status='1' WHERE loan_code  = '$latest_loan_code'";
				//echo $sql6."<br />";
				//$result = mysql_query($sql6);
			}
		}
	}
	
	$sql14="insert into cron_jobs(cron_job, transactiontime)values('cron_notifications', '$transactiontime')";
	//echo $sql14."<br />";
	//$result = mysql_query($sql14);
	
	
?>
