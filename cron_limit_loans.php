<?php
	 include_once('includes/db_conn.php');
	
	 $sql = mysql_query("select loan_total_interest, loan_status, loan_code from loan_application where customer_id = '$user_id'");
	 $calculated_loan_total_interest = 0;
	 $repayments_1 = 0;
	 $calculated_total_repayments = 0;
	 $limit_repayments = 0;
	 while ($row = mysql_fetch_array($sql))
	 {
		$loan_total_interest_1 = $row['loan_total_interest'];
		$loan_status = $row['loan_status'];
		$loan_code = $row['loan_code'];

		$sql2 = mysql_query("select sum(loan_rep_amount)limit_repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
		$limit_repayments = 0;
		while ($row = mysql_fetch_array($sql2))
		{
			$limit_repayments = $row['limit_repayments'];
		}
	
		
		if($loan_status == '12' || $loan_status == '11' || $loan_status == '14' || $loan_status == '15' || $loan_status == '18'){
			$loan_total_interest_1 = 0;
			$limit_repayments = 0;
			$calculated_loan_total_interest = $calculated_loan_total_interest + $loan_total_interest_1;
			$calculated_total_repayments = $calculated_total_repayments + $limit_repayments;
		}
		else{
			$calculated_loan_total_interest = $calculated_loan_total_interest + $loan_total_interest_1;
			$calculated_total_repayments = $calculated_total_repayments + $limit_repayments;
		}
	}
	$calculated_loan_balance = $calculated_loan_total_interest - $calculated_total_repayments;
?>