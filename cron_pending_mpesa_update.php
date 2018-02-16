<?php

/* This code is run by CRON to send disbursement requests for new loans to MPESA app */
//include_once('includes/header.php');
//cron job runs every second
//Sends requests to mpesa for payment
include_once('includes/db_conn.php');

$mpesa_user_name = "afb_client";
$mpesa_password = "F@.5671hD573";

echo "DEBUG: cron_pending_mpesa_update.php - start ...";

$sql = mysql_query("select id, loan_code, msisdn, amount, carrier from mobile_money_requests where new = '1' and loan_code != ''");
while ($row = mysql_fetch_array($sql)) {
    $id = $row['id'];
    $loan_code = $row['loan_code'];
    $msisdn = $row['msisdn'];
    $amount = $row['amount'];
    $carrier = $row['carrier'];

    $sql2 = "update mobile_money_requests set new='2' WHERE id  = '$id'";
    //echo $sql2."<br />";
    $result = mysql_query($sql2);

    if ($carrier == 1) {
        //$mobile_money_request = "http://172.16.16.3:28080/ApiApp/b2c?transaction_id=" . $loan_code . "&username=" . $mpesa_user_name . "&password=" . $mpesa_password . "&msisdn=" . $msisdn . "&amount=" . $amount;
        $mobile_money_request = "http://172.16.16.10:8230/afb/transact?transaction_id=" . $loan_code . "&username=" . $mpesa_user_name . "&password=" . $mpesa_password . "&msisdn=" . $msisdn . "&amount=" . $amount;
	echo $mobile_money_request . "<br />";
    } else if ($carrier == 2) {
        //$mobile_money_request = "http://172.16.16.10:8090/4g-airtel/transact?transaction_id=" . $loan_code . "&username=" . $mpesa_user_name . "&password=" . $mpesa_password . "&msisdn=" . $msisdn . "&amount=" . $amount;
        $mobile_money_request = "http://172.16.16.3:28080/ApiApp/b2c?transaction_id=" . $loan_code . "&username=" . $mpesa_user_name . "&password=" . $mpesa_password . "&msisdn=" . $msisdn . "&amount=" . $amount;
        
	// This is for testing
        //$mobile_money_request = "http://localhost:8080/4g-airtel/transact?transaction_id=" . $loan_code . "&username=" . $mpesa_user_name . "&password=" . $mpesa_password . "&msisdn=" . $msisdn . "&amount=" . $amount;
        
        echo $mobile_money_request . "<br />";
    }

    header("Location: $mobile_money_request");
    exit;
}
?>
