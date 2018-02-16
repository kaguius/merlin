<?php

//include_once('includes/header.php');
//Receives notifications on Airtel payments made to the system
//and then updates the loan_repayments table
include_once('includes/db_conn.php');
include_once('classes/clear_loan.php');
include_once('classes/mobile_survey.php');

$ln_rep = new clear_loan();

$transactiontime = date("Y-m-d G:i:s");

$filter_month = date("m");
$filter_year = date("Y");
$filter_day = date("d");
$current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;

if ($_GET) {
    $loan_rep_date = trim(htmlspecialchars($_GET['loan_date']));
    $loan_rep_mobile = trim(htmlspecialchars($_GET['msisdn']));
    $loan_rep_amount = trim(htmlspecialchars($_GET['amount']));
    $loan_rep_code = trim(htmlspecialchars($_GET['loan_code']));
    $loan_rep_ref_id = trim(htmlspecialchars($_GET['ref_id']));
}

echo $loan_rep_date . " - " . $loan_rep_mobile . " - " . $loan_rep_amount 
        . " - " . $loan_rep_code . " - " . $loan_rep_ref_id;
echo "<br />";

$sql3 = "INSERT INTO incoming_airtel_payments (loan_rep_date, loan_rep_mobile, loan_rep_amount,"
        . " loan_rep_code, loan_rep_airtel_refid, transactiontime) "
        . "VALUES ('$loan_rep_date', '$loan_rep_mobile', '$loan_rep_amount', '$loan_rep_code',"
        . " '$loan_rep_ref_id', '$transactiontime')";

echo $sql3 . "<br />";
$result = mysql_query($sql3);

$sql = mysql_query("select customer_id, customer_station, current_collector from loan_application "
        . "where loan_code = '$loan_rep_code'");
while ($row = mysql_fetch_array($sql)) {
    $customer_id = $row['customer_id'];
    $customer_station = $row['customer_station'];
    $current_collector = $row['current_collector'];
}
echo $customer_id . " - " . $customer_station . " - " . $current_collector;
echo "<br />";

if ($customer_id != "") {
    $sql4 = mysql_query("select loan_rep_mpesa_code from loan_repayments where loan_rep_mpesa_code = '$loan_rep_ref_id'");
    while ($row = mysql_fetch_array($sql4)) {
        $exists_loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
    }

    if ($exists_loan_rep_mpesa_code != $loan_rep_ref_id) {
        $sql3 = "INSERT INTO loan_repayments (loan_rep_date, customer_id, customer_station, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, current_collector)
			VALUES('$loan_rep_date', '$customer_id', '$customer_station', '$loan_rep_mobile', '$loan_rep_amount', '$loan_rep_ref_id', '$loan_rep_code', '$current_collector');";

        //echo $sql3."<br />";
        $result = mysql_query($sql3);
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

    //Mobile survey request code


    echo $latest_loan_rep_date . "<br />";
    echo $due_days . "<br />";
    echo $due_loan_total_interest . "<br />";
    echo $loan_rep_amount . "<br />";
    echo $latest_loan . "<br />";

    // Mobile survey Code 
    $ln_bal = $ln_rep->get_loan_details($loan_rep_code);
    if ($ln_bal <= 0) {
        $data = getSurvey('$loan_rep_mobile', 'airtel', '4GLOAN', '111');
    }

    if ($due_loan_total_interest == $repayments) {
        $early_settlement = $latest_loan - $repayments;
        $latest_loan = $latest_loan - $early_settlement;
        $sql6 = "update loan_application set loan_status='13' WHERE loan_code  = '$loan_rep_code'";
        $result = mysql_query($sql5);
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
        if ($early_settlement_surplus > 0) {
            $early_settlement_surplus_figure = -$early_settlement_surplus;
            $sql15 = "update overpayments_schedule set loan_balance = '$early_settlement_surplus' WHERE loan_code  = '$loan_rep_code'";
            $result = mysql_query($sql15);
            //echo $sql1."<br />";
        }
    }
} else {
    $sql4 = mysql_query("select receipt from suspence_accounts where receipt = '$loan_rep_ref_id'");
    while ($row = mysql_fetch_array($sql4)) {
        $exists_receipt = $row['receipt'];
    }

    if ($exists_receipt != $loan_rep_ref_id) {
        $loan_rep_mobile = substr($loan_rep_mobile, -9);
        $loan_rep_mobile = "0" . $loan_rep_mobile . " - ";
        $sql3 = "INSERT INTO suspence_accounts(receipt, date, paid_in, other_party_info, trans_party_details)
			VALUES('$loan_rep_ref_id', '$loan_rep_date', '$loan_rep_amount', '$loan_rep_mobile', '$loan_rep_code')";

        //echo $sql3."<br />";
        $result = mysql_query($sql3);
    }
}
?>
