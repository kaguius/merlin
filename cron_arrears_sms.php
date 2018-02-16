<?php
//include_once('includes/header.php');
include_once('includes/db_conn.php');

$filter_month = date("m");
$filter_year = date("Y");
$filter_day = date("d");
$current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
$transactiontime = date("Y-m-d G:i:s");
?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
	 <thead bgcolor="#E6EEEE">
	  <tr>
	   <th>Customer</th>
	   <th>Phone</th>
	   <th>Loan Code</th>
	   <th>Loan Date</th>
	   <th>Loan Due Date</th>
	   <th>Status</th>
	   <th>Total</th>
	   <th>Payment</th>
	   <th>Balance</th>
	  </tr>
	 </thead>
	 <tbody>
	     <?php
	     $sql5 = mysql_query("select distinct customer_id from loan_application order by customer_id");
	     while ($row = mysql_fetch_array($sql5)) {
		     $user_id = $row['customer_id'];
		     //echo $user_id;

		     $sql = mysql_query("select customer_id, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, waiver, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_total_interest, loan_date, loan_due_date, loan_code, loan_status, late_status, customer_state from loan_application where customer_id = '$user_id' and loan_status != '6' and loan_status != '8' and loan_status != '12' and loan_status != '11' and loan_status != '14' and loan_status != '13' and loan_status != '15' and loan_status != '10' and loan_date <= '2015-11-30' order by loan_date desc LIMIT 0,1");
		     while ($row = mysql_fetch_array($sql)) {
			     $customer_id = $row['customer_id'];
			     $loan_date = $row['loan_date'];
			     $loan_due_date = $row['loan_due_date'];
			     $loan_code = $row['loan_code'];
			     $loan_status = $row['loan_status'];
			     $current_loan_status = $loan_status;
			     $late_status = $row['late_status'];
			     $customer_state = $row['customer_state'];

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
			     $loan_total = $row['loan_total_interest'];

			     //$loan_total = $initiation_fee + $loan_amount + $loan_extension + $loan_interest + $loan_late_interest + $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
			     
			     $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
			     while ($row = mysql_fetch_array($sql2)) {
				     $repayments = $row['repayments'];
				     if ($repayments == "") {
				     	$repayments = 0;
				     }
			     }

			     $balance = $loan_total - $repayments;

			     $sql2 = mysql_query("select first_name, last_name, mobile_no, dis_phone, customer_state, affordability from users where id = '$customer_id'");
			     while ($row = mysql_fetch_array($sql2)) {
				     $first_name = $row['first_name'];
				     $last_name = $row['last_name'];
				     $first_name = ucwords(strtolower($first_name));
				     $last_name = ucwords(strtolower($last_name));
				     $name = $first_name . ' ' . $last_name;
				     $mobile_no = $row['mobile_no'];
				     $customer_state = $row['customer_state'];
			     }

			     $sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
			     while ($row = mysql_fetch_array($sql2)) {
				     $status_name = $row['status'];
				     $status_name = ucwords(strtolower($status_name));
			     }

			     if ($intcount % 2 == 0) {
			     	$display = '<tr bgcolor = #F0F0F6>';
			     } else {
			     	$display = '<tr>';
			     }

			     echo $display;
			     echo "<td valign='top'>$name</td>";
			     echo "<td valign='top'>$mobile_no</td>";
			     echo "<td valign='top'>$loan_code</td>";
			     echo "<td valign='top'>$loan_date</td>";
			     echo "<td valign='top'>$loan_due_date</td>";
			     echo "<td valign='top'>$status_name</td>";
			     echo "<td valign='top'>$customer_state</td>";
			     echo "<td align='right' valign='top'>" . number_format($loan_amount, 2) . "</td>";
			     echo "<td align='right' valign='top'>" . number_format($loan_total, 2) . "</td>";
			     echo "<td align='right' valign='top'>" . number_format($repayments, 2) . "</td>";
			     echo "<td align='right' valign='top'>" . number_format($balance, 2) . "</td>";
			     echo "</tr>";

			$loan_total = 0;
			$repayments = 0;
			$balance = 0;
			$status_name = "";
	}
}
?>
