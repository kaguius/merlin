<?php

include_once('../includes/db_conn.php');
include_once '../classes/clear_loan.php';

function getSurvey($msisdn, $domain, $survey, $customer_id) {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

//set POST variables
    $url = 'https://apps.msurvey.co.ke/surveyapi/ipn/4gcapital/';

    $request_headers = array();
    $request_headers[] = 'TOKEN: 8759382ecc1411e591ef34e6ad53e797';

    $vars = "msisdn=+" . $msisdn . "&domain=" . $domain . "&survey=" . $survey;


//open connection
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//execute post
    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);

    $result_data = json_decode($output, true);

    $sql = "INSERT INTO mobile_survey(customer_id, customer_mobile, domain, response_status, response)"
            . "VALUES(";

    $sql .= "'" . $customer_id . "','" . $msisdn . "','" . $domain . "','" . $result_data["status"]
            . "','" . $result_data["response"] . "');";


    $result1 = mysql_query($sql) or die(mysql_error());

    return $result1;
    
    //echo $result1;
}
//$data = getSurvey('254723165710', 'safaricom', '4GLOAN', '111');

//echo $data;

//$cl = new clear_loan();
//$l = $cl->get_loan_details('41351');
//
//echo $l;







