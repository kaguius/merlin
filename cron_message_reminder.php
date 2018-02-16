<?php
	include_once('includes/db_conn.php');
	
	$transactiontime = date("Y-m-d G:i:s");

	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$sql = mysql_query("select distinct customer_id from loan_application order by loan_due_date desc");
	while ($row = mysql_fetch_array($sql))
	{
		$id = $row['customer_id'];

		$sql2 = mysql_query("select first_name from users where id = '$id'");
		while ($row = mysql_fetch_array($sql2))
		{
			$first_name = $row['first_name'];
			$first_name = strtoupper($first_name);
		}

		$sql3 = mysql_query("select loan_date, loan_due_date, loan_mobile, loan_code, loan_status, loan_total_interest from loan_application where customer_id = '$id' and loan_status != '1' and loan_status != '8' and loan_status != '10' and loan_status != '11' and loan_status != '12' and loan_status != '13' and loan_status != '14' and loan_status != '15' and loan_status != '16' order by loan_date desc limit 1");
		while ($row = mysql_fetch_array($sql3))
		{
			$loan_date = $row['loan_date'];
			$loan_due_date = $row['loan_due_date'];
			$loan_code = $row['loan_code'];
			$loan_mobile = $row['loan_mobile'];
			$loan_status = $row['loan_status'];
			$loan_total_interest = $row['loan_total_interest'];

			$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
			while ($row = mysql_fetch_array($sql2))
			{
				$repayments = $row['repayments'];
				if($repayments == ''){
					$repayments = 0;
				}
			}
	
			$loan_balance = $loan_total_interest - $repayments;
	
			$date1 = strtotime($loan_due_date);
			$date2 = strtotime($current_date);
			$dateDiff = $date1 - $date2;
			$date_diff = floor($dateDiff/(60*60*24));

			echo $date_diff." - ".$loan_status." - ".$loan_due_date." - ".$loan_code."<br />";

			//7 Day SMS Reminder
			if($date_diff == '7'  && $loan_status == '2'){
				$message_text = "Dear $first_name, this is to remind you that your loan falls due on $loan_due_date. Please pay KES. ".number_format($loan_balance, 0).". Your loan ref: ".$loan_code.".";

				echo $message_text."<br />";
				echo "Date Diff: 7<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}

			//48Hour SMS Remiender
			if($date_diff == '2'  && $loan_status == '2'){
				$message_text = "Dear $first_name, this is to remind you that your loan falls due on $loan_due_date. Please pay KES. ".number_format($loan_balance, 0).". Your loan ref: ".$loan_code.".";

				echo $message_text."<br />";
				echo "Date Diff: 2<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}
			
			//Due Date SMS Reminder
			if($date_diff == '0'  && $loan_status == '2'){
				$message_text = "Dear $first_name, this is to remind you that your loan falls due today. Please pay KES. ".number_format($loan_balance, 0).". Your loan ref: ".$loan_code.".";

				echo $message_text."<br />";
				echo "Date Diff: 0<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}

			//On Default SMS Reminder
			if($date_diff == '-1'  && ($loan_status == '2' || $loan_status == '3' || $loan_status == '4' || $loan_status == '5' || $loan_status == '7' || $loan_status == '3')){
				$message_text = "Dear $first_name, due to your lack/late repayment your balance is now KES. ".number_format($loan_balance, 0).". Your loan ref: ".$loan_code.", kindly pay to avoid extra charges";

				echo $message_text."<br />";
				echo "Date Diff: 1<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}
	
			//DD+7 SMS Remeinder
			if($date_diff == '-7'  && ($loan_status == '2' || $loan_status == '3' || $loan_status == '4' || $loan_status == '5' || $loan_status == '7' || $loan_status == '3')){
				$message_text = "Dear $first_name, we will call, text and visit your work and home until you pay your outstanding balance of KES. ".number_format($loan_balance, 0).". Your loan ref: ".$loan_code.".";

				echo $message_text."<br />";
				echo "Date Diff: 7<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}
	
			//DD+15 SMS Reminder
			if($date_diff == '-15'  && ($loan_status == '2' || $loan_status == '3' || $loan_status == '4' || $loan_status == '5' || $loan_status == '7' || $loan_status == '3')){
				$message_text = "Dear $first_name, you risk being reported to the CRB. Please pay outstanding balance of KES. ".number_format($loan_balance, 0).". Your loan ref: ".$loan_code.".";

				echo $message_text."<br />";
				echo "Date Diff: 15<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}

			//DD+31 SMS Reminder
			if($date_diff == '-31'  && ($loan_status == '2' || $loan_status == '3' || $loan_status == '4' || $loan_status == '5' || $loan_status == '7' || $loan_status == '3')){
				$message_text = "Your loan is about to marked as defaulted. When this happens, we will report you to the CRB. To avoid this, please pay KES. ".number_format($loan_balance, 0)." your loan ref: ".$loan_code.".";

				echo $message_text."<br />";
				echo "Date Diff: 31<br />";
				$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
				VALUES('$loan_code', '$id', '$loan_mobile', '$message_text', '1', '2', '$transactiontime')";
				$result = mysql_query($sql6);
				
			}
			$message_text = '';
			$loan_balance = 0;
			$repayments = 0;
			$loan_due_date = '';
		}
		
	}
?>
