<?php

//include_once('includes/header.php');
//Recieves notifications on payments made to the system
//updates the loan_repayments table
include_once('includes/db_conn.php');
include_once('classes/clear_loan.php');

$ln_rep = new clear_loan();

$transactiontime = date("Y-m-d G:i:s");

$filter_month = date("m");
$filter_year = date("Y");
$filter_day = date("d");
$current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;

if (!empty($_GET)) {
    $loan_rep_date = $_GET['loan_date'];
    $loan_rep_mobile = $_GET['msisdn'];
    $loan_rep_amount = $_GET['amount'];
    $loan_rep_code = $_GET['loan_code'];
    $loan_rep_mpesa_code = $_GET['mpesa_code'];
    $loan_rep_mpesa_code = trim($loan_rep_mpesa_code);
}

echo $loan_rep_date . " - " . $loan_rep_mobile . " - " . $loan_rep_amount . " - " . $loan_rep_code . " - " . $loan_rep_mpesa_code;
echo "<br />";

$sql3 = "INSERT INTO incoming_safaricom_payments (loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_code, loan_rep_mpesa_code, transactiontime) VALUES ('$loan_rep_date', '$loan_rep_mobile', '$loan_rep_amount', '$loan_rep_code', '$loan_rep_mpesa_code', '$transactiontime')";
echo $sql3 . "<br />";
$result = mysql_query($sql3);

$sql = mysql_query("select customer_id, customer_station, current_collector from loan_application where loan_code = '$loan_rep_code'");
while ($row = mysql_fetch_array($sql)) {
    $customer_id = $row['customer_id'];
    $customer_station = $row['customer_station'];
    $current_collector = $row['current_collector'];
}
echo $customer_id . " - " . $customer_station . " - " . $current_collector;
echo "<br />";

if ($customer_id != "") {
    $sql4 = mysql_query("select loan_rep_mpesa_code from loan_repayments where loan_rep_mpesa_code = '$loan_rep_mpesa_code'");
    while ($row = mysql_fetch_array($sql4)) {
        $exists_loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
    }

    if ($exists_loan_rep_mpesa_code != $loan_rep_mpesa_code) {
        $sql3 = "INSERT INTO loan_repayments (loan_rep_date, customer_id, customer_station, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, current_collector)
	VALUES('$loan_rep_date', '$customer_id', '$customer_station', '$loan_rep_mobile', '$loan_rep_amount', '$loan_rep_mpesa_code', '$loan_rep_code', '$current_collector');";

        //echo $sql3."<br />";
        $result = mysql_query($sql3);

	$sql3 = mysql_query("select mobile_no from users where id = '$customer_id'");
    	while ($row = mysql_fetch_array($sql3)) {
        	$mobile_no = $row['mobile_no'];
	}

	$message_text = "Dear customer, your payment of KES ".number_format($loan_rep_amount, 0)." has been received. Payment Ref: $loan_rep_mpesa_code. For more info 0205144099";

	echo $message_text."<br />";
	$sql6="INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
	VALUES('$loan_rep_code', '$customer_id', '$mobile_no', '$message_text', '1', '2', '$transactiontime')";
	$result = mysql_query($sql6);
    }

    $sql3 = mysql_query("select loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status, initiation_fee, loan_extension from loan_application where customer_id = '$customer_id' and loan_code = '$loan_rep_code'");
    while ($row = mysql_fetch_array($sql3)) {
        $current_loan = $row['loan_amount'];
        $loan_date = $row['loan_date'];
        $latest_loan = $row['loan_total_interest'];
        $latest_loan_code = $row['loan_code'];
        $loan_due_date = $row['loan_due_date'];
        $initiation_fee = $row['initiation_fee'];
        $loan_extension = $row['loan_extension'];
    }

    $sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$loan_rep_code' group by loan_rep_code");
    while ($row = mysql_fetch_array($sql4)) {
        $repayments = $row['repayments'];
        if ($repayments == "") {
            $repayments = 0;
        }
    }

    //$balance = $latest_loan - $repayments;
    //$loan_balance = $balance * -1;

    $sql5 = mysql_query("select loan_rep_date from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$loan_rep_code' order by loan_rep_date desc limit 1");
    while ($row = mysql_fetch_array($sql5)) {
        $latest_loan_rep_date = $row['loan_rep_date'];
    }

    $date1 = strtotime($loan_date);
    $date2 = strtotime($latest_loan_rep_date);
    $dateDiff = $date2 - $date1;
    $due_days = floor($dateDiff / (60 * 60 * 24));

    if ($due_days < 14) {
        $due_days = 14;
    } else if ($due_days > 30) {
        $due_days = 14;
    } else {
        $due_days = $due_days;
    }

    $due_loan_interest = $current_loan * ($due_days / 100);
    $due_loan_total_interest = $due_loan_interest + $current_loan + $initiation_fee + $loan_extension;

    echo $latest_loan_rep_date . "<br />";
    echo $due_days . "<br />";
    echo $due_loan_total_interest . "<br />";
    echo $loan_rep_amount . "<br />";
    echo $latest_loan . "<br />";
    
        // Mobile survey Code 
//    $ln_bal = $ln_rep->get_loan_details($loan_rep_code);
//    if ($ln_bal <= 0) {
//        $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
//    }
    
    include_once('classes/mobile_survey.php');

    if ($due_loan_total_interest == $repayments) {
        $early_settlement = $latest_loan - $repayments;
        $latest_loan = $latest_loan - $early_settlement;
        $sql6 = "update loan_application set loan_status='13' WHERE loan_code  = '$loan_rep_code'";
        $result = mysql_query($sql5);
        
        $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
        
        //echo $sql6."<br />";
        if ($early_settlement > 0) {
            $early_settlement = -$early_settlement;
            $sql7 = "update loan_application set early_settlement='$early_settlement', loan_total_interest = '$latest_loan' WHERE loan_code  = '$loan_rep_code'";
            $result = mysql_query($sql7);
            //echo $sql7."<br />";
        }
    } else if ($repayments >= $latest_loan) {
        $early_settlement_surplus = $repayments - $latest_loan;
        $latest_loan = $latest_loan - $early_settlement_surplus;
        $sql5 = "update loan_application set loan_status='13' WHERE loan_code  = '$loan_rep_code'";
        $result = mysql_query($sql5);
        $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
        if ($early_settlement_surplus > 0) {
            $early_settlement_surplus_figure = -$early_settlement_surplus;
            $sql15 = "update overpayments_schedule set loan_balance = '$early_settlement_surplus' WHERE loan_code  = '$loan_rep_code'";
            $result = mysql_query($sql15);
            //echo $sql1."<br />";
        }
    }
} else {
    $sql4 = mysql_query("select receipt from suspence_accounts where receipt = '$loan_rep_mpesa_code'");
    while ($row = mysql_fetch_array($sql4)) {
        $exists_receipt = $row['receipt'];
    }

    if ($exists_receipt != $loan_rep_mpesa_code) {
        $loan_rep_mobile = substr($loan_rep_mobile, -9);
        $loan_rep_mobile = "0" . $loan_rep_mobile . " - ";
        $sql3 = "INSERT INTO suspence_accounts(receipt, date, paid_in, other_party_info, trans_party_details)
	VALUES('$loan_rep_mpesa_code', '$loan_rep_date', '$loan_rep_amount', '$loan_rep_mobile', '$loan_rep_code')";

        //echo $sql3."<br />";
        $result = mysql_query($sql3);
    }
}
?>
