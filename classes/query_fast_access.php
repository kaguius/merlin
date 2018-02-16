<?php //

$url = "http://10.0.1.245:8080/score?key=eda2c6a97bec3e4fea2a97f9c7e77";
include_once 'get_score.php';
$customer_id = 32227;
$req_amount = 10000;
$user = 164;

$data = getScore($customer_id, $req_amount, $user);
$data_string = json_encode($data);

echo $data_string;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

$result = curl_exec($ch);

//echo $result;

$result_data = json_decode($result, true);
if ($result_data['success']) {


    $sql = "INSERT INTO score(score_id,response_status,response_risk_bucket,response_loan_size,response_interest_rate,customer_id,business_id,requested_amount,user_id)VALUES(";

    $score = $result_data["score"];
    $score["customer_id"] = $customer_id;
    $score["business_id"] = $data["borrower_data"]["business_id"];
    $score["requested_amount"] = $req_amount;

    $sql .= "'" . $score["score_id"] . "','" . $score["response_status"] . "','" . $score["response_risk_bucket"] . "','" . $score["response_loan_size"] . "','" . $score["response_interest_rate"] . "','" . $score["customer_id"] . "','" . $score["business_id"] . "','" . $score["requested_amount"] . "','" . $data["user_id"] . "');";

//echo $sql;
    saveScore($sql);
	$result1 = mysql_query($sql) or die(mysql_error());
}

echo $result;

