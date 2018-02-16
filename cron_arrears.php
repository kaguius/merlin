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
   <th>Loan Date</th>
   <th>Loan Due Date</th>
   <th>Vintage</th>
   <th>Days</th>
   <th>Loan Code</th>
   <th>Loan Status</th>
   <th>current Status</th>
   <th>Status Name</th>
   <th>Late Status</th>
   <th>Late Status Name</th>
   <th>Loan Amount</th>
   <th>Payment</th>
   <th>Balance</th>
   <th>Interest</th>
   <th>Late Interest</th>
   <th>Loan Total</th>
   <th>State</th>
  </tr>
 </thead>
 <tbody>
     <?php
     $sql5 = mysql_query("select id from users order by id asc");
     while ($row = mysql_fetch_array($sql5)) {
     $user_id = $row['id'];
     //echo $user_id;

     $sql = mysql_query("select customer_id, customer_station, initiation_fee, loan_amount, loan_extension, loan_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_date, loan_due_date, loan_code, loan_status, late_status, customer_state from loan_application where customer_id = '$user_id' and loan_status != '6' and loan_status != '8' and loan_status != '12' and loan_status != '11' and loan_status != '14' and loan_status != '13' and loan_status != '15' and loan_status != '10' and loan_date > '2015-06-01' order by loan_date desc LIMIT 0,1");
     //$sql = mysql_query("select customer_id, initiation_fee, loan_amount, loan_extension, loan_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_date, loan_due_date, loan_code, loan_status, late_status, customer_state from loan_application where customer_id = '$user_id' and loan_status != '6' and loan_status != '8' and loan_status != '12' and loan_status != '11' and loan_status != '14' and loan_status != '13' and loan_status != '15' and loan_status != '10' and loan_date > '2014-11-01' order by loan_date desc LIMIT 0,1");
     while ($row = mysql_fetch_array($sql)) {
     $customer_id = $row['customer_id'];
     $customer_station = $row['customer_station'];
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

     $loan_total = $initiation_fee + $loan_amount + $loan_extension + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;

     $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$loan_code' group by loan_rep_code");
     while ($row = mysql_fetch_array($sql2)) {
		 $repayments = $row['repayments'];
		 if ($repayments == "") {
			 $repayments = 0;
		 }
     }

     $sql2 = mysql_query("select first_name, last_name, mobile_no, dis_phone, affordability from users where id = '$customer_id'");
     while ($row = mysql_fetch_array($sql2)) {
     $first_name = $row['first_name'];
     $last_name = $row['last_name'];
     $first_name = ucwords(strtolower($first_name));
     $last_name = ucwords(strtolower($last_name));
     $name = $first_name . ' ' . $last_name;
     $mobile_no = $row['mobile_no'];
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

     if ($Arrearsdays == "") {
     $Arrearsdays = 0;
     }

     if ($Arrearsdays <= 7) {
     $vintage = "CD 1";
     } else if ($Arrearsdays <= 14) {
     $vintage = "CD 2";
     } else if ($Arrearsdays <= 22) {
     $vintage = "CD 3";
     } else if ($Arrearsdays <= 30) {
     $vintage = "CD 4";
     } else if ($Arrearsdays <= 37) {
     $vintage = "CD 5";
     } else if ($Arrearsdays <= 44) {
     $vintage = "CD 6";
     } else if ($Arrearsdays <= 51) {
     $vintage = "CD 7";
     } else if ($Arrearsdays <= 58) {
     $vintage = "CD 8";
     } else if ($Arrearsdays <= 65) {
     $vintage = "CD 9";
     } else if ($Arrearsdays <= 72) {
     $vintage = "CD 10";
     } else if ($Arrearsdays <= 79) {
     $vintage = "CD 11";
     } else if ($Arrearsdays <= 86) {
     $vintage = "CD 12";
     } else if ($Arrearsdays <= 93) {
     $vintage = "CD 13";
     } else if ($Arrearsdays <= 100) {
     $vintage = "CD 14";
     } else if ($Arrearsdays <= 106) {
     $vintage = "CD 15";
     } else if ($Arrearsdays <= 113) {
     $vintage = "CD 16";
     } else if ($Arrearsdays <= 120) {
     $vintage = "CD 17";
     } else if ($Arrearsdays > 120) {
     $vintage = "CD 18";
     }

     //$loan_late_interest = 0; 

     if ($Arrearsdays > 0 && $loan_balance > 0) {
     if ($Arrearsdays <= '14') {
     $interest_levied = $loan_amount * (5 / 100);
     $loan_late_interest = $interest_levied; 
    // $loan_late_interest = $loan_late_interest + $interest_levied;
     $latest_loan = $loan_total + $loan_late_interest;
     //echo "Should be here1<br />";
     } else if ($Arrearsdays > '14' && $Arrearsdays <= '30') {
     $interest_levied = $loan_amount * (13 / 100);
    $loan_late_interest = $interest_levied; 
    // $loan_late_interest = $loan_late_interest + $interest_levied;
     $latest_loan = $loan_total + $loan_late_interest;
     //echo "Should be here2<br />";
     } else if ($Arrearsdays > '30') {
     $interest_levied = $loan_amount * (28 / 100);
     $loan_late_interest = $interest_levied; 
    // $loan_late_interest = $loan_late_interest + $interest_levied;
     $latest_loan = $loan_total + $loan_late_interest;
     //echo "Should be here3<br />";
     }
     //else if($Arrearsdays > 59 && $Arrearsdays < 121){
     //	$interest_levied = $loan_balance * (10/100);
     //	$loan_late_interest = $loan_late_interest + $interest_levied;
     //	$latest_loan = $loan_total + $loan_late_interest;
     //echo "Should be here3<br />";
     //}
     //	else {
     //	$loan_late_interest = $loan_late_interest;
     //	$latest_loan = $loan_total + $loan_late_interest;
     //	}

     //$customer_state = 'BFC';

	if($customer_station == '5' || $customer_station == '9' || $customer_station == '17' || $customer_station == '13'){
		if ($customer_state == 'BLC' || $customer_state == '') {
			 if ($Arrearsdays <= 14) {
				 $late_status = '1';
				 $loan_status = '4';
				 $late_status_name = 'Branch';
			 } else if ($Arrearsdays <= 29) {
				 $late_status = '2';
				 $loan_status = '5';
				 $late_status_name = 'Collections Cell';
			 } else if ($Arrearsdays >= 30) {
				 $late_status = '6';
				 $loan_status = '7';
				 $late_status_name = 'Write Off';
				 $customer_state = 'BFC';
			 }
		} else if ($customer_state == 'BFC') {
			 if ($Arrearsdays <= 14) {
				 $late_status = '1';
				 $loan_status = '4';
				 $late_status_name = 'Branch';
				 $customer_state = 'BFC';
			 } else if ($Arrearsdays <= 29) {
				 $late_status = '2';
				 $loan_status = '5';
				 $late_status_name = 'Collections Cell';
				 $customer_state = 'BFC';
			 } else if ($Arrearsdays >= 30) {
				 $late_status = '6';
				 $loan_status = '7';
				 $late_status_name = 'Write Off';
				 $customer_state = 'FA';
			 }
		}
	}
	else{
		if ($customer_state == 'BLC' || $customer_state == '') {
			 if ($Arrearsdays <= 7) {
				 $late_status = '1';
				 $loan_status = '4';
				 $late_status_name = 'Branch';
			 } else if ($Arrearsdays <= 29) {
				 $late_status = '2';
				 $loan_status = '5';
				 $late_status_name = 'Collections Cell';
			 } else if ($Arrearsdays >= 30) {
				 $late_status = '6';
				 $loan_status = '7';
				 $late_status_name = 'Write Off';
				 $customer_state = 'FA';
			 } 
		} else if ($customer_state == 'BFC') {
			 if ($Arrearsdays <= 7) {
				 $late_status = '1';
				 $loan_status = '4';
				 $late_status_name = 'Branch';
			 } else if ($Arrearsdays <= 14) {
				 $late_status = '2';
				 $loan_status = '5';
				 $late_status_name = 'Collections Cell';
				 $customer_state = 'BFC';
			 } else if ($Arrearsdays >= 30) {
				 $late_status = '6';
				 $loan_status = '7';
				 $late_status_name = 'Write Off';
				 $customer_state = 'FA';
			 }
		}
	}

     $sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
     while ($row = mysql_fetch_array($sql2)) {
     $status_name = $row['status'];
     $status_name = ucwords(strtolower($status_name));
     }

     $sql2 = mysql_query("select status from customer_status where id = '$current_loan_status'");
     while ($row = mysql_fetch_array($sql2)) {
     $current_status_name = $row['status'];
     $current_status_name = ucwords(strtolower($current_status_name));
     }

     if ($intcount % 2 == 0) {
     $display = '<tr bgcolor = #F0F0F6>';
     } else {
     $display = '<tr>';
     }
     echo $display;
     echo "<td valign='top'>$name</td>";
     echo "<td valign='top'>$mobile_no</td>";
     echo "<td valign='top'>$loan_date</td>";
     echo "<td valign='top'>$loan_due_date</td>";
     echo "<td valign='top'>$vintage</td>";
     echo "<td valign='top'>$Arrearsdays</td>";
     echo "<td valign='top'>$loan_code</td>";
     echo "<td valign='top'>$loan_status</td>";
     echo "<td valign='top'>$current_status_name</td>";
     echo "<td valign='top'>$status_name</td>";
     echo "<td valign='top'>$late_status</td>";
     echo "<td valign='top'>$late_status_name</td>";
     echo "<td align='right' valign='top'>" . number_format($loan_total, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($repayments, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($loan_balance, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($interest_levied, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($loan_late_interest, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($latest_loan, 2) . "</td>";
     echo "<td valign='top'>$customer_state</td>";
     echo "</tr>";
     echo "<tr>";
     //Include the loan_status and late_status change in the query below.
     $sql7 = "update loan_application set loan_late_interest='$loan_late_interest', loan_status = '$loan_status', late_status = '$late_status', loan_total_interest = '$latest_loan', vintage = '$vintage', arreardays = '$Arrearsdays', customer_state = '$customer_state' WHERE loan_code  = '$loan_code'";
     //echo "<td valign='top' colspan='16'>$sql7</td>";
     //echo $sql7."<br />";
     //$result = mysql_query($sql7);
     echo "</tr>";

     //$loan_total = 0;
     //$repayments = 0;
     //$loan_balance = 0;
     //$loan_late_interest = 0;
     //$latest_loan = 0;
     //$interest_levied = 0;
     } else if ($Arrearsdays > 0 && $loan_balance <= 0) {
     $interest_levied = $loan_balance * (0 / 100);
     $loan_late_interest = $loan_late_interest + $interest_levied;
     $latest_loan = $loan_total + $loan_late_interest;
     //echo "Should be here3<br />";

     $late_status = '4';
     $loan_status = '13';
     $late_status_name = 'Calls';

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
     echo "<td valign='top'>$loan_date</td>";
     echo "<td valign='top'>$loan_due_date</td>";
     echo "<td valign='top'>$vintage</td>";
     echo "<td valign='top'>$Arrearsdays</td>";
     echo "<td valign='top'>$loan_code</td>";
     echo "<td valign='top'>$loan_status</td>";
     echo "<td valign='top'>$current_loan_status</td>";
     echo "<td valign='top'>$status_name</td>";
     echo "<td valign='top'>$late_status</td>";
     echo "<td valign='top'>$late_status_name</td>";
     echo "<td align='right' valign='top'>" . number_format($loan_total, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($repayments, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($loan_balance, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($interest_levied, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($loan_late_interest, 2) . "</td>";
     echo "<td align='right' valign='top'>" . number_format($latest_loan, 2) . "</td>";
     echo "<td valign='top'>$customer_state</td>";
     echo "</tr>";
     echo "<tr>";
     //update the loan status and late status in the query below
     $sql7 = "update loan_application set loan_late_interest='$loan_late_interest', loan_status = '$loan_status', late_status='$late_status', loan_total_interest = '$latest_loan', vintage = '$vintage', arreardays = '$Arrearsdays', customer_state = '$customer_state' WHERE loan_code  = '$loan_code'";
     //echo "<td valign='top' colspan='16'>$sql7</td>";
     //echo $sql7."<br />";
     //$result = mysql_query($sql7);
     echo "</tr>";

     $loan_total = 0;
     $repayments = 0;
     $loan_balance = 0;
     $loan_late_interest = 0;
     $latest_loan = 0;
     $interest_levied = 0;
     } else {
     if ($intcount % 2 == 0) {
     $display = '<tr bgcolor = #F0F0F6>';
     } else {
     $display = '<tr>';
     }
     echo $display;
     echo "<td valign='top'>$name</td>";
     echo "<td valign='top'>$mobile_no</td>";
     echo "<td valign='top'>$loan_date</td>";
     echo "<td valign='top'>$loan_due_date</td>";
     echo "<td valign='top' colspan='15'>Loan Does not qualify</td>";
     echo "</tr>";
     }

     $loan_total = 0;
     $repayments = 0;
     $loan_balance = 0;
     $loan_late_interest = 0;
     $latest_loan = 0;
     $interest_levied = 0;
     }
     }
     $sql14 = "insert into cron_jobs(cron_job, transactiontime)values('cron_arrears', '$transactiontime')";
     //echo $sql14."<br />";
     $result = mysql_query($sql14);
     ?>
 </tbody>
</table>
