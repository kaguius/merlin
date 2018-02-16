<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$sql3 = mysql_query("select loan_code, loan_mobile, loan_total_interest from loan_application where loan_status = '2' order by loan_code asc");
	while ($row = mysql_fetch_array($sql3))
	{
		$loan_code = $row['loan_code'];
		$loan_mobile = $row['loan_mobile'];
		$loan_amount = $row['loan_total_interest'];
		
		$sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
		while ($row = mysql_fetch_array($sql4))
		{
			$repayments = $row['repayments'];
			if($repayments == ""){
				$repayments = 0;
			}
		}
		$balance = $loan_amount - $repayments;
		//echo "$loan_mobile | $loan_code | $loan_amount | $repayments | $balance<br />";
		
		//$sql4="insert into test_repayments(loan_mobile, loan_code, loan_amount, repayments, balance)values('$loan_mobile', '$loan_code', '$loan_amount', '$repayments', '$balance')";
		//$result = mysql_query($sql4);
		
		if($balance == 0){
			$sql16="update loan_application set loan_status='13' WHERE loan_code  = '$loan_code'";
					
			//echo $sql16."<br />";  
			//$result = mysql_query($sql16);
		}
		else if($balance < 0){
			$sql16="update loan_application set loan_status='13', early_settlement = '$balance' WHERE loan_code  = '$loan_code'";
					
			//echo $sql16."<br />";  
			//$result = mysql_query($sql16);
		}
		
		$loan_mobile = "";
		$loan_code = 0;
		$loan_amount = 0;
		$repayments = 0;
	}
	
	$sql10 = mysql_query("select distinct customer_id, count(loan_id)counts, loan_code, loan_mobile from loan_application group by customer_id order by counts desc");
	while ($row = mysql_fetch_array($sql10))
	{
		$customer_id = $row['customer_id'];
		$counts = $row['counts'];
		$loan_code = $row['loan_code'];
		$loan_mobile = $row['loan_mobile'];
		
		$sql11 = mysql_query("select loan_date, loan_due_date, loan_code, loan_status, loan_mpesa_code, loan_mobile, loan_amount, loan_total_interest from loan_application where customer_id = '$customer_id' and loan_status = '2' order by loan_date desc LIMIT 1,$counts");
		while ($row = mysql_fetch_array($sql11))
		{
			$loan_date = $row['loan_date'];
			$loan_due_date = $row['loan_due_date'];
			$loan_code = $row['loan_code'];
			$loan_status = $row['loan_status'];
			$loan_mpesa_code = $row['loan_mpesa_code'];
			$loan_mobile = $row['loan_mobile'];
			$loan_amount = $row['loan_amount'];
			$loan_total_interest = $row['loan_total_interest'];
			echo "$customer_id | $loan_status_for_update | $loan_code_for_update | $loan_total_interest_for_update<br />";
			
			$sql16 = "update loan_application set loan_status = '13' WHERE loan_code = '$loan_code_for_update'";
					
			//echo $sql16."<br />";  
			//$result = mysql_query($sql16);
		}
	}
	
?>
